<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 15-8-4
 * Time: ����9:16
 */
global $db;
global $ADODB_FETCH_MODE;
$this->db = $db;
$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;//ֻ��ѯ�����������
require_once BATH_PATH.'source/Dao/AssessDao.php';
if($act=='checkingAssessUpdate'){
    //��ȡ����������Ҫ�ᱨ�Ŀ���
    $base_status = 2; //������
    $sql = "select * from sa_assess_base where staff_sub_start_date=curdate() and base_status={$base_status}";
    $todayAssess = $db->getAll($sql);
    $todayAssess = $assessDao->getTodayAllCheckingAssess();
    $baseIds = array();
    foreach($todayAssess as $k=>$data){
        $baseIds[] = $data['base_id'];
    }

    $dbRes = true;
    //��assess_relation�����п����˸���
    $sql = "update sa_assess_user_relation set user_assess_status=4,updateTime=".date("Y-m-d H:i:s")." where base_id in (".implode(',',$baseIds).") and user_assess_status=3 ";
    $dbRes = $dbRes && $db->Execute($sql);

    //��assess_base�����
    $sql = "update sa_assess_base set base_status=3 where base_id in (".implode(',',$baseIds).") and base_status={$base_status}";
    $dbRes = $dbRes && $db->Execute($sql);
    $resStatus = ($dbRes)?'success':'fail';

    admin_log('�����ᱨ�ű�_'.$resStatus,'bindid',date("Y-m-d H:i:s"));

}

//���ƴﵽ�����������¿���
if($act=='reachCopyDayAssess'){
    $assessDao = new AssessDao();
    $sql = "select * from sa_assess_base where base_end_date='".date("Y-m-d",strtotime("+1 day"))."' and isNew=1";
    $baseRecord = $db->getAll($sql);
    foreach($baseRecord as $k=>$findBaseRecord){
        $baseId = $findBaseRecord['base_id'];
        //��¡����
        $assessDao->copyAssessDbHandler($findBaseRecord);
        $sql = "update sa_assess_base set isNew=0 where base_id={$baseId}";
        $dbRes = $db->Execute($sql);
        $resStatus = ($dbRes)?'success':'fail';
        admin_log('�����������¿��˽ű�_'.$resStatus,'bindid',$baseId);
    }
}