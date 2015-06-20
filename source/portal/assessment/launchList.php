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
    $authLaunch = new Auth($m,'launchAssess','launchAssess');
    $authClone = new Auth($m,$a,'cloneAssess');
    $authPublish = new Auth($m,$a,'publishAssess');
    $authList = array(
        'authLaunch'=>$authLaunch,
        'authClone'=>$authClone,
        'authPublish'=>$authPublish
    );

    $tpl = new NewTpl('assessment/launchList.php',array(
        'tableData'=>$tableData,
        'page_nav'=>$page_nav,
        'pageConditionUrl'=>$searchResult['pageConditionUrl'],
        'bus_parent_list'=>$assessDao->getBusParentDropList(),
        'userId'=>getUserId(),
        'authList'=>$authList
    ));
    $tpl->render();
    die();
}

//克隆考核
if($_REQUEST['act']=='cloneAssess'){
    $assessDao = new AssessDao();
    $auth = new Auth($m,$a,'cloneAssess');
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
    $auth = new Auth($m,$a,'publishAssess');
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



