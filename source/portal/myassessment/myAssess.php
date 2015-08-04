<?php
/**
 * Created by PhpStorm.
 * User: pc
 * Date: 15-6-22
 * Time: 下午3:52
 */
require_once BATH_PATH.'source/Dao/AssessDao.php';
require_once BATH_PATH.'source/Dao/AssessFlowDao.php';
require_once BATH_PATH.'source/Util/btnValid/StaffValid.php';
$_REQUEST['act'] = (!isset($_REQUEST['act']))?'myAssessList':$_REQUEST['act'];
$filterActs = array('triggerStatusUpdate');
checkUserAuthority($filterActs);//验证act请求权限
//我的考核列表页
if($_REQUEST['act']=='myAssessList'){
    $assessDao = new AssessDao();
    $assessFlowDao = new AssessFlowDao();
    $userId = getUserId();
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
    $findSql = " a.user_assess_status,a.score,a.userId as user_Id,a.rejectStatus,b.*";
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
    $assessDao = new AssessDao();
    $assessFlowDao = new AssessFlowDao();
    $userId = getUserId();
    $base_id = $_REQUEST['base_id'];
    $mValid = new StaffValid($base_id,$userId);
    if(isset($_REQUEST['status']) && in_array($_REQUEST['status'],array('save','next'))){
        $attrRecord = array();
        $attrRecordType = array_flip(AssessDao::$AttrRecordTypeMaps);
        $attrTypeList = array();
        foreach($_REQUEST['attrData']['fromData']['handlerData'] as $key=>$data){
            $tmp = array();
            $tmp['base_id'] = $base_id;
            $tmp['weight'] = (isset($data['weight']))?$data['weight']:'';
            $tmp['userId'] = $userId;
            $attrTypeList[] = $tmp['attr_type'] = $attrRecordType[$key];
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
                //对于被驳回的状态,修改状态
                if($userRelationRecord['rejectStatus']==1)
                    $userRelationRecord['rejectStatus'] = 2;
            }
            if($userRelationRecord['user_assess_status']==AssessFlowDao::AssessRealLeadView){//当转给领导终审时，需要生成历史记录
                $historyData = $assessFlowDao->getUserAssessRecord($base_id,$userId);
                if($_REQUEST['rpItem']){
                    $userRelationRecord['rpData'] = serialize($assessFlowDao->formatRpItem($_REQUEST['rpItem']));
                }

                //附件上传保存
                if($_REQUEST['plupFileList']){
                    $assessDao->plugFileSave($_REQUEST['plupFileList'],$userRelationRecord['rid']);
                }
            }
            $assessItemRecord = $assessFlowDao->getUserAssessItemRecord($base_id,$userId);
            $delStatus = (count($assessItemRecord)!=count($attrTypeList))?true:false; //当发现提交过来的数量和原item表条数不一致时需要删除item数据
            $assessDao->triggerUserNewAttrTypeUpdate($userRelationRecord,$delStatus);//更新user_relation表 assess_attr_type状态
            $assessDao->setAssessUserItemRecord($uids,$attrRecord); //更新user_item表
            $assessDao->triggerUserItemHistoryWrite($historyData,$assessFlowDao);//当$historyData存在时需要触发对比逻辑，写入前后差异到更新user_relation表 diffData字段
        }catch (Exception $e){
            throw new Exception('500');
        }
        echo json_encode(array('status'=>'success'));
        die();
    }else{
        $record_info = $assessFlowDao->getUserAssessRecord($base_id,$userId);
        $assessFlowDao->validStuffSetFow($record_info['relation']['user_assess_status']);
        if($record_info['relation']['user_assess_status']==AssessFlowDao::AssessPreReport){
            $record_info['plupFileList'] = $assessFlowDao->getPlugFileList($record_info['relation']['rid']);
        }
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


//查看具体考核进程
if($_REQUEST['act']=='staffViewStaffDetail'){
    $assessDao = new AssessDao();
    $assessFlowDao = new AssessFlowDao();
    $base_id = $_REQUEST['base_id'];
    $userId = getUserId();
    $record_info = $assessFlowDao->getUserAssessRecord($base_id,$userId);

    $record_info['base'] = $assessDao->getAssessBaseRecord($base_id);
    $record_info['plupFileList'] = $assessFlowDao->getPlugFileList($record_info['relation']['rid']);
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

//更改到提报时
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