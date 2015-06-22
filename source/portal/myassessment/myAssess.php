<?php
/**
 * Created by PhpStorm.
 * User: pc
 * Date: 15-6-22
 * Time: 下午3:52
 */
require_once BATH_PATH.'source/Dao/AssessDao.php';
require_once BATH_PATH.'source/Dao/AssessFlowDao.php';
$_REQUEST['act'] = (!isset($_REQUEST['act']))?'myAssessList':$_REQUEST['act'];
//我的考核列表页
if($_REQUEST['act']=='myAssessList'){
    $assessDao = new AssessDao();
    $assessFlowDao = new AssessFlowDao();
    $userId = getUserId();
    $userId = 2;
    if(!isset($_REQUEST['base_status']) || !array_key_exists($_REQUEST['base_status'],AssessFlowDao::$UserAssessStatusByStaff)){
        $_REQUEST['base_status'] = 1;//默认为 我待填写计划状态
    }
    $searchResult = $assessFlowDao->getMyAssessSearchHandlerListSql($userId,$_REQUEST);
    $pageurl = '?m='.$m.'&a='.$a.$searchResult['pageConditionUrl'];
    $sql = $searchResult['sql'];
    //获取分页page_nav
    $countSql = " count(a.*)";
    $countSql = str_replace('[*]',$countSql,$sql);
    $count = $db->GetOne($sql);
    $page = isset($_GET['pn']) ? (int)$_GET['pn'] : 1;
    $limit = 10;
    $offset = ($page-1)*$limit;
    $page_nav = page($count,$limit,$page,$pageurl);
    //获取表格查询结果
    $findSql = " a.user_assess_status,b.*";
    $findSql = str_replace('[*]',$findSql,$sql);
    $findSql.= " order by b.base_id desc limit {$offset},{$limit}";
    $tableData = $db->GetAll($findSql);
    $tpl = new NewTpl('myAssess/myAssessList.php',array(
        'tableData'=>$tableData,
        'page_nav'=>$page_nav,
        'pageConditionUrl'=>$searchResult['pageConditionUrl']
    ));
    $tpl->render();
    die();
}

//我的考核流程
if($_REQUEST['act']=='myAssessFlow'){

}