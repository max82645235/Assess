<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 15-6-30
 * Time: 上午8:55
 */
$_REQUEST['act'] = (!isset($_REQUEST['act']))?'assessReportList':$_REQUEST['act'];
require_once BATH_PATH.'source/Dao/AssessDao.php';
require_once BATH_PATH.'source/Dao/AssessFlowDao.php';
//绩效考核报表
if($_REQUEST['act']=='assessReportList'){
    $assessFlowDao = new AssessFlowDao();
    $date = explode('-',date("Y-m-d"));
    $_REQUEST['assess_year'] = (!isset($_REQUEST['assess_year']))?$date[0]:$_REQUEST['assess_year'];
    $_REQUEST['assess_month'] = (!isset($_REQUEST['assess_month']))?intval($date[1]):$_REQUEST['assess_month'];
    $tableData = $assessFlowDao->getAssessReportData($_REQUEST);
    $tpl = new NewTpl('report/assessReportList.php',array(
        'tableData'=>$tableData
    ));
    $tpl->render();
    die();
}