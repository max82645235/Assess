<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 15-6-17
 * Time: 下午1:34
 * Describe :  发起考核列表
 * Author : wmc
 */
//判断用户改脚本访问权限
function checkUserAuthority(){
    return true;
}
require_once BATH_PATH.'source/Dao/AssessDao.php';
require_once BATH_PATH.'source/Dao/AssessFlowDao.php';
require_once BATH_PATH.'source/Util/Auth.php';

$_REQUEST['act'] = (!isset($_REQUEST['act']))?'launchList':$_REQUEST['act'];

if($_REQUEST['act']=='launchList'){
    $assessDao = new AssessDao();
    $table = 'sa_assess_base';
    $_REQUEST['base_status'] = (!isset($_REQUEST['base_status']))?'0':$_REQUEST['base_status']; //状态初始默认为0  待发布状态
    $_REQUEST['byme_status'] = (!isset($_REQUEST['byme_status']))?'1':$_REQUEST['byme_status']; //状态初始默认为1 由我发起
    $searchResult = $assessDao->getBaseSearchHandlerList($table,$_REQUEST);
    $pageurl = '?m='.$m.'&a='.$a.$searchResult['pageConditionUrl'];
    $where = $searchResult['sqlWhere'];
    //获取分页page_nav
    $sql = "select count(*) from  $table where 1=1 ".$where;
    $count = $db->GetOne($sql);
    $page = isset($_GET['pn']) ? (int)$_GET['pn'] : 1;
    $limit = 10;
    $offset = ($page-1)*$limit;
    $page_nav = page($count,$limit,$page,$pageurl);
    //获取表格查询结果
    $sql = " select base_id,base_name,assess_period_type,base_start_date,base_end_date,base_status,publish_date,userId  from  {$table} where 1=1 {$where}  order  by base_id desc limit {$offset},{$limit}";
    $tableData = $db->GetAll($sql);

    //建立权限验证类
    $auth = new Auth();
    $auth->addAuthItem('launchAssess',array('m'=>$m,'a'=>$a,'act'=>'launchAssess'));
    $auth->addAuthItem('cloneAssess',array('m'=>$m,'a'=>$a,'act'=>'cloneAssess'));
    $auth->addAuthItem('publishAssess',array('m'=>$m,'a'=>$a,'act'=>'publishAssess'));
    $auth->addAuthItem('hrViewPublish',array('m'=>$m,'a'=>$a,'act'=>'hrViewStaffList'));

    $tpl = new NewTpl('assessment/launchList.php',array(
        'tableData'=>$tableData,
        'page_nav'=>$page_nav,
        'pageConditionUrl'=>$searchResult['pageConditionUrl'],
        'bus_parent_list'=>$assessDao->getBusParentDropList(),
        'userId'=>getUserId(),
        'auth'=>$auth
    ));
    $tpl->render();
    die();
}

//克隆考核
if($_REQUEST['act']=='cloneAssess'){
    $assessDao = new AssessDao();
        $auth =  new Auth();
        $auth->addAuthItem('cloneAssess',array('m'=>$m,'a'=>$a,'act'=>"cloneAssess"));
    //按钮单条方式
    if(isset($_REQUEST['base_id'])){
        $baseId = intval($_REQUEST['base_id']);
        $assessDao->copyAssessRecord($baseId,$auth);
        $jumpUrl = P_SYSPATH."index.php?".$assessDao->getConditionParamUrl(array('base_id','act'));
        alertMsg('克隆成功',$jumpUrl);
        die();
    }

    //ajax批量方式
    if(isset($_REQUEST['copyItemList'])){
        $baseIdList = array();
        $retData = array();
        $baseIdList = $_REQUEST['copyItemList'];
        try{
            foreach($baseIdList as $baseId){
                $assessDao->copyAssessRecord($baseId,$auth);
            }
            $retData['status'] = 'success';
        }catch (Exception $e){
            throw new Exception('500');
        }
        echo json_encode($retData);
        die();
    }
}

//发布考核
if($_REQUEST['act']=='publishAssess'){
    $assessDao = new AssessDao();
    $auth =  new Auth();
    $auth->addAuthItem('publishAssess',array('m'=>$m,'a'=>$a,'act'=>"publishAssess"));
    //按钮单条方式
    if(isset($_REQUEST['base_id'])){
        $baseId = intval($_REQUEST['base_id']);
        if($assessDao->setAssessPublishStatus($baseId,$auth)){
            $jumpUrl = P_SYSPATH."index.php?".$assessDao->getConditionParamUrl(array('base_id','act'));
            alertMsg('发布成功',$jumpUrl);
            die();
        }
    }

    //ajax批量方式

    if(isset($_REQUEST['selectedItemList'])){
        $baseIdList = array();
        $retData = array();
        $baseIdList = $_REQUEST['selectedItemList'];
        try{
            foreach($baseIdList as $baseId){
                $assessDao->setAssessPublishStatus($baseId,$auth);
            }
            $retData['status'] = 'success';
        }catch (Exception $e){
            throw new Exception('500');
        }
        echo json_encode($retData);
        die();
    }

}

//Hr查看考核成员列表
if($_REQUEST['act']=='hrViewStaffList'){
    $assessDao = new AssessDao();
    $base_Id = $_REQUEST['base_id'];
    $assessBaseRecord = $assessDao->getAssessBaseRecord($base_Id);
    $resultList = $assessDao->getStaffListForHrSql($_REQUEST);
    $pageurl = '?m='.$m.'&a='.$a.$resultList['pageConditionUrl'];
    $getStaffSql = $resultList['staffListSql'];
    $countSql = " count(a.*)";
    $countSql = str_replace('[*]',$countSql,$getStaffSql);
    $count = $db->GetOne($countSql);
    $page = isset($_GET['pn']) ? (int)$_GET['pn'] : 1;
    $limit = 10;
    $offset = ($page-1)*$limit;
    $page_nav = page($count,$limit,$page,$pageurl);
    //获取表格查询结果
    $findSql = " a.*,b.user_assess_status,b.base_id,b.score";
    $findSql = str_replace('[*]',$findSql,$getStaffSql);
    $tableData = $db->GetAll($findSql);
    $tpl = new NewTpl('assessment/hrViewStaffList.php',array(
        'tableData'=>$tableData,
        'page_nav'=>$page_nav,
        'pageConditionUrl'=>$resultList['pageConditionUrl'],
        'assessBaseRecord'=>$assessBaseRecord
    ));
    $tpl->render();
    die();
}

//hr查看具体成员考核进程
if($_REQUEST['act']=='hrViewStaffDetail'){
    $assessDao = new AssessDao();
    $assessFlowDao = new AssessFlowDao();
    $base_id = $_REQUEST['base_id'];
    $userId = $_REQUEST['userId'];
    $record_info = $assessFlowDao->getUserAssessRecord($base_id,$userId);

    $record_info['base'] = $assessDao->getAssessBaseRecord($base_id);
    require_once BATH_PATH."source/Widget/AssessAttrWidget.php";
    $assessAttrWidget = new AssessAttrWidget(new NewTpl());
    $tpl = new NewTpl('assessment/viewStaffDetail.php',array(
        'record_info'=>$record_info,
        'assessAttrWidget'=>$assessAttrWidget,
        'conditionUrl'=>$assessFlowDao->getConditionParamUrl(array('a','m','act','userId'))
    ));
    $tpl->render();
    die();
}

