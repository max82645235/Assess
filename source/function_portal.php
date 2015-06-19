<?php
defined('IN_UF') or exit('Access Denied');

function admin_log($action,$tbl_key='',$tbl_value=''){
    global $db;
    $log = array();

    $log['action'] = $action;

    if($tbl_key && $tbl_value){
        $log[$tbl_key] = $tbl_value;
    }

    $log['uid'] = $_SESSION[DB_PREFIX.'uid'];
    $log['username'] = $_SESSION[DB_PREFIX.'username'];
    $log['dateline']  = time();
    $log['ip']   = get_client_ip();

    $sql = get_insert_sql('`'.DB_PREFIX.'log`',$log);
    $db->Execute($sql);
}

function get_api_content($api_url,$ifalert=1){
    $content = @curl_get_contents($api_url);
    $arr_content = @unserialize($content);

    if(!is_array($arr_content) && sizeof($arr_content) != 3){
        if($ifalert == 1){
            halt2('�ӿ�����,����ϵ����Ա~~','?m=login');
        }
        else{
            halt('�ӿ�����,����ϵ����Ա~~');
        }
    }

    if($arr_content['result'] == 0){
        if($ifalert == 1){
            halt2($arr_content['msg'],'?m=login');
        }
        else{
            halt($arr_content['msg']);
        }
    }

    return $arr_content['info'];
}

function curl_get_contents($url){
    $arr_url = explode("?",$url);
    $url = $arr_url[0];
    $postfield = $arr_url[1];

    $ch = curl_init(); 

    curl_setopt($ch, CURLOPT_URL, $url);  
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $postfield);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 2);
    curl_setopt($ch, CURLOPT_REFERER, $t_url); 
    curl_setopt($ch,CURLOPT_RETURNTRANSFER, 1);

    $content = curl_exec($ch);
    return $content; 
}

// ��ȡ����ָ������б�
function get_indicator_type_list(){
    global $db;

    $arr_indicator_types = array();
    $sql = "SELECT `typeid`, `type` FROM `".DB_PREFIX."indicator_type` WHERE `status`=1 ORDER BY `typeid`";
    $rs = $db->Execute($sql);
    while($r = $rs->FetchRow()){
        $arr_indicator_types[$r['typeid']] = $r['type'];
    }

    return $arr_indicator_types;
}

// ��ȡ��ɫ�б�
function get_group_list(){
    global $db,$p_gid;

    $arr_groups = array();
    $sql = "SELECT `id`, `title` FROM `".DB_PREFIX."group` WHERE `status`=1".($p_gid != 2 ? " AND `id`!=2" : "")." ORDER BY `id`";
    $rs = $db->Execute($sql);
    while($r = $rs->FetchRow()){
        $arr_groups[$r['id']] = $r['title'];
    }

    return $arr_groups;
}

// ��ȡ��������
function get_arr_city(){
    global $p_city,$cfg;

    $arr_city = $p_city != "����" ? array($p_city) : $cfg['city'];
    return $arr_city;
}

function get_city_by_dept($dept_list){
    global $cfg;

    $_cur_city = '����';
    $_arr_city = $cfg['city'];
    krsort($_arr_city);

    foreach($_arr_city as $_city){
        if(strstr($dept_list,$_city)){
            $_cur_city = $_city;
            break;
        }
    }

    return $_cur_city;
}

// ��ȡ��������
function get_city_where($tbl="A",$id="`city`"){
    global $p_city;

    $where = "";
    if($p_city != "����"){
        $where = " AND {$tbl}.{$id}='".$p_city."'";
    }

    return $where;
}

// ��ȡ�������� ����
function get_city_where_reverse($tbl="A",$id="`city`"){
    global $p_city;

    if($p_city != "����"){
        $where = " AND ({$tbl}.{$id}='����' OR {$tbl}.{$id}='".$p_city."')";
    }
    else{
        $where = " AND {$tbl}.{$id}='".$p_city."'";
    }

    return $where;
}

// ��ȡ�Ƿ��Լ���������
function get_adduid_where($tbl="A",$id="`adduid`"){
    global $p_auth_all,$p_uid;

    $where = "";
    if($p_auth_all != 1){
        $where = " AND {$tbl}.{$id}='".$p_uid."'";
    }

    return $where;
}

// ��ȡ�û���
function get_str_user($uid){
    global $db;

    $str_user = "";
    $sql = "SELECT `username` FROM `".DB_PREFIX."user` WHERE `uid`='$uid'";
    $rows = $db->Execute($sql);
    while($row = $rows->FetchRow()){
        $str_user = $row['username'];
    }

    return $str_user;
}

function get_swf_upload_sid(){
    global $p_uid,$p_gid,$p_username;

    $arr_authcode[] = $p_uid;
    $arr_authcode[] = $p_gid;
    $arr_authcode[] = $p_username;

    $authcode = get_authcode(implode("$",$arr_authcode),"ENCODE");
    return $authcode;
}

// �ͻ�������
function oa_pushmsg($to_id,$title,$msg,$type,$id){
    $post = "&a=pushmsg";

    $post .= "&to_id=".urlencode($to_id);
    $post .= "&title=".urlencode($title);
    $post .= "&msg=".urlencode($msg);
    $post .= "&type=".urlencode($type);
    $post .= "&id=".urlencode($id);

    $api_url = P_OA_API.$post;
    $info = get_api_content($api_url,0);
}

// oa �ʼ�
function oa_notice($toid,$fromid,$subject,$content){
    $post = "&a=sendmail";

    $post .= "&toid=".urlencode($toid);
    $post .= "&fromid=".urlencode($fromid);
    $post .= "&subject=".urlencode($subject);
    $post .= "&content=".urlencode($content);

    $api_url = P_OA_API.$post;
    $info = get_api_content($api_url,0);
}

// oa ֪ͨ
function oa_sms($toid,$fromid,$content,$smstype=13){
    $post = "&a=sendoasms";

    $post .= "&toid=".urlencode($toid);
    $post .= "&fromid=".urlencode($fromid);
    $post .= "&content=".urlencode($content);
    $post .= "&smstype=".intval($smstype);

    $api_url = P_OA_API.$post;
    $info = get_api_content($api_url,0);
}

// Ȩ���ж�
function check_auth($m,$a,$act,$inajax=0,$id=0,$authfield="adduid"){
    global $db,$tbl,$p_power,$p_uid,$p_auth_all;
    $flag = true;

    // �ж��Ƿ�������������
    if(($p_auth_all != 1) && ($id = intval($id)) && ($tbl != "")){
        $sql = "SELECT `".$authfield."` FROM $tbl WHERE `id`='$id'";
        $adduid = $db->GetOne($sql);

        if($adduid != $p_uid){
            $flag = false;
        }
    }

    // �ж��Ƿ���Ȩ��
    if($flag == false || ($p_power[$m."_".$a."_".$act] != 1)){
        if($inajax  == 1){
            echo("no auth");
            exit();
        }
        else{
            $referer = $_SERVER[HTTP_REFERER] ? $_SERVER[HTTP_REFERER] : "index.php?m=".$m."&a=".$a;
            $referer = $_POST[referer] ? $_POST[referer] : $referer;
            halt2('û��Ȩ��~~',$referer);
        }
    }

    return true;
}

// Ȩ���ж�
function tbl_check_auth($m,$a,$act,$tbl,$inajax=0,$id=0,$authfield="deal_userid"){
    global $db,$p_power,$p_uid,$p_auth_all;
    $flag = true;

    // �ж��Ƿ�������������
    if(($p_auth_all != 1) && ($id = intval($id)) && ($tbl != "")){
        $sql = "SELECT `".$authfield."` FROM $tbl WHERE `id`='$id'";
        $adduid = $db->GetOne($sql);
        
        if($adduid != $p_uid){
            $flag = false;
        }
    }

    // �ж��Ƿ���Ȩ��
    if($flag == false || ($p_power[$m."_".$a."_".$act] != 1)){
        if($inajax  == 1){
            echo("no auth");
            exit();
        }
        else{
            $referer = $_SERVER[HTTP_REFERER] ? $_SERVER[HTTP_REFERER] : "index.php?m=".$m."&a=".$a;
            $referer = $_POST[referer] ? $_POST[referer] : $referer;
            halt2('û��Ȩ��~~',$referer);
        }
    }

    return true;
}

function getUserId(){
    if(@LOCAL_EV){
        return 1;
    }
    return (isset($_SESSION[DB_PREFIX.'user_id']))?$_SESSION[DB_PREFIX.'user_id']:'';
}

function getIsRootGroup(){
    return true;
}

//��ȡ�û���ҵ����id
 function getUserBusId(){
    $busId = '';
    return $busId;
}

function alertMsg($msg,$url=''){
    $script = "<script>alert('{$msg}');";
    if($url){
        $script.="location.href='{$url}';";
    }
    $script.="</script>";
    return $script;
}
?>