<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 15-7-8
 * Time: ÏÂÎç1:29
 */
require_once 'BaseValid.php';
abstract class UserValid extends BaseValid{
    public $userAssessData;
    public function __construct($baseId,$userId){
        parent::__construct($baseId);
        $sql = "select * from sa_assess_user_relation where base_id={$baseId} and userId={$userId}";
        $this->userAssessData = $this->db->GetRow($sql);
    }
}