<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 15-8-4
 * Time: 上午9:16
 */
global $db;
global $ADODB_FETCH_MODE;
$this->db = $db;
$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;//只查询关联索引结果
require_once BATH_PATH.'source/Dao/AssessDao.php';
if($act=='checkingAssessUpdate'){
    //获取今天所有需要提报的考核
    $base_status = 2; //考核中
    $sql = "select * from sa_assess_base where staff_sub_start_date=curdate() and base_status={$base_status}";
    $todayAssess = $db->getAll($sql);
    $todayAssess = $assessDao->getTodayAllCheckingAssess();
    $baseIds = array();
    foreach($todayAssess as $k=>$data){
        $baseIds[] = $data['base_id'];
    }

    $dbRes = true;
    //将assess_relation表所有考核人更新
    $sql = "update sa_assess_user_relation set user_assess_status=4,updateTime=".date("Y-m-d H:i:s")." where base_id in (".implode(',',$baseIds).") and user_assess_status=3 ";
    $dbRes = $dbRes && $db->Execute($sql);

    //将assess_base表更新
    $sql = "update sa_assess_base set base_status=3 where base_id in (".implode(',',$baseIds).") and base_status={$base_status}";
    $dbRes = $dbRes && $db->Execute($sql);
    $resStatus = ($dbRes)?'success':'fail';

    admin_log('更新提报脚本_'.$resStatus,'bindid',date("Y-m-d H:i:s"));

}

//复制达到按周期生成新考核
if($act=='reachCopyDayAssess'){
    $assessDao = new AssessDao();
    $sql = "select * from sa_assess_base where base_end_date='".date("Y-m-d",strtotime("+1 day"))."' and isNew=1";
    $baseRecord = $db->getAll($sql);
    foreach($baseRecord as $k=>$findBaseRecord){
        $baseId = $findBaseRecord['base_id'];
        //克隆数据
        $assessDao->copyAssessDbHandler($findBaseRecord);
        $sql = "update sa_assess_base set isNew=0 where base_id={$baseId}";
        $dbRes = $db->Execute($sql);
        $resStatus = ($dbRes)?'success':'fail';
        admin_log('按周期生成新考核脚本_'.$resStatus,'bindid',$baseId);
    }
}