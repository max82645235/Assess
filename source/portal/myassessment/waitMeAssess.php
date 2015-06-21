<?php
/**
 * 待我审核
 * Created by PhpStorm.
 * User: Administrator
 * Date: 15-6-19
* Time: 下午4:57
*/
require_once BATH_PATH.'source/Dao/AssessDao.php';
require_once BATH_PATH.'source/Dao/AssessFlowDao.php';
$_REQUEST['act'] = (!isset($_REQUEST['act']))?'waitMeList':$_REQUEST['act'];

//待我审核列表页
if($_REQUEST['act']=='waitMeList'){
    $assessDao = new AssessDao();
    $assessFlowDao = new AssessFlowDao();
    $assessFlowDao->setAssessDao($assessDao);
    $_REQUEST['base_status'] = (!isset($_REQUEST['base_status']))?'1':$_REQUEST['base_status']; //状态初始默认为0  待发布状态
    $_REQUEST['status'] = (!isset($_REQUEST['status']))?1:$_REQUEST['status'];
    $searchResult = $assessFlowDao->waitMeSearchHandlerList($_REQUEST);
    $pageurl = '?m='.$m.'&a='.$a.$searchResult['pageConditionUrl'];
    $where = $searchResult['sqlWhere'];
    //获取分页page_nav
    $sql = "select count(*) from sa_assess_base where 1=1 ".$where;
    $count = $db->GetOne($sql);
    $page = isset($_GET['pn']) ? (int)$_GET['pn'] : 1;
    $limit = 10;
    $offset = ($page-1)*$limit;
    $page_nav = page($count,$limit,$page,$pageurl);
    //获取表格查询结果
    $sql = " select base_id,base_name,assess_period_type,base_start_date,base_end_date,base_status,publish_date,userId  from  sa_assess_base where 1=1 {$where}  order  by base_id desc limit {$offset},{$limit}";
    //echo $sql;
    $tableData = $db->GetAll($sql);
    $tpl = new NewTpl('waitMeAssess/waitMeList.php',array(
        'tableData'=>$tableData,
        'page_nav'=>$page_nav,
        'pageConditionUrl'=>$searchResult['pageConditionUrl'],
        'bus_parent_list'=>$assessDao->getBusParentDropList(),
    ));
    $tpl->render();
    die();
}

//考核员工列表
if($_REQUEST['act']=='myStaffList'){
    $base_id = $_REQUEST['base_id'];
    $assessDao = new AssessDao();
    $assessBaseRecord = $assessDao->getAssessBaseRecord($base_id);
    $assessFlowDao = new AssessFlowDao();
    $resultList = $assessFlowDao->getStaffListForLeaderSql($_REQUEST);
    $getStaffSql = $resultList['staffListSql'];
    $countSql = " count(a.*)";
    $countSql = str_replace('[*]',$countSql,$getStaffSql);
    $count = $db->GetOne($countSql);
    $page = isset($_GET['pn']) ? (int)$_GET['pn'] : 1;
    $limit = 10;
    $offset = ($page-1)*$limit;
    $page_nav = page($count,$limit,$page,$pageurl);
    //获取表格查询结果
    $findSql = " a.*,b.user_assess_status,b.base_id";
    $findSql = str_replace('[*]',$findSql,$getStaffSql);
    //echo $findSql."<br/>";
    $tableData = $db->GetAll($findSql);
    $tpl = new NewTpl('waitMeAssess/myStaffList.php',array(
        'tableData'=>$tableData,
        'page_nav'=>$page_nav,
        'pageConditionUrl'=>$resultList['pageConditionUrl'],
        'bus_parent_list'=>$assessDao->getBusParentDropList(),
        'assessBaseRecord'=>$assessBaseRecord
    ));
    $tpl->render();
    die();
}

//查看流程
if($_REQUEST['act']=='viewFlow'){


}

if($_REQUEST['act']=='leaderSetFlow'){
    $userId = $_REQUEST['userId'];
    $base_id = $_REQUEST['base_id'];
    $assessFlowDao = new AssessFlowDao();
    if(isset($_REQUEST['formSubTag']) && $_REQUEST['formSubTag']==1 && isset($_REQUEST['subFormData'])){

    }else{
        $record_info = $assessFlowDao->getUserAssessRecord($base_id,$userId);
        require_once BATH_PATH."source/Widget/AssessAttrWidget.php";
        $assessAttrWidget = new AssessAttrWidget(new NewTpl());
    }
    $tpl = new NewTpl('waitMeAssess/leaderSetFlow.php',array(
        'record_info'=>$record_info,
        'assessAttrWidget'=>$assessAttrWidget
    ));

    $tpl->render();
    die();
}

//员工自设
if($_REQUEST['act']=='staffDiySet'){

}

