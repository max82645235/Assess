<?php
defined('IN_UF') or exit('Access Denied');

// ��ǰʱ��
$cur_year = date("Y");
$cur_mon = date("m");
$cur_day = date("d");
$cur_hour = date('H');
$cur_minite = date('i');
$cur_second = date('s');
$str_cur_time = "$cur_year,$cur_mon,$cur_day,$cur_hour,$cur_minite,$cur_second";

// �ʺ���
$int_hour = date("H",time());

if($int_hour >= 18 && $int_hour <= 23){
    $str_hello = "���Ϻã�";
}
elseif($int_hour >= 0 && $int_hour <= 5){
    $str_hello = "ҹ��ã�";
}
elseif($int_hour >= 6 && $int_hour <= 8){
    $str_hello = "���Ϻã�";
}
elseif($int_hour >= 9 && $int_hour <= 11){
    $str_hello = "����ã�";
}
elseif($int_hour >= 12 && $int_hour <= 13){
    $str_hello = "����ã�";
}
elseif($int_hour >= 14 && $int_hour <= 17){
    $str_hello = "����ã�";
}

include(Template("top"));
?>