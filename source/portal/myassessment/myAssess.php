<?php
/**
 * Created by PhpStorm.
 * User: pc
 * Date: 15-6-22
 * Time: ����3:52
 */
require_once BATH_PATH.'source/Dao/AssessDao.php';
require_once BATH_PATH.'source/Dao/AssessFlowDao.php';
require_once BATH_PATH.'source/Util/btnValid/StaffValid.php';
$_REQUEST['act'] = (!isset($_REQUEST['act']))?'myAssessList':$_REQUEST['act'];
//�ҵĿ����б�ҳ
if($_REQUEST['act']=='myAssessList'){
    $assessDao = new AssessDao();
    $assessFlowDao = new AssessFlowDao();
    $userId = getUserId();
    if(!isset($_REQUEST['base_status']) || !array_key_exists($_REQUEST['base_status'],AssessFlowDao::$UserAssessStatusByStaff)){
        $_REQUEST['base_status'] = 1;//Ĭ��Ϊ �Ҵ���д�ƻ�״̬
    }
    $searchResult = $assessFlowDao->getMyAssessSearchHandlerListSql($userId,$_REQUEST);
    $pageurl = '?m='.$m.'&a='.$a.$searchResult['pageConditionUrl'];
    $sql = $searchResult['sql'];
    //��ȡ��ҳpage_nav
    $countSql = " count(a.*)";
    $countSql = str_replace('[*]',$countSql,$sql);
    $count = $db->GetOne($sql);
    $page = isset($_GET['pn']) ? (int)$_GET['pn'] : 1;
    $limit = 10;
    $offset = ($page-1)*$limit;
    $page_nav = page($count,$limit,$page,$pageurl);
    //��ȡ����ѯ���
    $findSql = " a.user_assess_status,a.score,a.userId as user_Id,b.*";
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

//�ҵĿ�������
if($_REQUEST['act']=='myAssessFlow'){
    $assessDao = new AssessDao();
    $assessFlowDao = new AssessFlowDao();
    $userId = getUserId();
    $base_id = $_REQUEST['base_id'];
    $mValid = new StaffValid($base_id,$userId);
    if(isset($_REQUEST['status']) && in_array($_REQUEST['status'],array('save','next'))){
        $attrRecord = array();
        $attrRecordType = array_flip(AssessDao::$AttrRecordTypeMaps);
        foreach($_REQUEST['attrData']['fromData']['handlerData'] as $key=>$data){
            $tmp = array();
            $tmp['base_id'] = $base_id;
            $tmp['weight'] = (isset($data['weight']))?$data['weight']:'';
            $tmp['userId'] = $userId;
            $tmp['attr_type'] = $attrRecordType[$key];
            $tmp['cash'] = isset($data['cash'])?$data['cash']:'';
            if(is_array($data['table_data'])){
                foreach($data['table_data'] as $i=>$tt){
                    foreach($tt as $attr=>$v){
                        if(mb_detect_encoding($v)=='UTF-8'){
                            $data['table_data'][$i][$attr] = iconv('UTF-8','GBK//IGNORE',$v);
                        }
                    }
                }
                $data['table_data'] = serialize($data['table_data']);
            }
            $tmp['itemData'] = $data['table_data'];
            $attrRecord[] = $tmp;
        }
        $uids = array($userId);
        try{
            $historyData = array();
            $userRelationRecord = $assessDao->getUserRelationRecord($userId,$base_id);
            $userRelationRecord['assess_attr_type'] = $_REQUEST['attrData']['fromData']['type'];
            $nextStatus = $_REQUEST['status']=='next';
            if($_REQUEST['status']=='next'){
                $userRelationRecord['user_assess_status'] = $userRelationRecord['user_assess_status']+1;
            }
            if($userRelationRecord['user_assess_status']==AssessFlowDao::AssessRealLeadView){//��ת���쵼����ʱ����Ҫ������ʷ��¼
                $historyData = $assessFlowDao->getUserAssessRecord($base_id,$userId);
                if($_REQUEST['rpItem']){
                    $userRelationRecord['rpData'] = serialize($assessFlowDao->formatRpItem($_REQUEST['rpItem']));
                }
            }
            $assessDao->triggerUserNewAttrTypeUpdate($userRelationRecord,false);//����user_relation�� assess_attr_type״̬
            $assessDao->setAssessUserItemRecord($uids,$attrRecord); //����user_item��
            $assessDao->triggerUserItemHistoryWrite($historyData,$assessFlowDao);//��$historyData����ʱ��Ҫ�����Ա��߼���д��ǰ����쵽����user_relation�� diffData�ֶ�
        }catch (Exception $e){
            throw new Exception('500');
        }
        echo json_encode(array('status'=>'success'));
        die();
    }else{
        $record_info = $assessFlowDao->getUserAssessRecord($base_id,$userId);
        $assessFlowDao->validStuffSetFow($record_info['relation']['user_assess_status']);
        require_once BATH_PATH."source/Widget/AssessAttrWidget.php";
        $assessAttrWidget = new AssessAttrWidget(new NewTpl());
    }

    $tpl = new NewTpl('myAssess/myAssessFlow.php',array(
        'record_info'=>$record_info,
        'assessAttrWidget'=>$assessAttrWidget,
        'conditionUrl'=>$assessDao->getConditionParamUrl(array('a','m','act')),
        'mValid'=>$mValid
    ));

    $tpl->render();
    die();
}


//hr�鿴�����Ա���˽���
if($_REQUEST['act']=='staffViewStaffDetail'){
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

if($_REQUEST['act']=='triggerStatusUpdate'){
    $assessFlowDao = new AssessFlowDao();
    $assessDao = new AssessDao();
    $userId = $_REQUEST['userId'];
    $base_id = $_REQUEST['base_id'];
    $assessFlowDao->triggerStatusUpdate($base_id,$userId);
    $assessDao->checkAssessAllUserSubbingStatus($base_id);
    $conditionUrl = $assessFlowDao->getConditionParamUrl(array('act'));
    $location = P_SYSPATH."index.php?act=myAssessList&$conditionUrl";
    header("Location: $location");

}