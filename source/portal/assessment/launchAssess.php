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
function checkUserAuthority(){
    return true;
}
require_once BATH_PATH.'source/Dao/AssessDao.php';

$_REQUEST['act'] = (!isset($_REQUEST['act']))?'launchAssess':$_REQUEST['act'];
if($_REQUEST['act']=='launchAssess'){
    $base_id = getgpc('base_id'); //���˱�����
    $user_id = '';//��ǰ�û����Id
    $assessDao = new AssessDao();
    if(checkUserAuthority()){
        //ajax���ύ
        if(isset($_REQUEST['formSubTag']) && $_REQUEST['formSubTag']==1 && isset($_REQUEST['subFormData'])){
            if(isset($_REQUEST['subFormData']['baseData']) && isset($_REQUEST['subFormData']['attrData'])){
                //assess_base������
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

                    //assess_attr����
                    if($attrResult = $assessDao->setAssessAttrRecord($attrRecord)){
                        $uids = explode(',',$_REQUEST['subFormData']['baseData']['uids']);
                        if($_REQUEST['subFormData']['baseData']['lead_direct_set_status']==0){//û�й�ѡֱ�����쵼����ʱ
                            $assessDao->setAssessUserItemRecord($uids,$attrResult);
                        }
                    }
                }
            }
            die();
        }else{
            //��$base_id����ʱ��˵���˿����Ѿ����ڣ����ڸ��²��������������½�����
            $record_info = array();
            if($base_id){

                $record_info = $assessDao->getAssessRecordInfo($base_id);
                // var_dump($record_info);exit;
            }
        }


        $attrTypeMaps = array(
            '1'=>'����ָ����',
            '2'=>'����������',
            '3'=>'�����',
            '4'=>'�����'
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


//���Ŷ�������
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




