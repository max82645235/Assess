<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 15-8-4
 * Time: 上午9:16
 */
global $db;
global $ADODB_FETCH_MODE;
$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;//只查询关联索引结果
require_once BATH_PATH.'source/Dao/AssessDao.php';

if($_REQUEST['key'] != md5('job_key')){
    exit;
}

if($a == 'refresh_relation'){
    if(!$_REQUEST['uid']){
        halt("error input");
    }

    $uid = $_REQUEST['uid'];

    refresh_relation($uid);

    halt("OK");
}


//更改提报状态
if($_REQUEST['a']=='checkingAssessUpdate'){
    //获取今天所有需要提报的考核
    $base_status = 2; //考核中
    //获取员工提报时间为今天或已过今天的且考核状态的所有考核
    $sql = "select * from sa_assess_base where staff_sub_start_date<=curdate() and base_status={$base_status}";
    $todayAssess = $db->getAll($sql);
    $baseIds = array();
    foreach($todayAssess as $k=>$data){
        $baseIds[] = $data['base_id'];
    }
    if($baseIds){
        $dbRes = true;
        //将在baseIds内的assess_relation表所有考核人更新到提报状态
        $sql = "update sa_assess_user_relation set user_assess_status=4,updateTime='".date("Y-m-d H:i:s")."' where base_id in (".implode(',',$baseIds).") and user_assess_status=3 ";
        $dbRes = $dbRes && $db->Execute($sql);

        //将assess_base表更新
        $sql = "update sa_assess_base set base_status=3 where base_id in (".implode(',',$baseIds).") and base_status={$base_status}";
        $dbRes = $dbRes && $db->Execute($sql);
        $resStatus = ($dbRes)?'success':'fail';

        admin_log('更新提报脚本_'.$resStatus,'bindid',date("Y-m-d H:i:s"));
    }
    echo 'success';
}

//复制达到按周期生成新考核
if($_REQUEST['a']=='reachCopyDayAssess'){
    $assessDao = new AssessDao();
    //获取按月生成状态位为1  且达到考核结束日期 且isNew状态位1的所有考核
    $sql = "select * from sa_assess_base where create_on_month_status=1 and base_end_date='".date("Y-m-d",strtotime("-1 day"))."' and isNew=1";
    $baseRecord = $db->getAll($sql);
    foreach($baseRecord as $k=>$findBaseRecord){
        $baseId = $findBaseRecord['base_id'];
        //克隆数据
        $findBaseRecord['base_start_date'] = date('Y-m-d');//将当前日期做为复制考核的开始日期
        $findBaseRecord['base_end_date'] = AssessDao::getAssessBaseEndDate($findBaseRecord['assess_period_type'],$findBaseRecord['base_start_date']);
        $findBaseRecord['staff_sub_start_date'] = date('Y-m-d',strtotime('+'.$findBaseRecord['assess_period_type'].' month',strtotime($findBaseRecord['staff_sub_start_date'])));
        $yearMonthArr = $assessDao->getAssessYearMonth($findBaseRecord['base_start_date']);
        $findBaseRecord['base_name'] = $findBaseRecord['base_name']."_".$yearMonthArr['assess_year']."-".$yearMonthArr['assess_month'];//默认在克隆的考核标题后面加上时间后缀
        $assessDao->copyAssessDbHandler($findBaseRecord);
        $sql = "update sa_assess_base set isNew=0 where base_id={$baseId}";
        $dbRes = $db->Execute($sql);
        $resStatus = ($dbRes)?'success':'fail';
        echo $resStatus;
        admin_log('按周期生成新考核脚本_'.$resStatus,'bindid',$baseId);
    }
    echo 'success';
}