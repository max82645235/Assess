<?php
/**
* Created by PhpStorm.
* User: Administrator
*  Date: 15-6-19
*  Time: 下午5:03
*  考核流程类
*/
require_once 'BaseDao.php';
class AssessFlowDao extends BaseDao{
    protected $assessDao;
    static $UserAssessStatusByHr = array(
        '0'=>'待考核人创建',
        '1'=>'待被考核人填写计划',
        '2'=>'待考核人初审',
        '3'=>"考核中",
        '4'=>'待被考核人汇报',
        '5'=>'待考核人终审',
        '6'=>'终审完成',
        '7'=>'考核结束'
    );

    static $UserAssessStatusByLeader = array(
        '0'=>'待我创建',
        '1'=>'待被考核人填写计划',
        '2'=>'待我初审',
        '3'=>"考核中",
        '4'=>'待下属汇报',
        '5'=>'待我终审',
        '6'=>'终审完成',
        '7'=>'考核结束'
    );

    static $UserAssessStatusByStaff = array(
        '0'=>'待考核人创建',
        '1'=>"待我填写计划",
        '2'=>"待考核人初审",
        '3'=>"考核中",
        '4'=>'待我汇报',
        '5'=>"待考核人终审",
        '6'=>'终审完成',
        '7'=>'考核结束'
    );


    const AssessCreate= 0;//待领导创建
    const AssessPreStaffWrite = 1;//待员工填写
    const AssessPreLeadVIew = 2;//待领导初审|员工已填写完成预期
    const AssessChecking = 3;//考核中
    const AssessPreReport = 4;//待我汇报
    const AssessRealLeadView = 5;//待领导终审|员工已填写完实际
    const AssessRealSuccess = 6;//领导终审通过
    const AssessRealOver = 7;//考核结束
    public function setAssessDao(AssessDao $assessDao){
        $this->assessDao = $assessDao;
    }

    public function waitMeSearchHandlerList($searchParam){
        $searchResult = array();
        $searchParam['base_status'] = 1; // 已发布状态
        $searchParam = $this->filterConditionParam($searchParam,array('byme_status'));
        $searchResult = $this->assessDao->getBaseSearchHandlerList('sa_assess_base',$searchParam);
        //附加领导对应的员工的baseIdList
        if($baseIdList = $this->getBaseIdsForLeader($searchParam)){
            $searchResult['sqlWhere'].= " AND sa_assess_base.base_id in (".implode(',',$baseIdList).")";
        }else{
            $searchResult['sqlWhere'].= ' AND 1=0 ';
        }
        return $searchResult;
    }



    public function filterConditionParam($searchParam=array(),$filterParam = array()){
        foreach($searchParam as $key=>$v){
            if($filterParam && in_array($key,$filterParam)){
                unset($searchParam[$key]);
            }
        }
        return $searchParam;
    }

    //获取领导相关的baseIds
    public function getBaseIdsForLeader($params){
        $status = $params['status'];
        $baseIdList = array();
        $curUserId = getUserId();
        $addStatusSql = "and a.status=$status ";//隶属领导状态
        if(isset($params['user_assess_status']) && $params['user_assess_status']!==''){
            $addStatusSql.=" and b.user_assess_status={$params['user_assess_status']}";
        }
        $getRelationBaseIdSql = "select c.base_id,c.base_status from sa_user_relation as a
                                 inner join sa_assess_user_relation as b on a.super_userId={$curUserId} and a.low_userId=b.userId  $addStatusSql
                                 inner join sa_assess_base as c on b.base_Id=c.base_Id  group  by  c.base_id";
        $result = $this->db->getAll($getRelationBaseIdSql);
        if($result){
            foreach($result as $data){
                //过滤掉待HR发布状态的考核
                if($data['base_status']!=0){
                    $baseIdList[] = $data['base_id'];
                }
            }
        }

        return $baseIdList;
    }

    //获取某一考核下领导的下属员工
    public function getStaffListForLeaderSql($conditionParams = array()){
        $base_Id = $conditionParams['base_id'];
        $curUserId = getUserId();
        $addSql = "";
        $pageConditionUrl = '';
        $resultList = array();
        if(isset($conditionParams['user_assess_status']) && $conditionParams['user_assess_status']!==''){
            $addSql.= "and b.user_assess_status={$conditionParams['user_assess_status']}";//附加用户填写状态条件
            $pageConditionUrl.="&user_assess_status={$conditionParams['user_assess_status']}";
        }

        if(isset($conditionParams['username']) && $conditionParams['username']){
            $addSql.= "and a.username like'%{$conditionParams['username']}%'"; //附加用户名模糊查询条件
            $pageConditionUrl.="&username={$conditionParams['username']}";
        }
        $resultList['staffListSql'] = "select [*] from sa_user as a
                inner join sa_assess_user_relation as b on a.userId=b.userId and b.base_id={$base_Id} $addSql
                inner join sa_user_relation as c on c.super_userId={$curUserId} and c.low_userId = a.userId ";
        $resultList['pageConditionUrl'] = $pageConditionUrl;
        return $resultList;

    }

    public function validLeaderSetFlow($userAssessStatus){
        $allowStatusList = array(
            self::AssessCreate,
            self::AssessPreLeadVIew,
            self::AssessRealLeadView
        );
        if(in_array($userAssessStatus,$allowStatusList)){
            return true;
        }else{
            throw new Exception('500');
        }
    }

    public function validStuffSetFow($userAssessStatus){
        $allowStatusList = array(
            self::AssessPreStaffWrite,
            self::AssessPreReport,
        );
        if(in_array($userAssessStatus,$allowStatusList)){
            return true;
        }else{
            throw new Exception('500');
        }
    }

    public function getUserAssessRecord($baseId,$userId){
        $resultRecord = array();
        $relationRecord = $this->getUserRelationRecord($baseId,$userId);
        if($relationRecord){
            //获取考核人填写具体信息
            $userAssessItemSql = "select a.*,a.item_weight as weight from sa_assess_user_item as a where a.base_id={$baseId} and a.userId={$userId}";
            $userAssessItem = $this->db->GetAll($userAssessItemSql);
            $resultRecord['relation'] = $relationRecord;
            $resultRecord['item'] = $userAssessItem;
        }
        return $resultRecord;
    }

    //获取考核人状态信息
    public function getUserRelationRecord($baseId,$userId){
        $userAssessRelationSql = "select a.*,b.username from sa_assess_user_relation as a
                                    inner join sa_user as b on a.userId=b.userId
                                     where a.userId={$userId} and a.base_id = {$baseId}";
        $relationRecord = $this->db->GetRow($userAssessRelationSql);
        return $relationRecord;
    }

    //获取用户考核列表页
    public function getMyAssessSearchHandlerListSql($userId,$conditionParams = array()){
        $sql = "select [*] from sa_assess_user_relation as a
                inner join sa_assess_base as b on a.base_id = b.base_id where a.userId={$userId} and b.base_status>0";
        $pageConditionUrl = '';
        $resultList = array();
        if(isset($conditionParams['assess_period_type']) && $conditionParams['assess_period_type']){
            $sql.=" and b.assess_period_type={$conditionParams['assess_period_type']}";
            $pageConditionUrl.="&assess_period_type={$conditionParams['assess_period_type']}";
        }

        if(isset($conditionParams['user_assess_status']) && $conditionParams['user_assess_status']!==''){
            $sql.=" and a.user_assess_status={$conditionParams['user_assess_status']}";
            $pageConditionUrl.="&user_assess_status={$conditionParams['user_assess_status']}";
        }

        if(isset($conditionParams['base_name']) && $conditionParams['base_name']){
            $sql.=" and b.base_name like '%{$conditionParams['base_name']}%'";
            $pageConditionUrl.="&base_name={$conditionParams['base_name']}";
        }
        $resultList = array(
            'sql' => $sql,
            'pageConditionUrl'=>$pageConditionUrl
        );
        return $resultList;
    }

    public function changeCheckingStatus($userId,$baseId){
        $relationRecord = $this->getUserRelationRecord($baseId,$userId);
        if($relationRecord && $relationRecord['user_assess_status'] == self::AssessChecking){
            $relationRecord['user_assess_status'] = self::AssessPreLeadVIew;
            $where = " rid={$relationRecord['rid']}";
            unset($relationRecord['rid']);
            unset($relationRecord['username']);
            $sql = self::get_update_sql('sa_assess_user_relation',$relationRecord,$where);
            $this->db->Execute($sql);
            $conditionUrl = $this->getConditionParamUrl(array('act'));
            $location = P_SYSPATH."index.php?act=myStaffList&$conditionUrl";
            header("Location: $location");
        }
    }

    public function getUserAssessScore($attrRecord){
        $finalScore = 0;
        if($attrRecord){
            foreach($attrRecord as $k=>$data){
                $itemData = unserialize($data['itemData']);
                foreach($itemData as $i=>$item){
                    switch($data['attr_type']){
                        case 1://量化指标
                            $finalScore+=$item['leadScore']*$item['qz']/100;
                            break;

                        case 2://工作任务
                            $finalScore+=$item['leadScore']*$item['qz']/100;
                            break;

                        case 3://打分类
                            $finalScore+=$item['cash']*$item['leadScore'];
                            break;

                        case 4://提成类
                            $finalScore = $item['tc_name']*$item['finishCash'];
                            break;
                    }
                }
            }
        }
        return $finalScore;
    }

    //将领导设置转给员工设置
    public function changeCreateToStaff($baseId,$userId=''){
        $leadId = getUserId();
        $user_assess_status = self::AssessCreate;
        $sql = "select a.rid from sa_assess_user_relation as a
                inner join sa_assess_base as b on a.base_id = b.base_id and b.lead_direct_set_status=1 and a.user_assess_status={$user_assess_status}
                inner join sa_user_relation as c on  c.super_userId={$leadId} and a.userId=c.low_userId";
        $records = $this->db->GetAll($sql);
        if($records){
            $rids = '';
            foreach($records as $rid){
                $rids.=$rid['rid'].",";
            }
            $rids = substr($rids,0,-1);
            $addSql = '';
            if($userId){
                $addSql = " and userId in ({$userId})";
            }
            $updateSql = "update sa_assess_user_relation set user_assess_status=1 where base_id={$baseId}   and user_assess_status=0 and rid in ({$rids}) {$addSql}";
            $this->db->Execute($updateSql);
            return true;
        }
    }

    public function triggerStatusUpdate($base_id,$userId){
        $sql = "update sa_assess_user_relation set user_assess_status=4 where base_id={$base_id} and userId ={$userId} and user_assess_status=3";
        $this->db->Execute($sql);
    }

    public function getAssessReportData($params){
        $addSql = '';
        //考核类型
        if(isset($params['assess_attr_type']) && $params['assess_attr_type']){
            $addSql.=" and a.assess_attr_type={$params['assess_attr_type']}";
        }

        //考核状态
        if(isset($params['user_assess_status']) && $params['user_assess_status']!==''){
            $addSql.=" and a.user_assess_status={$params['user_assess_status']}";
        }

        //考核周期
        if(isset($params['assess_period_type']) && $params['assess_period_type']){
            $addSql.=" and b.assess_period_type={$params['assess_period_type']}";
        }

        //年份
        if(isset($params['assess_year']) && $params['assess_year']){
            $addSql.=" and b.assess_year={$params['assess_year']}";
        }

        //月份
        if(isset($params['assess_month']) && $params['assess_month']){
            $params['assess_month'] = intval($params['assess_month']);
            $addSql.=" and b.assess_month={$params['assess_month']}";
        }

        //姓名
        if(isset($params['username']) && $params['username']){
            $addSql.=" and c.username like '%{$params['username']}%' ";
        }


        $sql = "select a.*,b.assess_period_type,b.base_start_date,b.base_end_date,c.username from sa_assess_user_relation as a
                inner join sa_assess_base as b on a.base_id=b.base_id
                inner join sa_user as c on a.userId=c.userId
                where 1=1 {$addSql} group  by a.base_id ";
        $retData = $this->db->GetAll($sql);
        return $retData;
    }
}