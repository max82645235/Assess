<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 15-6-8
 * Time: ����2:11
 *  Describe :  ���𿼺ˣ���д���˻�����Ϣ����������������Ϣ��
 * Author : wmc
 */
//�ж��û��Ľű�����Ȩ��
require_once BATH_PATH.'source/Dao/AssessDao.php';
require_once BATH_PATH.'source/Util/btnValid/HrValid.php';
$_REQUEST['act'] = (!isset($_REQUEST['act']))?'launchAssess':$_REQUEST['act'];
$filterActs = array('autoUserName','selectUserList');
checkUserAuthority($filterActs);//��֤act����Ȩ��
if($_REQUEST['act']=='launchAssess'){
    $base_id = getgpc('base_id'); //���˱�����
    $user_id = getUserId();//��ǰ�û����Id
    $assessDao = new AssessDao();
    $mValid = new HrValid($base_id);
    //ajax���ύ
    if(isset($_REQUEST['formSubTag']) && $_REQUEST['formSubTag']==1 && isset($_REQUEST['subFormData'])){
        if(isset($_REQUEST['subFormData']['baseData'])){
            //assess_base������
            if(mb_detect_encoding($_REQUEST['subFormData']['baseData']['base_name'])=='UTF-8'){
                $_REQUEST['subFormData']['baseData']['base_name'] = iconv('UTF-8','GBK//IGNORE',$_REQUEST['subFormData']['baseData']['base_name']);
            }
            if($base_id = $assessDao->setAssessBaseRecord($_REQUEST['subFormData']['baseData'])){
                $uids = explode(',',$_REQUEST['subFormData']['baseData']['uids']);
                $baseRecord = $assessDao->getAssessBaseRecord($base_id);
                $assessDao->setAssessUserRelation($uids,$baseRecord);//�����û���ϵ������
                $clearTag = $assessDao->clearDeleteUser($base_id,$uids); //���ɾ�����û�
                //�������ѡ�쵼����ʱ
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
                    //assess_attr����
                    if($attrResult = $assessDao->setAssessAttrRecord($attrRecord)){
                        if($clearTag){//��clearTag��ʶΪtrue ���������delete�û�ITEM
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
        //��$base_id����ʱ��˵���˿����Ѿ����ڣ����ڸ��²��������������½�����
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
    $uids = explode(',',$_REQUEST['uids']);
    $assessDao = new AssessDao();
    $sql = "select userId,username,deptlist from sa_user where tixi={$pid} and comp_dept={$cid} and status=1 order by deptlist desc";
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