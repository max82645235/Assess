<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 15-7-8
* Time: ����1:19
*/
require_once 'UserValid.php';
class LeaderValid extends UserValid{
    public function validElement($element=''){
        $user_assess_status = $this->userAssessData['user_assess_status'];
        switch($user_assess_status){
            case AssessFlowDao::AssessCreate://����
                        if($this->getIsLeadDirect()){
                            return true;
                        }
                break;

            case AssessFlowDao::AssessPreLeadVIew://Ԥ��
                        if($this->getIsLeadDirect()){
                            return true;
                        }
                break;

            case AssessFlowDao::AssessRealLeadView://����
                        if(!$element){
                            return true;
                        }
                break;
        }

        return false;
    }
}