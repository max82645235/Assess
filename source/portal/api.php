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
        $sql = get_update_sql("`".DB_PREFIX."user`",$info,"uid='".$uid."' and lockStatus=0");
        $db->Execute($sql);
    }
    else{
        $info['uid'] = $uid;
        $info['gid'] = 1;

        $info['lastlogin'] = time();
        $info['dateline'] = time();

        $sql = get_insert_sql("`".DB_PREFIX."user`",$info);
        $db->Execute($sql);
    }

    halt("OK");
}

if($a == 'update_user_name'){
    $authcode = get_authcode($_GET['authcode']);
    $userinfo = @unserialize($authcode);

    if(!is_array($userinfo)){
        halt("error input");
    }

    $uid = $userinfo['uid'];
    $username = $userinfo['username'];
    $mobile = $userinfo['mobile'];
    $card_no = $userinfo['card_no'];

    if(!$uid || !$username){
        halt("error input");
    }

    $info = array();

    $info['username'] = $username;
    $info['card_no'] = $card_no;
    $info['mobile'] = $mobile;

    $sql = get_update_sql("`".DB_PREFIX."user`",$info,"uid='".$uid."'");
    $db->Execute($sql);

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
    $info['status'] = 0;

    $sql = get_update_sql("`".DB_PREFIX."user`",$info,"uid='".$uid."'");
    $db->Execute($sql);

    halt("OK");
}

if($a == 'get_oa_msg'){
    halt("get_oa_msg {$uid}");
}


//获取相关用户考核信息，为oa系统提供api接口
if($a== 'getUserAssessInfo'){
    require_once BATH_PATH . 'source/Util/OaUserAssess.php';
    require_once BATH_PATH . 'source/Dao/AssessFlowDao.php';
    $uid = $_REQUEST['uid'];
    $flowDao = new AssessFlowDao();
    $oaObj = new OaUserAssess($uid,$flowDao);
    $assessInfo = $oaObj->getAssessInfo();
    if($_REQUEST['debug']==1){
        echo "<pre>";
        print_r($assessInfo);
        echo "</pre>";
        exit;
    }
    echo serialize($assessInfo);
}
?>