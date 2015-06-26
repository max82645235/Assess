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

    static $UserAssessStatusByLeader = array(
        '0'=>'待我创建',
        '1'=>'待被考核人填写计划',
        '2'=>'待我初审',
        '3'=>"待考核",
        '4'=>"考核中",
        '5'=>'待下属汇报',
        '6'=>'待我终审',
        '7'=>'终审完成',
        '8'=>'考核结束'
    );

    static $UserAssessStatusByStaff = array(
        '0'=>'待考核人创建',
        '1'=>"待我填写计划",
        '2'=>"待考核人初审",
        '3'=>'待考核',
        '4'=>"考核中",
        '5'=>'待我汇报',
        '6'=>"待考核人终审",
        '7'=>'终审完成',
        '8'=>'考核结束'
    );


    const AssessCreate= 0;//待领导创建
    const AssessPreStaffWrite = 1;//待员工填写
    const AssessPreLeadVIew = 2;//待领导初审|员工已填写完成预期
    const AssessWait = 3;//待领导初审|员工已填写完成预期
    const AssessPreSuccess = 4;//领导初审通过
    const AssessPreReport = 5;//待我汇报
    const AssessRealLeadView = 6;//待领导终审|员工已填写完实际
    const AssessRealSuccess = 7;//领导终审通过

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
        if(isset($conditionParams['bus_area_parent']) && $conditionParams['bus_area_parent']){
            $addSql.=" and a.tixi={$conditionParams['bus_area_parent']}";
            $pageConditionUrl.="&bus_area_parent={$conditionParams['bus_area_parent']}";
        }

        if(isset($conditionParams['bus_area_child']) && $conditionParams['bus_area_child']){
            $addSql.=" and a.comp_dept={$conditionParams['bus_area_child']}";
            $pageConditionUrl.="&bus_area_child={$conditionParams['bus_area_child']}";
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
        //获取考核人状态信息
        $userAssessRelationSql = "select a.*,b.username from sa_assess_user_relation as a
                                    inner join sa_user as b on a.userId=b.userId
                                     where a.userId={$userId} and a.base_id = {$baseId}";
        //echo $userAssessRelationSql."<br/>";
        if($relationRecord = $this->db->GetRow($userAssessRelationSql)){
            //获取考核人填写具体信息
            $userAssessItemSql = "select a.*,a.item_weight as weight from sa_assess_user_item as a where a.base_id={$baseId} and a.userId={$userId}";
            //echo $userAssessItemSql."<br/>";
            $userAssessItem = $this->db->GetAll($userAssessItemSql);
            $resultRecord['relation'] = $relationRecord;
            $resultRecord['item'] = $userAssessItem;
        }
        return $resultRecord;
    }

    //获取用户考核列表页
    public function getMyAssessSearchHandlerListSql($userId,$conditionParams = array()){
        $sql = "select [*] from sa_assess_user_relation as a
                inner join sa_assess_base as b on a.base_id = b.base_id where a.userId={$userId}";
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
}