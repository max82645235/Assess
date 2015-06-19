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
                //assess_base主表保存
                if($base_id = $assessDao->setAssessBaseRecord($_REQUEST['subFormData']['baseData'])){
                    $attrRecord = array();
                    $attrRecordType = array_flip(AssessDao::$AttrRecordTypeMaps);
                    foreach($_REQUEST['subFormData']['attrData']['fromData']['handlerData'] as $key=>$data){
                        $tmp = array();
                        $tmp['base_id'] = $base_id;
                        $tmp['attr_type'] = $attrRecordType[$key];
                        $tmp['weight'] = (isset($data['weight']))?$data['weight']:'';
                        $tmp['cash'] = isset($data['cash'])?$data['cash']:'';
                        $tmp['itemData'] = $data['table_data'];
                        $attrRecord[$key] = $tmp;
                    }

                    //assess_attr表保存
                    if($attrResult = $assessDao->setAssessAttrRecord($attrRecord)){
                        $uids = explode(',',$_REQUEST['subFormData']['baseData']['uids']);
                        if($_REQUEST['subFormData']['baseData']['lead_direct_set_status']==0){//没有勾选直接由领导设置时
                            $assessDao->setAssessUserItemRecord($uids,$attrResult);
                            $assessDao->setAssessUserRelation($uids,$base_id);
                        }
                    }
                    echo json_encode(array('status'=>'success'));
                }
            }
            die();
        }else{
            //当$base_id存在时，说明此考核已经存在，属于更新操作，否则属于新建考核
            $record_info = array();
            if($base_id){
                $record_info = $assessDao->getAssessRecordInfo($base_id);
            }
        }


        require_once BATH_PATH."source/Widget/AssessAttrWidget.php";
        $assessAttrWidget = new AssessAttrWidget(new NewTpl());

        $tpl = new NewTpl('assessment/launchAssess.php',array(
            'record_info'=>$record_info,
            'attrTypeMaps'=>AssessDao::$attrTypeMaps,
            'assessAttrWidget'=>$assessAttrWidget,
            'cfg'=>$cfg,
            'conditionUrl'=>$assessDao->getConditionParamUrl(array('a','m'))
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




