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
require_once BATH_PATH.'source/Dao/AssessDao.php';
require_once BATH_PATH.'source/Dao/AssessFlowDao.php';
require_once BATH_PATH.'source/Util/Auth.php';
$_REQUEST['act'] = (!isset($_REQUEST['act']))?'launchList':$_REQUEST['act'];
checkUserAuthority();//��֤act����Ȩ��
if($_REQUEST['act']=='launchList'){
    $assessDao = new AssessDao();
    $table = 'sa_assess_base';
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

//��¡����
if($_REQUEST['act']=='cloneAssess'){
    $assessDao = new AssessDao();
        $auth =  new Auth();
        $auth->addAuthItem('cloneAssess',array('m'=>$m,'a'=>$a,'act'=>"cloneAssess"));
    //��ť������ʽ
    if(isset($_REQUEST['base_id'])){
        $baseId = intval($_REQUEST['base_id']);
        $assessDao->copyAssessRecord($baseId,$auth);
        $jumpUrl = P_SYSPATH."index.php?".$assessDao->getConditionParamUrl(array('base_id','act'));
        alertMsg('���Ƴɹ�',$jumpUrl);
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
    $auth =  new Auth();
    $auth->addAuthItem('publishAssess',array('m'=>$m,'a'=>$a,'act'=>"publishAssess"));
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

//Hr�鿴���˳�Ա�б�
if($_REQUEST['act']=='hrViewStaffList'){
    $assessDao = new AssessDao();
    $base_Id = $_REQUEST['base_id'];
    $assessBaseRecord = $assessDao->getAssessBaseRecord($base_Id);
    $resultList = $assessDao->getStaffListForHrSql($_REQUEST);
    $pageurl = '?m='.$m.'&a='.$a.'&'.$assessDao->getConditionParamUrl(array('m','a'));
    $getStaffSql = $resultList['staffListSql'];
    $countSql = " count(*)";
    $countSql = str_replace('[*]',$countSql,$getStaffSql);
    $count = $db->GetOne($countSql);
    $page = isset($_GET['pn']) ? (int)$_GET['pn'] : 1;
    $limit = 10;
    $offset = ($page-1)*$limit;
    $page_nav = page($count,$limit,$page,$pageurl);
    //��ȡ����ѯ���
    $findSql = " a.*,b.user_assess_status,b.base_id,b.score";
    $findSql = str_replace('[*]',$findSql,$getStaffSql);
    $findSql.= " limit {$offset},{$limit}";
    if($_GET['test']){
        echo $findSql;
    }
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

//hr�鿴�����Ա���˽���
if($_REQUEST['act']=='hrViewStaffDetail'){
    $assessDao = new AssessDao();
    $assessFlowDao = new AssessFlowDao();
    $auth = new Auth();
    $auth->addAuthItem('hrAssessReject',array('m'=>$m,'a'=>$a,'act'=>'hrAssessReject'));
    $base_id = $_REQUEST['base_id'];
    $userId = $_REQUEST['userId'];
    $record_info = $assessFlowDao->getUserAssessRecord($base_id,$userId);

    $record_info['base'] = $assessDao->getAssessBaseRecord($base_id);
    require_once BATH_PATH."source/Widget/AssessAttrWidget.php";
    $assessAttrWidget = new AssessAttrWidget(new NewTpl());
    $tpl = new NewTpl('assessment/viewStaffDetail.php',array(
        'auth'=>$auth,
        'record_info'=>$record_info,
        'assessAttrWidget'=>$assessAttrWidget,
        'conditionUrl'=>$assessFlowDao->getConditionParamUrl(array('a','m','act','userId'))
    ));
    $tpl->render();
    die();
}

//hr���������ɵĿ���
if($_REQUEST['act']=='hrAssessReject'){
    $base_id = $_REQUEST['base_id'];
    $userId = $_REQUEST['userId'];
    $assessDao = new AssessDao();
    $assessFlowDao = new AssessFlowDao();
    $auth = new Auth();
    $auth->addAuthItem('hrAssessReject',array('m'=>$m,'a'=>$a,'act'=>'hrAssessReject'));
    $record_info = $assessFlowDao->getUserRelationRecord($base_id,$userId);
    $result = array();
    if($record_info && $record_info['user_assess_status']>=AssessFlowDao::AssessRealSuccess){
        unset($record_info['username']);
        $record_info['user_assess_status'] = AssessFlowDao::AssessRealLeadView;
        $record_info['rejectText'] = iconv('UTF-8','GBK//IGNORE',$_REQUEST['reject']);
        $record_info['rejectStatus'] = 2;
        $assessDao->triggerUserNewAttrTypeUpdate($record_info,false);
        $result['status'] = 'success';
    }
    echo json_encode($result);
    die();
}

