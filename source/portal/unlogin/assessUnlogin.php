<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 15-7-24
 * Time: 上午9:54
 */
if($act=='ajaxBusClassify'){
    global $cfg,$p_tixi,$p_comp_dept;
    $retData = array('data'=>array(),'status'=>'empty');
    if(isset($_REQUEST['bus_area_parent']) && isset($cfg['tixi'])){
        $bus_area_parent = $_REQUEST['bus_area_parent'];
        $validAuth = $_REQUEST['validAuth'];
        require_once BATH_PATH . 'source/Dao/AssessDao.php';
        $assessDao = new AssessDao();
        if(isset($cfg['tixi'][$bus_area_parent])){
            foreach($cfg['tixi'][$bus_area_parent]['deptlist'] as $k=>$v){
                $curTx = ($bus_area_parent== $p_tixi) && ($p_comp_dept == $k);
                if($assessDao->validBusAuth($bus_area_parent,$k) || $curTx){
                    $tmp = array('value'=>$k,'name'=>iconv('GBK','UTF-8',$v));
                    $retData['data'][] = $tmp;
                }
            }
            $retData['status'] = 'success';
        }
    }
    echo json_encode($retData);
    die();
}

if($act=='ajaxBusThirdClassify'){
    global $cfg,$p_tixi,$p_comp_dept;
    $retData = array('data'=>array(),'status'=>'empty');
    if(isset($_REQUEST['bus_area_parent']) && isset($_REQUEST['bus_area_child']) && isset($cfg['tixi'])){
        $bus_area_parent = $_REQUEST['bus_area_parent'];
        $bus_area_child = $_REQUEST['bus_area_child'];
        $validAuth = $_REQUEST['validAuth'];
        require_once BATH_PATH . 'source/Dao/AssessDao.php';
        $assessDao = new AssessDao();
        if(isset($cfg['tixi'][$bus_area_parent])){
            foreach($cfg['tixi'][$bus_area_parent]['deptlist'] as $k=>$v){
                $curTx = ($bus_area_parent== $p_tixi) && ($p_comp_dept == $k);
                if($bus_area_child== $k && $curTx){
                    if(isset($cfg['tixi'][$bus_area_parent]['thirdList'])){
                        foreach($cfg['tixi'][$bus_area_parent]['thirdList']  as $tId=>$data){
                            $tmp = array('value'=>$tId,'name'=>iconv('GBK','UTF-8',$data));
                            $retData['data'][] = $tmp;
                        }
                    }
                    break;
                }
            }
            $retData['status'] = 'success';
        }
    }
    echo json_encode($retData);
    die();
}

if($act =='ajaxIndicatorClassify'){
    $retData = array('status'=>'empty');
    if(isset($_GET['indicator_parent'])){
        $indicator_parent = $_GET['indicator_parent'];
        $retData = array();
        require_once BATH_PATH . 'source/Dao/IndicatorDao.php';
        $ind_dao = new IndicatorDao();
        $retData['data'] = $ind_dao->getIndicatorChildList($indicator_parent);
        $retData['status'] = 'success';
    }
    echo json_encode($retData);
    die();
}


if($act =='uploadFile'){
    require_once BATH_PATH . 'source/uploadFile.php';
    //print_r($_FILES);
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

if($act== 'downFile'){
    ini_set('display_errors','on');
    error_reporting(E_ALL & ~E_NOTICE);
    require_once BATH_PATH . 'source/Util/DownloadFile.php';
    $filePath = urldecode($_REQUEST['filePath']);
    $download = new DownloadFile('php,exe,html',false);
     if(!$download->downloadfile($filePath))
     {
          echo $download->geterrormsg();
     }
    die();
}

