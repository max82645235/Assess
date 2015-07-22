<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 15-6-18
* Time: 下午4:11
*   $obj = new Auth($m,$a,$act);
*   $obj->setIsMy(true| false);默认false
*   return  $obj->validIsAuth();
*/
class Auth{
    protected $isMy;
    protected $authList;

    public function addAuthItem($key,$authItem = array()){
        $this->authList[$key] = $authItem;
    }


    //判断是否有改条记录的操作权限
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


    //判断用户组权限
    public function getUserGroupAuth(){
        global $p_auth_all;
        if($p_auth_all || $this->getIsMy()){
            return true;
        }
    }

    //判断按钮权限
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