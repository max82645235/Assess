<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 15-6-25
 * Time: 下午6:44
 *  Hr考核发布页各元素修改权限验证
 */
require_once 'BaseValid.php';
class HrValid extends BaseValid{
    public function validElement($element=''){
        if(!$this->record){
            return true;
        }
        $base_status = $this->record['base_status'];
        switch($base_status){
            case AssessDao::HrAssessWait:
                return true;
                break;

            case AssessDao::HrAssessPublish:
                if($this->getIsLeadDirect()){
                    if(!$element || in_array($element,self::$elementsMap['basicElement'])){
                        return true;
                    }
                }else{
                    return true;
                }
                break;

            case AssessDao::HrAssessChecking:
                if($this->getIsLeadDirect()){
                    if(in_array($element,self::$elementsMap['basicElement'])){
                        return true;
                    }
                }else{
                    if(in_array($element,self::$elementsMap['flowElement'])){
                        return false;
                    }else{
                        return true;
                    }
                }
                break;

            case AssessDao::HrAssessSubbing:
                if($this->getIsLeadDirect()){
                    if(in_array($element,self::$elementsMap['basicElement'])){
                        return true;
                    }
                }else{
                    if(in_array($element,self::$elementsMap['flowElement'])){
                        return false;
                    }else{
                        return true;
                    }
                }
                return false;
                break;
            case AssessDao::HrAssessOver:
                return false;
                break;
        }
        return false;
    }
}