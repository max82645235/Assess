<?php
defined('IN_UF') or exit('Access Denied');

if($a == 'update_user'){
    $authcode = get_authcode($_GET['authcode']);
    $userinfo = @unserialize($authcode);

    if(!is_array($userinfo)){
        halt("error input");
    }

    $uid = $userinfo['uid'];
    $username = $userinfo['username'];
    $did = $userinfo['deptid'];
    $dept = $userinfo['department'];
    $deptlist = $userinfo['department_all'];
    $priv = $userinfo['priv'];
    $tixi = $userinfo['tixi'];
    $comp_dept = $userinfo['comp_dept'];

    if(!$uid || !$username || !$did || !$dept || !$deptlist || !$priv || !$tixi){
        halt("error input");
    }

    $info = array();

    $info['username'] = $username;
    $info['did'] = $did;
    $info['dept'] = $dept;
    $info['deptlist'] = $deptlist;
    $info['priv'] = $priv;
    $info['tixi'] = $tixi;
    $info['comp_dept'] = $comp_dept;
    $info['city'] = get_city_by_dept($info['deptlist']);
    $info['status'] = 1;

    $sql = "SELECT `uid` FROM `".DB_PREFIX."user` WHERE `uid`='".$uid."'";
    if($user = $db->GetRow($sql)){
        $sql = get_update_sql("`".DB_PREFIX."user`",$info,"uid='".$uid."'");
        $db->Execute($sql);
    }
    else{
        $info['uid'] = $uid;

        $info['lastlogin'] = time();
        $info['dateline'] = time();

        $sql = get_insert_sql("`".DB_PREFIX."user`",$info);
        $db->Execute($sql);
    }

    halt("OK");
}

if($a == 'get_user_info'){
    if(!$_REQUEST['uid']){
        halt("error input");
    }

    $uid = get_authcode($_REQUEST['uid']);
    $sql = "SELECT `gid` FROM `".DB_PREFIX."user` WHERE `uid`='".$uid."'";
    $user = $db->GetRow($sql);

    $userinfo = array();
    $userinfo['gid'] = $user['gid'];

    echo(serialize($user));
    exit;
}

if($a == 'del_user'){
    $uid = get_authcode($_REQUEST['uid']);
    if($uid == ""){
        halt("error input");
    }

    $info = array();
    $info['gid'] = 0;

    $sql = get_update_sql("`".DB_PREFIX."user`",$info,"uid='".$uid."'");
    $db->Execute($sql);

    halt("OK");
}

if($a == 'get_oa_msg'){
    halt("get_oa_msg {$uid}");
}

if($a=='ajaxBusClassify'){
    global $cfg;
    if(isset($_REQUEST['bus_area_parent']) && isset($cfg['tixi'])){
        $bus_area_parent = $_REQUEST['bus_area_parent'];
        $validAuth = $_REQUEST['validAuth'];
        $retData = array('data'=>array());
        require_once BATH_PATH.'source/Dao/AssessDao.php';
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

if($a =='ajaxIndicatorClassify'){
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


if($a =='uploadFile'){
    require_once BATH_PATH.'source/uploadFile.php';
    $uf = new UploadFile("file");//upfile为上传空间file的name属性
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
?>