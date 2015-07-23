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
require_once BATH_PATH.'source/Util/btnValid/LeaderValid.php';
$_REQUEST['act'] = (!isset($_REQUEST['act']))?'waitMeList':$_REQUEST['act'];
checkUserAuthority();//验证act请求权限
//待我审核列表页
if($_REQUEST['act']=='waitMeList'){
    $assessDao = new AssessDao();
    $assessFlowDao = new AssessFlowDao();
    $assessFlowDao->setAssessDao($assessDao);
    $_REQUEST['status'] = (!isset($_REQUEST['status']) ||$_REQUEST['status']===0)?1:$_REQUEST['status'];//下属状态
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
    $_REQUEST['status'] = (!isset($_REQUEST['status']))?1:$_REQUEST['status'];//下属状态
    $resultList = $assessFlowDao->getStaffListForLeaderSql($_REQUEST);
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
    $findSql = " a.*,b.user_assess_status,b.base_id,b.score,b.rejectStatus,b.rpData";
    $findSql = str_replace('[*]',$findSql,$getStaffSql);
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


if($_REQUEST['act']=='leaderSetFlow'){
    $userId = $_REQUEST['userId'];
    $base_id = $_REQUEST['base_id'];
    $assessDao = new AssessDao();
    $assessFlowDao = new AssessFlowDao();
    $mValid = new LeaderValid($base_id,$userId);
    if(isset($_REQUEST['status']) && in_array($_REQUEST['status'],array('save','next','back','start'))){
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
            $userRelationRecord = $assessDao->getUserRelationRecord($userId,$base_id);
            //校验考核状态
            if($assessFlowDao->validLeaderSetFlow($userRelationRecord['user_assess_status'])){
                //考核类型更改
                if($userRelationRecord){
                    $delStatus = $_REQUEST['attrData']['fromData']['type']!=$userRelationRecord['assess_attr_type'];
                    $userRelationRecord['assess_attr_type'] = $_REQUEST['attrData']['fromData']['type'];
                    if($_REQUEST['status']=='next'){
                        $userRelationRecord['user_assess_status'] = $userRelationRecord['user_assess_status']+1;
                        $userRelationRecord['rejectText'] = '';
                        $userRelationRecord['rejectStatus'] = 0;
                        //当为领导终审通过时,需要计算得分写入assess_user_relation表score字段
                        if($userRelationRecord['user_assess_status'] == AssessFlowDao::AssessRealSuccess){
                            $userRelationRecord['score'] = $assessFlowDao->getUserAssessScore($attrRecord);
                        }
                    }elseif($_REQUEST['status']=='back'){
                        $userRelationRecord['user_assess_status'] = $userRelationRecord['user_assess_status']-1;
                        $userRelationRecord['rejectText'] = iconv('UTF-8','GBK//IGNORE',$_REQUEST['reject']);
                        $userRelationRecord['rejectStatus'] = 1;
                    }elseif($_REQUEST['status']=='start'){
                        $userRelationRecord['user_assess_status'] = AssessFlowDao::AssessChecking;
                        $userRelationRecord['rejectText'] = '';
                        $userRelationRecord['rejectStatus'] = 0;
                    }

                    //奖惩
                    if($_REQUEST['rpItem']){
                        $userRelationRecord['rpData'] = serialize($assessFlowDao->formatRpItem($_REQUEST['rpItem']));
                    }

                    $assessDao->triggerUserNewAttrTypeUpdate($userRelationRecord,$delStatus);
                }
                $assessDao->setAssessUserItemRecord($uids,$attrRecord);
                //校验该考核下所有考核人状态，如果都已经设置为考核中（3） 需要变更base主表状态
                if($_REQUEST['status']=='start'){
                    $assessDao->checkAssessAllUserCheckingStatus($base_id);
                }

                if($userRelationRecord['user_assess_status']==AssessFlowDao::AssessRealSuccess){
                    $assessDao->checkAssessAllUserSuccessStatus($base_id);
                }

                //附件上传保存
                if($_REQUEST['plupFileList']){
                    $assessDao->plugFileSave($_REQUEST['plupFileList'],$userRelationRecord['rid']);
                }
            }
        }catch (Exception $e){
            throw new Exception('500');
        }
        echo json_encode(array('status'=>'success'));
        die();
    }else{
        $record_info = $assessFlowDao->getUserAssessRecord($base_id,$userId);
        $assessFlowDao->validLeaderSetFlow($record_info['relation']['user_assess_status']);
        if($record_info['relation']['user_assess_status']==AssessFlowDao::AssessRealLeadView){
            $record_info['plupFileList'] = $assessFlowDao->getPlugFileList($record_info['relation']['rid']);
        }
        require_once BATH_PATH."source/Widget/AssessAttrWidget.php";
        $assessAttrWidget = new AssessAttrWidget(new NewTpl());
    }



    $tpl = new NewTpl('waitMeAssess/leaderSetFlow.php',array(
        'record_info'=>$record_info,
        'assessAttrWidget'=>$assessAttrWidget,
        'conditionUrl'=>$assessDao->getConditionParamUrl(array('a','m','act','userId')),
        'mValid'=>$mValid
    ));
    $tpl->render();
    die();
}

//多考核项员工自设
if($_REQUEST['act']=='mulAssessDiySet'){
    $jp = false;
    if(!is_array($_REQUEST['diyItemList'])){
        $jp = true;
        $_REQUEST['diyItemList'] = array($_REQUEST['base_id']);
    }
    $baseList = $_REQUEST['diyItemList'];
    $assessFlowDao = new AssessFlowDao();
    $ret = array();
    if(count($baseList)){
        foreach($baseList as $base_id){
            if($assessFlowDao->changeCreateToStaff($base_id)){
                $ret['status'] = 'success';
            }
        }
    }
    if($jp ==true){
        $conditionUrl = $assessFlowDao->getConditionParamUrl(array('act'));
        $location = P_SYSPATH."index.php?act=waitMeList&$conditionUrl";
        echo "<script>location.href='{$location}';alert('设置成功');</script>";
    }else{
        echo json_encode($ret);
        die();
    }
}


//单考核项员工自设
if($_REQUEST['act']=='singleAssessDiySet'){
    $userList = implode(',',$_REQUEST['diyItemList']);
    $base_id = $_REQUEST['base_id'];
    $assessFlowDao = new AssessFlowDao();
    $ret = array();
    if($userList && $base_id){
        if($assessFlowDao->changeCreateToStaff($base_id,$userList)){
            $ret['status'] = 'success';
        }
    }
    echo json_encode($ret);
    die();
}

//查看流程
if($_REQUEST['act']=='viewFlow'){
    $userId = $_REQUEST['userId'];
    $base_id = $_REQUEST['base_id'];
    $record_info = $assessFlowDao->getUserAssessRecord($base_id,$userId);
    $assessAttrWidget = new AssessAttrWidget(new NewTpl());
    $tpl = new NewTpl('waitMeAssess/viewFlow.php',array(
        'record_info'=>$record_info,
        'assessAttrWidget'=>$assessAttrWidget
    ));
}

//状态变更
if($_REQUEST['act']=='changeCheckingStatus'){
    $userId = $_REQUEST['userId'];
    $baseId = $_REQUEST['base_id'];
    $assessFlowDao = new AssessFlowDao();
    $assessFlowDao->changeCheckingStatus($userId,$baseId);
    die();
}

//hr查看具体成员考核进程
if($_REQUEST['act']=='leadViewStaffDetail'){
    $assessDao = new AssessDao();
    $assessFlowDao = new AssessFlowDao();
    $base_id = $_REQUEST['base_id'];
    $userId = $_REQUEST['userId'];
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

//批量复制待我创建
if($_REQUEST['act']=='mulCopyCreateAssess'){
    $assessDao = new AssessDao();
    $assessFlowDao = new AssessFlowDao();
    if(isset($_REQUEST['ajax']) && $_REQUEST['ajax']==1){
        $base_id = $_REQUEST['base_id'];
        $userId = $_REQUEST['userId'];
        $record_info = $assessFlowDao->getUserAssessRecord($base_id,$userId);
        $newUids = $_REQUEST['newUids'];
        try{
            if($record_info){
                $baseRecord =  array(
                    'base_id'=>$base_id,
                    'assess_attr_type'=>$record_info['relation']['assess_attr_type']
                );
                $assessDao->clearDeleteUserItem($base_id,$newUids,false);
                $assessDao->setAssessUserRelation($newUids,$baseRecord);//考核用户关系表设置
                $assessDao->setAssessUserItemRecord($newUids,$record_info['item']);
            }
        }catch (Exception $e){
            throw new Exception('500');
        }
        echo json_encode(array('status'=>'success'));
        die();
    }
    $userList = $assessFlowDao->getCreatingUserList($_REQUEST);
    $tpl = new NewTpl('waitMeAssess/mulCopyCreateAssess.php',array(
        'userList'=>$userList,
        'conditionUrl'=>$assessFlowDao->getConditionParamUrl(array('a','m','act','userId'))
    ));
    $tpl->render();
    die();
}