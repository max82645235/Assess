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
function checkUserAuthority(){
    return true;
}
require_once BATH_PATH.'source/Dao/AssessDao.php';

$_REQUEST['act'] = (!isset($_REQUEST['act']))?'launchAssess':$_REQUEST['act'];
if($_REQUEST['act']=='launchAssess'){
    $base_id = getgpc('base_id'); //考核表主键
    $user_id = '';//当前用户身份Id
    $assessDao = new AssessDao();
    if(checkUserAuthority()){
        //ajax表单提交
        if(isset($_REQUEST['formSubTag']) && $_REQUEST['formSubTag']==1 && isset($_REQUEST['subFormData'])){
            if(isset($_REQUEST['subFormData']['baseData']) && isset($_REQUEST['subFormData']['attrData'])){
                if($base_id = $assessDao->setAssessBaseRecord($_REQUEST['subFormData']['baseData']['baseSubDataList'])){
                    $attrRecord = array();
                    $attrRecordType = array_flip(AssessDao::$AttrRecordTypeMaps);
                    foreach($_REQUEST['subFormData']['attrData']['fromData']['handlerData'] as $key=>$data){
                        $tmp = array();
                        $tmp['base_id'] = $base_id;
                        $tmp['attr_type'] = $attrRecordType[$key];
                        $tmp['weight'] = $data['height'];

                    }
                    if($attrResult = $assessDao->setAssessAttrRecord($base_id,$_REQUEST['subFormData']['attrData'])){
                        $uids = $_REQUEST['subFormData']['baseData']['baseSubDataList']['uids'];
                        if($_REQUEST['subFormData']['baseData']['baseSubDataList']['lead_direct_set_status']==0){//没有勾选直接由领导设置
                            $assessDao->setAssessUserItemRecord($uids,$attrResult);
                        }
                    }
                }
            }
        }else{
            //当$base_id存在时，说明此考核已经存在，属于更新操作，否则属于新建考核
            $record_info = array();
            if($base_id){
                $record_info = $assessDao->getAssessRecordInfo($base_id);
            }
        }


        $attrTypeMaps = array(
            '1'=>'量化指标类',
            '2'=>'工作任务类',
            '3'=>'打分类',
            '4'=>'提成类'
        );

        require_once BATH_PATH."source/Widget/AssessAttrWidget.php";
        $assessAttrWidget = new AssessAttrWidget(new NewTpl());

        $tpl = new NewTpl('assessment/launchAssess.php',array(
            'record_info'=>$record_info,
            'attrTypeMaps'=>$attrTypeMaps,
            'assessAttrWidget'=>$assessAttrWidget,
            'cfg'=>$cfg
        ));

        $tpl->render();
        die();
    }
}


//部门二级分类
if($_REQUEST['act']=='ajaxBusClassify'){
    if(isset($_GET['bus_area_parent']) && isset($cfg['tixi'])){
        $bus_area_parent = $_GET['bus_area_parent'];
        $retData = array();
        if(isset($cfg['tixi'][$bus_area_parent])){
            foreach($cfg['tixi'][$bus_area_parent]['deptlist'] as $k=>$v){
                $tmp = array('value'=>$k,'name'=>iconv('GBK','UTF-8',$v));
                $retData['data'][] = $tmp;
            }
            $retData['status'] = 'success';
        }
        echo json_encode($retData);
        die();
    }
}




