<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 15-7-24
 * Time: 上午9:54
 */

if($act=='ajaxBusClassify'){
    global $cfg;
    if(isset($_REQUEST['bus_area_parent']) && isset($cfg['tixi'])){
        $bus_area_parent = $_REQUEST['bus_area_parent'];
        $validAuth = $_REQUEST['validAuth'];
        $retData = array('data'=>array());
        require_once BATH_PATH . 'source/Dao/AssessDao.php';
        $assessDao = new AssessDao();
        if(isset($cfg['tixi'][$bus_area_parent])){
            foreach($cfg['tixi'][$bus_area_parent]['deptlist'] as $k=>$v){
                if(!$validAuth || $assessDao->validBusAuth($bus_area_parent,$k)){
                    $tmp = array('value'=>$k,'name'=>iconv('GBK','UTF-8',$v));
                    $retData['data'][] = $tmp;
                }
            }
            $retData['status'] = 'success';
        }
        echo json_encode($retData);
        die();
    }
}

if($act =='ajaxIndicatorClassify'){
    if(isset($_GET['indicator_parent'])){
        $indicator_parent = $_GET['indicator_parent'];
        $retData = array();
        require_once BATH_PATH . 'source/Dao/IndicatorDao.php';
        $ind_dao = new IndicatorDao();
        $retData['data'] = $ind_dao->getIndicatorChildList($indicator_parent);
        $retData['status'] = 'success';
        echo json_encode($retData);
        die();
    }
}


if($act =='uploadFile'){
    require_once BATH_PATH . 'source/uploadFile.php';
    $uf = new UploadFile("file");//upfile为上传空间file的name属性
    $uf->setFileType("image|office|rar");
    $uf->setSaveDir("/salary/");
    $stat=$uf->upload();
    $jsonArr = array();
    if($stat == "success"){
        $jsonArr['error'] = 0;
        $jsonArr['url'] = iconv("gbk","utf-8//ignore",$uf->getSaveFileURL());
    }else{
        $jsonArr['error'] = 1;
    }
    echo json_encode($jsonArr);
    die();
}