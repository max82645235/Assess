<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 15-6-17
 * Time: ����1:34
 * Describe :  ���𿼺��б�
 * Author : wmc
 */
//�ж��û��Ľű�����Ȩ��
function checkUserAuthority(){
    return true;
}
require_once BATH_PATH.'source/Dao/AssessDao.php';

$_REQUEST['act'] = (!isset($_REQUEST['act']))?'launchList':$_REQUEST['act'];

if($_REQUEST['act']=='launchList'){

    $assessDao = new AssessDao();
    $table = 'sa_assess_base';
    $searchResult = $assessDao->getHrSearchHandlerList($table);
    $pageurl = '?m='.$m.'&a='.$a.$searchResult['pageConditionUrl'];
    //��ȡ��ҳpage_nav
    $sql = "select count(*) from  $table where 1=1 ".$searchResult['sqlWhere'];
    $count = $db->GetOne($sql);
    $page = isset($_GET['pn']) ? (int)$_GET['pn'] : 1;
    $limit = 10;
    $offset = ($page-1)*$limit;
    $page_nav = page($count,$limit,$page,$pageurl);

    //��ȡ����ѯ���
    $sql = " select base_id,base_name,assess_period_type,base_start_date,base_end_date,base_status,publish_date  from  {$table} where 1=1 {$searchResult['sqlWhere']}  order  by base_id desc limit {$offset},{$limit}";
    $tableData = $db->GetAll($sql);
    $tpl = new NewTpl('assessment/launchList.php',array(
        'tableData'=>$tableData,
        'page_nav'=>$page_nav,
        'pageConditionUrl'=>$searchResult['pageConditionUrl'],
        'cfg'=>$cfg
    ));

    $tpl->render();
    die();
}