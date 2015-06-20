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
    protected $m;
    protected $a;
    protected $isMy;
    protected $act;
    public function __construct($m,$a,$act){
        $this->m = $m;
        $this->a = $a;
        $this->act = $act;
    }


    //判断是否有改条记录的操作权限
    public function validIsAuth(){
        if(getIsRootGroup() || ( $this->getUserGroupAuth() && $this->getBtnAuth())){
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

    protected function setAct($act){
        $this->act = $act;
    }

    //判断用户组权限
    public function getUserGroupAuth(){
        global $p_auth_all;
        if($p_auth_all || $this->getIsMy()){
            return true;
        }
    }

    //判断按钮权限
    public function getBtnAuth(){
        global $p_power;
        if(getIsRootGroup()){
            return true;
        }
        return  ($p_power[$this->m."_".$this->a."_".$this->act.""])?true:false;
    }

}