<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 15-6-12
 * Time: ÏÂÎç4:10
 */
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
}
