<?php
/**
 * �������
 * Created by PhpStorm.
 * User: Administrator
 * Date: 15-6-19
 * Time: ����4:57
 */
require_once BATH_PATH.'source/Dao/AssessDao.php';
require_once BATH_PATH.'source/Dao/AssessFlowDao.php';
$_REQUEST['act'] = (!isset($_REQUEST['act']))?'waitMeList':$_REQUEST['act'];

//��������б�ҳ
if($_REQUEST['act']=='waitMeList'){
    $assessDao = new AssessDao();
    $assessFlowDao = new AssessFlowDao();
    $assessFlowDao->setAssessDao($assessDao);
    $searchResult = $assessFlowDao->waitMeSearchHandlerList($_REQUEST);
}