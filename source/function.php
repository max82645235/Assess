<?php
defined('IN_UF') or exit('Access Denied');

function epre($array){
    echo("<pre>");
    print_r($array);
    echo("</pre>");
}

function json_halt($result,$msg){
    header('Content-type: text/html; charset=UTF-8');

    $arr = array();
    $arr[error] = $result;

    if($result == 1){
        $arr[message] = iconv("gbk","utf-8//ignore",$msg);
    }
    else{
        $arr[url] = $msg;
    }

    echo(json_encode($arr));
    exit;
}

function halt($error){
    echo is_array($error)?$error['message']:$error;
    exit;
}

function halt2($msg='',$url='',$parent=0){
    $output = '';

    $output .= '<script type="text/javascript">';
    $output .= $msg?'alert("'.$msg.'");':'';

    if($parent){
        for($i=0;$i<$parent;$i++){
            $output .= 'parent.';
        }
    }

    $output .= $url?'document.location.href="'.$url.'";':'';
    $output .= '</script>';
    echo $output;
    exit;
}

function halt_http_referer($msg=''){
    global $m,$a;

    $referer = $_SERVER[HTTP_REFERER] ? $_SERVER[HTTP_REFERER] : "index.php?m=".$m."&a=".$a;
    halt2($msg,$referer);
}

function halt_referer($msg=''){
    global $m,$a;

    $referer = $_SERVER[HTTP_REFERER] ? $_SERVER[HTTP_REFERER] : "index.php?m=".$m."&a=".$a;
    $referer = $_POST[referer] ? $_POST[referer] : $referer;
    halt2($msg,$referer);
}

function redirect($url,$time=0,$msg=''){
    $url = str_replace(array("\n","\r"),'',$url);
    if(empty($msg)) $msg = "ϵͳ����{$time}��֮���Զ���ת��{$url}��";

    if(!headers_sent()){
        if(!$time){
            header('Location: '.$url);
        }else{
            header("refresh:{$time};url={$url}");
            echo($msg);
        }
    }else{
        $str = "<meta http-equiv='Refresh' content='{$time};URL={$url}'>";
        if($time)
            $str .= $msg;
        echo $str;
    }
    exit;
}

function csubstr($str,$start,$len){
    $strlen = strlen($str); 
    $clen = 0;

    for($i = 0;$i < $strlen; $i++,$clen++){
        if($clen >= $start + $len){
            break;
        }

        if(ord(substr($str,$i,1)) > 0xa0){
            if($clen >= $start){
                $tmpstr .= substr($str,$i,2);
            }

            $i++;
        }
        else{
            if($clen >= $start){
                $tmpstr .= substr($str,$i,1);
            }
        }
    }

    return $tmpstr; 
}

function write_file($file,$source,$do='w'){
    if($fp = fopen($file,$do)){
        if(flock($fp,LOCK_EX)){
            $fileSource = fwrite($fp,$source);
            flock($fp,LOCK_UN);
        }else{
            die("�޷������ļ������ļ�ϵͳ������NFS��FAT��");
        }
        fclose($fp);
        return $fileSource;
    }else{
        return false;
    }
}

function page($count=0,$limit=0,$page=1,$pageurl='',$anchor=''){
    $pages  = $count?ceil($count/$limit):1;
    $output = '';

    $output .= '<span>��'.$count.'�� <em class="c-yel">'.$page.'</em>/'.$pages.'</span>';

    if(preg_match("/^.*(&pn=[0-9]*).*$/",$pageurl,$matches)){
        $pageurl = str_replace($matches[1],'',$pageurl);
    }

    if($pages>1){
        if($page > 1){
            $output .= '<a href="'.$pageurl.'&pn=1'.$anchor.'">��ҳ</a>';
            $output .= '<a href="'.$pageurl.'&pn='.($page>1?$page-1:$page).$anchor.'">��һҳ</a>';
        }

        if($page < $pages){
            $output .= '<a href="'.$pageurl.'&pn='.($page<$pages?$page+1:$page).$anchor.'">��һҳ</a>';
            $output .= '<a href="'.$pageurl.'&pn='.$pages.$anchor.'">ĩҳ</a>';
        }

        $output .= '<input type="text" class="width30" id="page_inp" value="'.($page<$pages?$page+1:$page).'" />&nbsp;';
        $output .= '<input type="button" name="��ת" class="tiaoz" onclick="window.location=\''.$pageurl.'&pn=\'+document.getElementById(\'page_inp\').value+\''.$anchor.'\'" />';
    }
    return $output;
}

function get_insert_sql($table,$arrFields){
    for($i=1;$i<=count($arrFields);$i++){ 
        if($i == 1){
            $ss1 = "`".key($arrFields)."`"; 
            $ss2 = "'".($arrFields[key($arrFields)])."'"; 
        }
        else{
            $ss1.= ", `".key($arrFields)."`";
            $ss2.= ", '".($arrFields[key($arrFields)])."'";
        }

        next($arrFields);
    }

    $sql = "INSERT INTO $table ($ss1) VALUES ($ss2)";
    return $sql;
}

function get_update_sql($table,$arrFields,$where=false){
    for($i=1;$i<=count($arrFields);$i++){ 
        if($i == 1) $ss = "`".key($arrFields)."`='".($arrFields[key($arrFields)])."' ";
        else $ss.= ",`".key($arrFields)."`='".($arrFields[key($arrFields)])."' ";

        next($arrFields) ;
    }

    if($where) $sql = "UPDATE $table SET $ss WHERE $where";
    else $sql = "UPDATE $table SET $ss";

    return $sql;
}

function get_client_ip(){
    $cip = $_SERVER["REMOTE_ADDR"];
    if(!empty($_SERVER["HTTP_X_FORWARDED_FOR"])){
        $tip = split(",",$_SERVER["HTTP_X_FORWARDED_FOR"]);
        $cip = $tip[0];
    }

    return $cip;
}

function remote_file_exists($url_file){
    // �������
    $url_file = trim($url_file);
    if(empty($url_file)){
        return false;
    }

    $url_arr = parse_url($url_file);
    if(!is_array($url_arr) || empty($url_arr)){
        return false;
    }

    // ��ȡ�������� 
    $host = $url_arr['host'];
    $path = $url_arr['path'] ."?".$url_arr['query'];
    $port = isset($url_arr['port']) ?$url_arr['port'] : "80";

    // ���ӷ����� 
    $fp = @fsockopen($host, $port, $err_no, $err_str,30);
    if(!$fp){
        return false;
    }

    // ��������Э�� 
    $request_str = "GET ".$path."HTTP/1.1\r\n";
    $request_str .= "Host:".$host."\r\n";
    $request_str .= "Connection:Close\r\n\r\n";

    // �������� 
    fwrite($fp,$request_str);
    $first_header = fgets($fp, 1024);
    fclose($fp);

    // �ж��ļ��Ƿ���� 
    if(trim($first_header) == ""){
        return false;
    }

    if(!preg_match("/200/", $first_header)){ 
        return false; 
    }

    return true; 
}

function get_authcode($string,$operation="DECODE",$key="",$expiry=0) {
    // ��̬�ܳ׳��ȣ���ͬ�����Ļ����ɲ�ͬ���ľ���������̬�ܳ�
    $ckey_length=4;
    
    // �ܳ�
    $key=md5($key?$key:"house365");
    
    // �ܳ�a�����ӽ���
    $keya=md5(substr($key,0,16));

    // �ܳ�b��������������������֤
    $keyb=md5(substr($key,16,16));

    // �ܳ�c���ڱ仯���ɵ�����
    $keyc=$ckey_length?($operation=="DECODE"?substr($string,0,$ckey_length):substr(md5(microtime()),-$ckey_length)):"";

    // ����������ܳ�
    $cryptkey=$keya.md5($keya.$keyc);
    $key_length=strlen($cryptkey);

    // ���ģ�ǰ10λ��������ʱ���������ʱ��֤������Ч�ԣ�10��26λ��������$keyb(�ܳ�b)������ʱ��ͨ������ܳ���֤����������
    // ����ǽ���Ļ�����ӵ�$ckey_lengthλ��ʼ����Ϊ����ǰ$ckey_lengthλ���� ��̬�ܳף��Ա�֤������ȷ
    $string=$operation=="DECODE"?base64_decode(substr($string,$ckey_length)):sprintf("%010d",$expiry?$expiry+time():0).substr(md5($string.$keyb),0,16).$string;
    $string_length=strlen($string);
    $result="";
    $box=range(0,255);
    $rndkey=array();

    // �����ܳײ�
    for($i=0;$i<=255;$i++) {
        $rndkey[$i]=ord($cryptkey[$i%$key_length]);
    }

    // �ù̶����㷨�������ܳײ�����������ԣ�����ܸ��ӣ�ʵ���϶Բ������������ĵ�ǿ��
    for($j=$i=0;$i<256;$i++){
        $j=($j+$box[$i]+$rndkey[$i])%256;
        $tmp=$box[$i];
        $box[$i]=$box[$j];
        $box[$j]=$tmp;
    }

    // ���ļӽ��ܲ���
    for($a=$j=$i=0;$i<$string_length;$i++) {
        $a=($a+1)%256;
        $j=($j+$box[$a])%256;
        $tmp=$box[$a];
        $box[$a]=$box[$j];
        $box[$j]=$tmp;

        // ���ܳײ��ó��ܳ׽��������ת���ַ�
        $result.=chr(ord($string[$i])^($box[($box[$a]+$box[$j])%256]));
    }

    if($operation=="DECODE") {
        // substr($result,0,10)==0 ��֤������Ч��
        // substr($result,0,10)-time()>0 ��֤������Ч��
        // substr($result,10,16)==substr(md5(substr($result,26).$keyb),0,16) ��֤����������
        // ��֤������Ч�ԣ��뿴δ�������ĵĸ�ʽ
        if((substr($result,0,10)==0||substr($result,0,10)-time()>0) && substr($result,10,16)==substr(md5(substr($result,26).$keyb),0,16)){
            return substr($result,26);
        } else {
            return "";
        }
    } else {
        // �Ѷ�̬�ܳױ������������Ҳ��Ϊʲôͬ�������ģ�������ͬ���ĺ��ܽ��ܵ�ԭ��
        // ��Ϊ���ܺ�����Ŀ�����һЩ�����ַ������ƹ��̿��ܻᶪʧ��������base64����
        return $keyc.str_replace("=","",base64_encode($result));
    }
}

function checkUserAuthority($filterActs=array()){
    global $m,$a;
    require_once BATH_PATH.'source/Util/Auth.php';
    $act = $_REQUEST['act'];
    if($filterActs && in_array($act,$filterActs)){
        return true;
    }
    $auth = new Auth();
    $auth->addAuthItem($act,array('m'=>$m,'a'=>$a,'act'=>$act));
    if(!$auth->setIsMy(true)->validIsAuth($act)){
        echo "�Բ���,��û��Ȩ�޷��ʸ�ҳ�棡";
        die();
    }
    loadThirdBus();
}

function _unserialize($string)
{
    return unserialize(preg_replace('!s:(\d+):"(.*?)";!se', '"s:".strlen("$2").":\"$2\";"', $string));
}

function loadThirdBus(){
    global $cfg;
    require_once BATH_PATH.'source/Util/Mcache.php';
    $cacheKey = P_OA_API."&a=get_dept_list";
    $depList = array();
    try{
        $memcache = Mcache::getInstance();
        $cacheData = $memcache->get($cacheKey);
        if($cacheData===false){

            $api_url = P_OA_API."&a=get_dept_list";
            $apiRes = get_api_content($api_url);
            $cacheData = serialize($apiRes);
            if($apiRes){
                $memcache->set($cacheKey,$cacheData);
            }
        }
        $depList = unserialize($cacheData);
    }catch (Exception $e){
        $api_url = P_OA_API."&a=get_dept_list&uid=";
        $depList = get_api_content($api_url);
    }

    if(isset($depList['1']['deptlist'])){
        $depList['1']['deptlist']['999'] = "��һ����";
    }


    $cfg['tixi'] = $depList;
}


function leaderAuth($staff_uid=''){
    global $db;
    global $ADODB_FETCH_MODE;
    $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;//ֻ��ѯ�����������
    $userId = getUserId();
    if($userId){
        $uidStr = '';
        if(!isset($_SESSION['leadStaffList'])){
            $sql = "select low_userId from sa_user_relation where super_userId={$userId}";
            $rs = $db->getAll($sql);
            if($rs){
                $uids = '';
                foreach($rs as $v){
                    $uids.="{$v['low_userId']},";
                }
                $uidStr = $uids = substr($uids,0,-1);
                $_SESSION['leadStaffList'] = $uids;
            }
        }else{

            $uidStr = $_SESSION['leadStaffList'];
        }
        if($uidStr){
            $uidArr = explode(',',$uidStr);
            if($staff_uid && !in_array($staff_uid,$uidArr)){
                return false;
            }
            return true;
        }

    }
    return false;
}

?>