<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 15-9-7
 * Time: ����5:14
 */
$_REQUEST['act'] = (!isset($_REQUEST['act']))?'freeFlowList':$_REQUEST['act'];
checkUserAuthority();//��֤act����Ȩ��
require_once BATH_PATH.'source/Dao/AssessDao.php';
require_once BATH_PATH.'source/Dao/AssessFlowDao.php';
//�������б�
if($_REQUEST['act']=='freeFlowList'){
    global $db;
    require_once BATH_PATH.'source/Dao/FreeFlowDao.php';
    $searchResult = FreeFlowDao::getAllFreeFlowList($_REQUEST);
    $pageurl = '?m='.$m.'&a='.$a.$searchResult['pageConditionUrl'];
    $sql = $searchResult['sql'];
    //��ȡ��ҳpage_nav
    $count = count($db->GetAll($sql));
    $page = isset($_GET['pn']) ? (int)$_GET['pn'] : 1;
    $limit = 10;
    $offset = ($page-1)*$limit;
    $page_nav = page($count,$limit,$page,$pageurl);
    //��ȡ����ѯ���
    $tableData = $db->GetAll($sql);
    $tpl = new NewTpl('myAssess/freeFlowList.php',array(
        'tableData'=>$tableData,
        'page_nav'=>$page_nav,
        'pageConditionUrl'=>$searchResult['pageConditionUrl']
    ));
    $tpl->render();
    die();
}
