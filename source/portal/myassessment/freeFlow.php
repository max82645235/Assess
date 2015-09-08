<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 15-9-7
 * Time: 下午5:14
 */
$_REQUEST['act'] = (!isset($_REQUEST['act']))?'freeFlowList':$_REQUEST['act'];
checkUserAuthority();//验证act请求权限
require_once BATH_PATH.'source/Dao/AssessDao.php';
require_once BATH_PATH.'source/Dao/AssessFlowDao.php';
//自由流列表
if($_REQUEST['act']=='freeFlowList'){
    global $db;
    require_once BATH_PATH.'source/Dao/FreeFlowDao.php';
    $searchResult = FreeFlowDao::getAllFreeFlowList($_REQUEST);
    $pageurl = '?m='.$m.'&a='.$a.$searchResult['pageConditionUrl'];
    $sql = $searchResult['sql'];
    //获取分页page_nav
    $count = count($db->GetAll($sql));
    $page = isset($_GET['pn']) ? (int)$_GET['pn'] : 1;
    $limit = 10;
    $offset = ($page-1)*$limit;
    $page_nav = page($count,$limit,$page,$pageurl);
    //获取表格查询结果
    $tableData = $db->GetAll($sql);
    $tpl = new NewTpl('myAssess/freeFlowList.php',array(
        'tableData'=>$tableData,
        'page_nav'=>$page_nav,
        'pageConditionUrl'=>$searchResult['pageConditionUrl']
    ));
    $tpl->render();
    die();
}
