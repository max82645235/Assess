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
    protected $m;
    protected $a;
    protected $isMy;
    protected $act;
    public function __construct($m,$a,$act){
        $this->m = $m;
        $this->a = $a;
        $this->act = $act;
    }


    //�ж��Ƿ��и�����¼�Ĳ���Ȩ��
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

    //�ж��û���Ȩ��
    public function getUserGroupAuth(){
        global $p_auth_all;
        if($p_auth_all || $this->getIsMy()){
            return true;
        }
    }

    //�жϰ�ťȨ��
    public function getBtnAuth(){
        global $p_power;
        if(getIsRootGroup()){
            return true;
        }
        return  ($p_power[$this->m."_".$this->a."_".$this->act.""])?true:false;
    }

}