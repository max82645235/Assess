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


//����base_id��ȡ���������Ϣ
function getAssessRecordInfo($base_id){
    global $db;

    $record_info = array();
    //��ȡ�����������Ϣ
    $base_sql = "select * from sa_assess_base where base_id={$base_id} ";
    $base_info = $db->GetOne($base_sql);
    if($base_info){
        $record_info['base_info'] = $base_info;
    }

    //��ȡ�������ͱ������Ϣ
    $attr_sql = "select * from sa_assess_attr where  base_id={$base_id}";
    $attr_info = $db->GetAll($attr_sql);
    if($attr_info){
        $record_info['attr_info'] = $attr_info;
    }

    return $record_info;
}

$base_id = getgpc('base_id'); //���˱�����
$user_id = '';//��ǰ�û����Id

if(checkUserAuthority()){
    //ajax���ύ
    if(isset($_REQUEST['formSub'])){

    }else{
        //��$base_id����ʱ��˵���˿����Ѿ����ڣ����ڸ��²��������������½�����
        $record_info = array();
        if($base_id){
            $record_info = getAssessRecordInfo($base_id);
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
}

