<?php
class portal{
    function __construct(){
        global $db;
        $this->db = $db;
        require_once  P_UTIL."NewTpl.php";
    }

    function display(){
        global $m,$a;
        if(file_exists('source/portal/'.$m.'/'.$a.'.php')) return 'source/portal/'.$m.'/'.$a.'.php';
        if(file_exists('source/portal/'.$m.'/index.php')) return 'source/portal/'.$m.'/index.php';
        if(file_exists('source/portal/'.$m.'.php')) return 'source/portal/'.$m.'.php';

        $output  = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">';
        $output .= '<html xmlns="http://www.w3.org/1999/xhtml">';
        $output .= '<head>';
        $output .= '<meta http-equiv="Content-Type" content="text/html; charset=gb2312" />';
        $output .= '<title>�����ʵ�ҳ�治���ڡ�</title>';
        $output .= '</head>';
        $output .= '<body>';
        $output .= '<div style="width:400px;margin:100px auto 0;padding:10px;border:1px solid #AAA;background-color:#FFC;color:#333;font:12px/30px Verdana;">';
            $output .= '<div style="font:bold 14px/40px Verdana;color:#F00">�����ʵ�ҳ�治���ڡ�</div>';
            $output .= '���ܿ������ڿ����У��ɴ˸��������ķ��ʲ����������Ǹ��.<br />';
            $output .= 'File:source/portal/'.$m.($a?'/'.$a:'').'.php';
        $output .= '</div>';
        $output .= '</body>';
        $output .= '</html>';
        $this->halt($output);
    }

    function halt($string=''){
        echo $string;
        exit;
    }
}
?>