<?php
/**
* Created by PhpStorm.
* User: Administrator
*  Date: 15-6-19
*  Time: ����5:03
*  ����������
*/
require_once 'BaseDao.php';
class AssessFlowDao extends BaseDao{
    protected $assessDao;
    static $UserAssessStatusByHr = array(
        '0'=>'�������˴���',
        '1'=>'������������д�ƻ�',
        '2'=>'�������˳���',
        '3'=>"������",
        '4'=>'���������˻㱨',
        '5'=>'������������',
        '6'=>'�������',
        '7'=>'���˽���'
    );

    static $UserAssessStatusByLeader = array(
        '0'=>'���Ҵ���',
        '1'=>'������������д�ƻ�',
        '2'=>'���ҳ���',
        '3'=>"������",
        '4'=>'�������㱨',
        '5'=>'��������',
        '6'=>'�������',
        '7'=>'���˽���'
    );

    static $UserAssessStatusByStaff = array(
        '0'=>'�������˴���',
        '1'=>"������д�ƻ�",
        '2'=>"�������˳���",
        '3'=>"������",
        '4'=>'���һ㱨',
        '5'=>"������������",
        '6'=>'�������',
        '7'=>'���˽���'
    );


    const AssessCreate= 0;//���쵼����
    const AssessPreStaffWrite = 1;//��Ա����д
    const AssessPreLeadVIew = 2;//���쵼����|Ա������д���Ԥ��
    const AssessChecking = 3;//������
    const AssessPreReport = 4;//���һ㱨
    const AssessRealLeadView = 5;//���쵼����|Ա������д��ʵ��
    const AssessRealSuccess = 6;//�쵼����ͨ��
    const AssessRealOver = 7;//���˽���
    public function setAssessDao(AssessDao $assessDao){
        $this->assessDao = $assessDao;
    }

    public function waitMeSearchHandlerList($searchParam){
        $searchResult = array();
        $searchParam['base_status'] = 1; // �ѷ���״̬
        $searchParam = $this->filterConditionParam($searchParam,array('byme_status'));
        $searchResult = $this->assessDao->getBaseSearchHandlerList('sa_assess_base',$searchParam);
        //�����쵼��Ӧ��Ա����baseIdList
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

    //��ȡ�쵼��ص�baseIds
    public function getBaseIdsForLeader($params){
        $status = $params['status'];
        $baseIdList = array();
        $curUserId = getUserId();
        $addStatusSql = "and a.status=$status ";//�����쵼״̬
        if(isset($params['user_assess_status']) && $params['user_assess_status']!==''){
            $addStatusSql.=" and b.user_assess_status={$params['user_assess_status']}";
        }
        $getRelationBaseIdSql = "select c.base_id,c.base_status from sa_user_relation as a
                                 inner join sa_assess_user_relation as b on a.super_userId={$curUserId} and a.low_userId=b.userId  $addStatusSql
                                 inner join sa_assess_base as c on b.base_Id=c.base_Id  group  by  c.base_id";
        $result = $this->db->getAll($getRelationBaseIdSql);
        if($result){
            foreach($result as $data){
                //���˵���HR����״̬�Ŀ���
                if($data['base_status']!=0){
                    $baseIdList[] = $data['base_id'];
                }
            }
        }

        return $baseIdList;
    }

    //��ȡĳһ�������쵼������Ա��
    public function getStaffListForLeaderSql($conditionParams = array()){
        $base_Id = $conditionParams['base_id'];
        $curUserId = getUserId();
        $addSql = "";
        $pageConditionUrl = '';
        $resultList = array();
        if(isset($conditionParams['user_assess_status']) && $conditionParams['user_assess_status']!==''){
            $addSql.= "and b.user_assess_status={$conditionParams['user_assess_status']}";//�����û���д״̬����
            $pageConditionUrl.="&user_assess_status={$conditionParams['user_assess_status']}";
        }

        if(isset($conditionParams['username']) && $conditionParams['username']){
            $addSql.= "and a.username like'%{$conditionParams['username']}%'"; //�����û���ģ����ѯ����
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
            //��ȡ��������д������Ϣ
            $userAssessItemSql = "select a.*,a.item_weight as weight from sa_assess_user_item as a where a.base_id={$baseId} and a.userId={$userId}";
            $userAssessItem = $this->db->GetAll($userAssessItemSql);
            $resultRecord['relation'] = $relationRecord;
            $resultRecord['item'] = $userAssessItem;
        }
        return $resultRecord;
    }

    //��ȡ������״̬��Ϣ
    public function getUserRelationRecord($baseId,$userId){
        $userAssessRelationSql = "select a.*,b.username from sa_assess_user_relation as a
                                    inner join sa_user as b on a.userId=b.userId
                                     where a.userId={$userId} and a.base_id = {$baseId}";
        $relationRecord = $this->db->GetRow($userAssessRelationSql);
        return $relationRecord;
    }

    //��ȡ�û������б�ҳ
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
                        case 1://����ָ��
                            $finalScore+=$item['leadScore']*$item['qz']/100;
                            break;

                        case 2://��������
                            $finalScore+=$item['leadScore']*$item['qz']/100;
                            break;

                        case 3://�����
                            $finalScore+=$item['cash']*$item['leadScore'];
                            break;

                        case 4://�����
                            $finalScore = $item['tc_name']*$item['finishCash'];
                            break;
                    }
                }
            }
        }
        return $finalScore;
    }

    //���쵼����ת��Ա������
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
        //��������
        if(isset($params['assess_attr_type']) && $params['assess_attr_type']){
            $addSql.=" and a.assess_attr_type={$params['assess_attr_type']}";
        }

        //����״̬
        if(isset($params['user_assess_status']) && $params['user_assess_status']!==''){
            $addSql.=" and a.user_assess_status={$params['user_assess_status']}";
        }

        //��������
        if(isset($params['assess_period_type']) && $params['assess_period_type']){
            $addSql.=" and b.assess_period_type={$params['assess_period_type']}";
        }

        //���
        if(isset($params['assess_year']) && $params['assess_year']){
            $addSql.=" and b.assess_year={$params['assess_year']}";
        }

        //�·�
        if(isset($params['assess_month']) && $params['assess_month']){
            $params['assess_month'] = intval($params['assess_month']);
            $addSql.=" and b.assess_month={$params['assess_month']}";
        }

        //����
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