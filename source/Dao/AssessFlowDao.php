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

    static $UserAssessStatusByLeader = array(
        '0'=>'���Ҵ���',
        '1'=>'������������д�ƻ�',
        '2'=>'���ҳ���',
        '3'=>"������",
        '4'=>"������",
        '5'=>'�������㱨',
        '6'=>'��������',
        '7'=>'�������',
        '8'=>'���˽���'
    );

    static $UserAssessStatusByStaff = array(
        '0'=>'�������˴���',
        '1'=>"������д�ƻ�",
        '2'=>"�������˳���",
        '3'=>'������',
        '4'=>"������",
        '5'=>'���һ㱨',
        '6'=>"������������",
        '7'=>'�������',
        '8'=>'���˽���'
    );


    const AssessCreate= 0;//���쵼����
    const AssessPreStaffWrite = 1;//��Ա����д
    const AssessPreLeadVIew = 2;//���쵼����|Ա������д���Ԥ��
    const AssessWait = 3;//���쵼����|Ա������д���Ԥ��
    const AssessPreSuccess = 4;//�쵼����ͨ��
    const AssessPreReport = 5;//���һ㱨
    const AssessRealLeadView = 6;//���쵼����|Ա������д��ʵ��
    const AssessRealSuccess = 7;//�쵼����ͨ��

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
        if(isset($conditionParams['bus_area_parent']) && $conditionParams['bus_area_parent']){
            $addSql.=" and a.tixi={$conditionParams['bus_area_parent']}";
            $pageConditionUrl.="&bus_area_parent={$conditionParams['bus_area_parent']}";
        }

        if(isset($conditionParams['bus_area_child']) && $conditionParams['bus_area_child']){
            $addSql.=" and a.comp_dept={$conditionParams['bus_area_child']}";
            $pageConditionUrl.="&bus_area_child={$conditionParams['bus_area_child']}";
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
        //��ȡ������״̬��Ϣ
        $userAssessRelationSql = "select a.*,b.username from sa_assess_user_relation as a
                                    inner join sa_user as b on a.userId=b.userId
                                     where a.userId={$userId} and a.base_id = {$baseId}";
        //echo $userAssessRelationSql."<br/>";
        if($relationRecord = $this->db->GetRow($userAssessRelationSql)){
            //��ȡ��������д������Ϣ
            $userAssessItemSql = "select a.*,a.item_weight as weight from sa_assess_user_item as a where a.base_id={$baseId} and a.userId={$userId}";
            //echo $userAssessItemSql."<br/>";
            $userAssessItem = $this->db->GetAll($userAssessItemSql);
            $resultRecord['relation'] = $relationRecord;
            $resultRecord['item'] = $userAssessItem;
        }
        return $resultRecord;
    }

    //��ȡ�û������б�ҳ
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