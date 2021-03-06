<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 15-6-8
 * Time: 下午2:11
 *  Describe :  发起考核，填写考核基本信息，考核类型事项信息等
 * Author : wmc
 */
//判断用户改脚本访问权限
require_once BATH_PATH.'source/Dao/AssessDao.php';
require_once BATH_PATH.'source/Util/btnValid/HrValid.php';
$_REQUEST['act'] = (!isset($_REQUEST['act']))?'launchAssess':$_REQUEST['act'];
$filterActs = array('autoUserName','selectUserList');
checkUserAuthority($filterActs);//验证act请求权限
if($_REQUEST['act']=='launchAssess'){
    $base_id = getgpc('base_id'); //考核表主键
    $user_id = getUserId();//当前用户身份Id
    $assessDao = new AssessDao();
    $mValid = new HrValid($base_id);
    //ajax表单提交
    if(isset($_REQUEST['formSubTag']) && $_REQUEST['formSubTag']==1 && isset($_REQUEST['subFormData'])){
        if(isset($_REQUEST['subFormData']['baseData'])){
            //assess_base主表保存
            if(mb_detect_encoding($_REQUEST['subFormData']['baseData']['base_name'])=='UTF-8'){
                $_REQUEST['subFormData']['baseData']['base_name'] = iconv('UTF-8','GBK//IGNORE',$_REQUEST['subFormData']['baseData']['base_name']);
            }
            if($base_id = $assessDao->setAssessBaseRecord($_REQUEST['subFormData']['baseData'])){
                $uids = explode(',',$_REQUEST['subFormData']['baseData']['uids']);
                $baseRecord = $assessDao->getAssessBaseRecord($base_id);
                $assessDao->setAssessUserRelation($uids,$baseRecord);//考核用户关系表设置
                $clearTag = $assessDao->clearDeleteUser($base_id,$uids); //清除删除的用户
                //如果不勾选领导设置时
                if(isset($_REQUEST['subFormData']['attrData']) && $_REQUEST['subFormData']['baseData']['lead_direct_set_status']==0){
                    $attrRecord = array();
                    $attrRecordType = array_flip(AssessDao::$AttrRecordTypeMaps);
                    foreach($_REQUEST['subFormData']['attrData']['fromData']['handlerData'] as $key=>$data){
                        $tmp = array();
                        $tmp['base_id'] = $base_id;
                        $tmp['attr_type'] = $attrRecordType[$key];
                        $tmp['cash'] = isset($data['cash'])?$data['cash']:'';
                        $tmp['itemData'] = $data['table_data'];
                        $attrRecord[$key] = $tmp;
                    }
                    //assess_attr表保存
                    if($attrResult = $assessDao->setAssessAttrRecord($attrRecord)){
                        if($clearTag){//当clearTag标识为true 清除残留的delete用户ITEM
                            $assessDao->clearDeleteUserItem($base_id,$uids);
                        }
                        $assessDao->setAssessUserItemRecord($uids,$attrResult);
                    }
                }
                echo json_encode(array('status'=>'success','base_id'=>$base_id));
            }
        }
        die();
    }else{
        //当$base_id存在时，说明此考核已经存在，属于更新操作，否则属于新建考核
        $record_info = array();
        if($base_id){
            $record_info = $assessDao->getAssessRecordInfo($base_id);
            $relationUsers = $assessDao->getRelatedUserRecord($base_id);
        }
    }


    require_once BATH_PATH."source/Widget/AssessAttrWidget.php";
    $assessAttrWidget = new AssessAttrWidget(new NewTpl());
    $tpl = new NewTpl('assessment/launchAssess.php',array(
        'record_info'=>$record_info,
        'relationUsers'=>$relationUsers,
        'attrTypeMaps'=>AssessDao::$attrTypeMaps,
        'assessAttrWidget'=>$assessAttrWidget,
        'bus_parent_list'=>$assessDao->getBusParentDropList(),
        'conditionUrl'=>$assessDao->getConditionParamUrl(array('a','m')),
        'mValid'=>$mValid
    ));

    $tpl->render();
    die();
}



if($_REQUEST['act']=='ajaxIndicatorClassify'){
    if(isset($_GET['indicator_parent'])){
        $indicator_parent = $_GET['indicator_parent'];
        $retData = array();
        require_once BATH_PATH.'source/Dao/IndicatorDao.php';
        $ind_dao = new IndicatorDao();
        $retData['data'] = $ind_dao->getIndicatorChildList($indicator_parent);
        $retData['status'] = 'success';
        echo json_encode($retData);
        die();
    }
}

if($_REQUEST['act']=='autoUserName'){
    $assessDao = new AssessDao();
    $userList = $assessDao->getAutoUserList($_REQUEST);
    echo json_encode($userList);
    die();
}

if($_REQUEST['act']=='selectUserList'){
    $pid = $_REQUEST['pid'];
    $cid = $_REQUEST['cid'];
    $tid = $_REQUEST['tid'];
    $uids = explode(',',$_REQUEST['uids']);
    $assessDao = new AssessDao();
    $extSql = '';
    if($tid){
        $extSql = " and did={$tid}";
    }
    $sql = "select userId,username,deptlist,card_no from sa_user where tixi={$pid} and comp_dept={$cid} and status=1 $extSql order by deptlist desc";
    $userList = $assessDao->db->GetAll($sql);
    $tpl = new NewTpl('assessment/selectUserList.php',array(
        'userList'=>$userList,
        'pid'=>$pid,
        'cid'=>$cid,
        'uids'=>$uids
    ));
    $tpl->render();
    die();
}

//用户业务单元，锁定，第一人设置 表单
if($_REQUEST['act']=='userLockInfoForm'){
    $assessDao = new AssessDao();
    $userId = $_REQUEST['userId'];
    $record_info = array();
    if($_REQUEST['ajax']==1){
        $tixi = $_REQUEST['tixi'];
        $comp_dept = $_REQUEST['comp_dept'];
        $did = $_REQUEST['did'];
        $lockStatus = $_REQUEST['lockStatus'];
        try{
            $sql = "update sa_user set tixi={$tixi},comp_dept={$comp_dept}, did={$did}, lockStatus={$lockStatus} where userId={$userId}";
            $ret = array();
            $rs = $assessDao->db->Execute($sql);
            $ret['status'] = 'success';
        }catch (Exception $e){
            $ret['status'] = 'fail';
        }
        echo json_encode($ret);
        die();
    }
    $sql = "select username,card_no,tixi,comp_dept,did,lockStatus,userId from sa_user where userId=$userId";
    $record_info = $assessDao->db->GetRow($sql);
    $tpl = new NewTpl('assessment/userLockInfoForm.php',array(
        'record_info'=>$record_info,
        'bus_parent_list'=>$assessDao->getBusParentDropList(),
    ));
    $tpl->render();
    die();
}