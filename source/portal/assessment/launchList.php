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
require_once BATH_PATH.'source/Util/Auth.php';

$_REQUEST['act'] = (!isset($_REQUEST['act']))?'launchList':$_REQUEST['act'];

if($_REQUEST['act']=='launchList'){
    $assessDao = new AssessDao();
    $table = 'sa_assess_base';
    $_REQUEST['base_status'] = (!isset($_REQUEST['base_status']))?'0':$_REQUEST['base_status']; //״̬��ʼĬ��Ϊ0  ������״̬
    $_REQUEST['byme_status'] = (!isset($_REQUEST['byme_status']))?'1':$_REQUEST['byme_status']; //״̬��ʼĬ��Ϊ1 ���ҷ���
    $searchResult = $assessDao->getBaseSearchHandlerList($table,$_REQUEST);
    $pageurl = '?m='.$m.'&a='.$a.$searchResult['pageConditionUrl'];
    $where = $searchResult['sqlWhere'];
    //��ȡ��ҳpage_nav
    $sql = "select count(*) from  $table where 1=1 ".$where;
    $count = $db->GetOne($sql);
    $page = isset($_GET['pn']) ? (int)$_GET['pn'] : 1;
    $limit = 10;
    $offset = ($page-1)*$limit;
    $page_nav = page($count,$limit,$page,$pageurl);
    //��ȡ����ѯ���
    $sql = " select base_id,base_name,assess_period_type,base_start_date,base_end_date,base_status,publish_date,userId  from  {$table} where 1=1 {$where}  order  by base_id desc limit {$offset},{$limit}";
    $tableData = $db->GetAll($sql);

    //����Ȩ����֤��
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

//��¡����
if($_REQUEST['act']=='cloneAssess'){
    $assessDao = new AssessDao();
    $auth = new Auth($m,$a,'cloneAssess');
    //��ť������ʽ
    if(isset($_REQUEST['base_id'])){
        $baseId = intval($_REQUEST['base_id']);
        $assessDao->copyAssessRecord($baseId,$auth);
        $jumpUrl = P_SYSPATH."index.php?".$assessDao->getConditionParamUrl(array('base_id','act'));
        alertMsg('��¡�ɹ�',$jumpUrl);
        die();
    }

    //ajax������ʽ
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

//��������
if($_REQUEST['act']=='publishAssess'){
    $assessDao = new AssessDao();
    $auth = new Auth($m,$a,'publishAssess');
    //��ť������ʽ
    if(isset($_REQUEST['base_id'])){
        $baseId = intval($_REQUEST['base_id']);
        if($assessDao->setAssessPublishStatus($baseId,$auth)){
            $jumpUrl = P_SYSPATH."index.php?".$assessDao->getConditionParamUrl(array('base_id','act'));
            alertMsg('�����ɹ�',$jumpUrl);
            die();
        }
    }

    //ajax������ʽ
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



