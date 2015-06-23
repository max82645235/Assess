<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 15-6-16
 * Time: 下午3:33
 */
class BaseDao{
    public $db;
    public function __construct(){
        global $db;
        global $ADODB_FETCH_MODE;
        $this->db = $db;
        $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;//只查询关联索引结果
    }


    static function get_insert_sql($tbl,$arrFields){
        $ctn = 0;
        foreach($arrFields as $k=>$val){
            if($ctn == 0){
                $ss1 = "`".$k."`";
                $ss2 = "'".($val)."'";
            }
            else{
                $ss1.= ", `".$k."`";
                $ss2.= ", '".($val)."'";
            }
            $ctn++;
        }

        $sql = "INSERT INTO $tbl ($ss1) VALUES ($ss2)";
        return $sql;
    }

    static  function get_update_sql($tbl,$arrFields,$where=false){
        $ctn = 0;
        foreach($arrFields as $k=>$val){
            if($ctn==0){
                $ss = "`".$k."`='".($val)."' ";
            }else{
                $ss.=  ",`".$k."`='".($val)."' ";
            }
            $ctn++;
        }
        if($where) $sql = "UPDATE $tbl SET $ss WHERE $where";
        else $sql = "UPDATE $tbl SET $ss";
        return $sql;
    }

}