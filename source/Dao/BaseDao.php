<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 15-6-16
 * Time: ����3:33
 */
class BaseDao{
    public $db;
    public function __construct(){
        global $db;
        global $ADODB_FETCH_MODE;
        $this->db = $db;
        $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;//ֻ��ѯ�����������
    }

}