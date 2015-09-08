<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 15-9-6
 * Time: 下午1:19
 */
global $ADODB_FETCH_MODE;
$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;//只查询关联索引结果
require_once 'BaseDao.php';
class FreeFlowDao extends BaseDao{
    //判断是否属于$userId正在处理的自由流  返回true/false
    static $isMyStatus;
    static function validIsMyFlowing($baseId,$userId){
        if(!self::$isMyStatus){
            global $db;
            $curUserId = getUserId();
            $userAssessStatus = AssessFlowDao::AssessRealLeadView;
            $sql = "select a.flow_id,a.userId from sa_free_flow as a
                inner join sa_assess_user_relation as b  on a.rid=b.rid and a.isNew=1
                where  b.userId=$userId and b.base_id=$baseId and b.user_assess_status=$userAssessStatus";
            $res = $db->GetRow($sql);
            if($res){
                self::$isMyStatus['status'] = ($res['userId']==$curUserId)?true:false;
            }else{
                self::$isMyStatus['status'] = false;
            }
            self::$isMyStatus = array(
                'status'=>($res)?true:false
            );
        }
        return self::$isMyStatus['status'];
    }

    static function getFreeFlowList($rid){
        global $db;
        $sql = "select a.*,b.username,b.card_no,b.deptlist from sa_free_flow as a
                    left join sa_user as b on a.userId=b.userId
                    where rid=$rid";
        $res = $db->GetAll($sql);
        return $res;
    }

    static function setNewFlowRecord($rid,$requestParam){
        global $db;
        $userId = $requestParam['freeFlowUserId'];
        $description = iconv('UTF-8','GBK//IGNORE',$requestParam['description']);
        $curUserId = getUserId();
        $dateTime = date("Y-m-d H:i:s");
        if(!self::getFreeFlowList($rid)){
            $sql = "insert into sa_free_flow (rid,userId,isNew,create_time) values($rid,$curUserId,0,'$dateTime')";
            $db->Execute($sql);
        }else{
            //将当前自由流状态isNew修改为0
            $sql = "update sa_free_flow set isNew=0,over_time='{$dateTime}' where rid=$rid and userId=$curUserId";
            $db->Execute($sql);
        }

        //插入自由流下一步
        $sql = "insert into sa_free_flow (rid,userId,isNew,create_time,description) values($rid,$userId,1,'$dateTime','$description')";
        $db->Execute($sql);
    }

    //获取用户所有的自由流数据
    static function getAllFreeFlowList($conditionParams = array()){
        $userId = getUserId();
        $addSql = "";
        $pageConditionUrl = "";
        $resultList = array();
        if($conditionParams['base_name']){
            $addSql.= " and c.base_name like '%{$conditionParams['base_name']}%'";
            $pageConditionUrl.= "&base_name={$conditionParams['base_name']}";
        }

        if($conditionParams['isNew']){
            $isNew = $conditionParams['isNew'];
            if($conditionParams['isNew']==1){
                $addSql.= " and a.isNew=1";
            }

            if($conditionParams['isNew']==2){
                $addSql.= " and a.isNew!=1";
            }

            $pageConditionUrl.= "&isNew=".$isNew;
        }
        $sql = "select t.* from (
                  select a.isNew,a.flow_id,b.user_assess_status,b.rejectStatus,b.userId,b.base_id,
                      c.base_name,c.assess_period_type,c.base_start_date,c.base_end_date,
                      d.username,d.card_no,d.deptlist
                  from sa_free_flow as a
                  inner join sa_assess_user_relation as b on a.userId=$userId and a.rid=b.rid
                  inner join sa_assess_base as c on b.base_id=c.base_id
                  inner join sa_user as d on b.userId=d.userId where 1=1 {$addSql} order by a.isNew desc
                ) as t group by t.base_id order by t.flow_id;";
        $resultList['sql'] = $sql;
        $resultList['pageConditionUrl'] = $pageConditionUrl;
        return $resultList;
    }
}