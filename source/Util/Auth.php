<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 15-6-18
* Time: ����4:11
*   $obj = new Auth($m,$a,$act);
*   $obj->setIsMy(true| false);Ĭ��false
*   return  $obj->validIsAuth();
*/
class Auth{
    protected $isMy;
    protected $authList;

    public function addAuthItem($key,$authItem = array()){
        $this->authList[$key] = $authItem;
    }


    //�ж��Ƿ��и�����¼�Ĳ���Ȩ��
    public function validIsAuth($btnKey){

        if(getIsRootGroup() || ( $this->getUserGroupAuth() && $this->getBtnAuth($btnKey))){
            $this->setIsMy(false);
            return true;
        }
    }

    protected function getIsMy(){
        return $this->isMy;
    }

    public  function setIsMy($isMy=false){
        $this->isMy = $isMy;
        return $this;
    }


    //�ж��û���Ȩ��
    public function getUserGroupAuth(){
        global $p_auth_all;
        if($p_auth_all || $this->getIsMy()){
            return true;
        }
    }

    //�жϰ�ťȨ��
    public function getBtnAuth($btnKey){
        global $p_power;
        if(array_key_exists($btnKey,$this->authList)){
            $m = $this->authList[$btnKey]['m'];
            $a = $this->authList[$btnKey]['a'];
            $act = $this->authList[$btnKey]['act'];
            if($p_power[$m."_".$a."_".$act.""]){
                return true;
            }
        }
    }




}