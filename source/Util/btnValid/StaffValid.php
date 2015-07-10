<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 15-7-8
 * Time: 下午1:19
 */
require_once 'UserValid.php';
class StaffValid extends UserValid{
    public function validElement($element=''){
        $user_assess_status = $this->userAssessData['user_assess_status'];
        switch($user_assess_status){
            case AssessFlowDao::AssessPreStaffWrite://员工填写计划
                        if($this->getIsLeadDirect()){
                            return true;
                        }
                break;

            case AssessFlowDao::AssessPreReport://员工提报
                        if(!$element ||$element!='valid_score_cash'){
                            return true;
                        }
                break;
        }
        return false;
    }
}