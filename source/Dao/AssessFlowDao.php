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
        '1'=>'待下属填写计划',
        '2'=>'待我初审',
        '3'=>"考核中",
        '4'=>'待下属汇报',
        '5'=>'待我终审',
        '6'=>'终审完成'
    );

    static $UserAssessStatusByStaff = array(
        '1'=>"待我制定计划",
        '2'=>"待领导初审",
        '3'=>'考核中',
        '4'=>'待我汇报',
        '5'=>"待领导终审",
        '6'=>'终审完成'
    );

    const AssessCreate= 0;//待领导创建
    const AssessPreStaffWrite = 1;//待员工填写
    const AssessPreLeadVIew = 2;//待领导初审|员工已填写完成预期
    const AssessPreSuccess = 3;//领导初审通过
    const AssessPreReport = 4;//待我汇报
    const AssessRealLeadView = 5;//待领导终审|员工已填写完实际
    const AssessRealSuccess = 6;//领导终审通过

    public function setAssessDao(AssessDao $assessDao){
        $this->assessDao = $assessDao;
    }

    public function waitMeSearchHandlerList($searchParam){
        $searchResult = array();
        $searchParam = $this->filterConditionParam($searchParam,array('byme_status'));
        $hrSearchResult = $this->assessDao->getBaseSearchHandlerList('sa_assess_base',$searchParam);
        //附加领导对应的员工的baseIdList
        if($baseIdList = $this->getBaseIdsForLeader($searchParam['status'])){
            $hrSearchResult['sqlWhere'].= " AND sa_assess_base.base_id in (".implode(',',$baseIdList).")";
        }else{
            $hrSearchResult['sqlWhere'].= ' AND 1=0 ';
        }
        return $hrSearchResult;
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
    public function getBaseIdsForLeader($status){
        $baseIdList = array();
        $curUserId = getUserBusId();
        $addStatusSql = "and a.status=$status ";
        $getRelationBaseIdSql = "select c.base_id,c.base_status from sa_user_relation as a
                                    left join sa_assess_user_relation as b on a.super_userId={$curUserId} and a.low_userId=b.userId $addStatusSql
                                    left join sa_assess_base as c on b.base_Id=c.base_Id  group  by  c.base_id";
        //echo $getRelationBaseIdSql."</br>";
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
        $userAssessStatus = $conditionParams['userAssessStatus'];
        $userName = $conditionParams['userName'];
        $curUserId = getUserBusId();
        $addSql = "";
        $addSql.= "and b.status=$userAssessStatus ";//附加用户填写状态条件
        $addSql.= ($userName)?" and a like'%{$userName}%'":""; //附加用户名模糊查询条件
        $staffListSql = "select [*] from sa_user as a
                left join sa_assess_user_relation as b on a.userId=b.userId and b.base_id={$base_Id} $addSql
                left join sa_user_relation as c on c.super_userId={$curUserId} and c.low_userId = a.userId ";
        return $staffListSql;

    }
}