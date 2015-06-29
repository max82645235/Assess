<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 15-6-11
 * Time: 下午1:26
 */
class AssessAttrWidget{
    public $tpl;
    public $mValid;
    public function __construct($tpl){
        $this->tpl = $tpl;
    }

    static $renderPathMaps = array(
        '1'=>'commission,job',
        '2'=>'score',
        '3'=>'target'
    );


    public function renderAttr($renderDataList,$attrType=false,$scoreList = array(),$mValid=array()){
        $index = $attrType;
        if(array_key_exists($index,self::$renderPathMaps)){
            $prefixPathArr = explode(',',self::$renderPathMaps[$index]);
            if($mValid){
                $this->mValid = $mValid;
            }

            foreach($prefixPathArr as $prefix){
                $renderData = array();
                foreach($renderDataList as $key=>$d){
                    if(AssessDao::$AttrRecordTypeMaps[$d['attr_type']]==$prefix){
                        $renderData = $d;
                        unset($renderDataList[$key]);
                        break;
                    }
                }
                if($renderPath = $this->getRenderPath($prefix)){
                    $this->tpl->set_tpl($renderPath);
                    $this->tpl->set_data(array('renderData'=>$renderData,'scoreList'=>$scoreList,'widget'=>$this));
                    $this->tpl->render();
                }
            }
        }
    }

    //获取对应挂件类型
    protected function getRenderPath($prefix){
        $renderPath = BATH_PATH.'template/assessment/widget/'.$prefix."TypeWidget.php";
        if(file_exists($renderPath)){
            return $renderPath;
        }
    }

    public function renderTableBaseInfo($base_id,$userId){
        require_once BATH_PATH.'source/Dao/AssessDao.php';
        $assessDao = new AssessDao();
        $baseInfo = $assessDao->getTableBaseInfo($base_id,$userId);
        $renderPath = BATH_PATH."template/assessment/widget/baseInfoTableWidget.php";
        $this->tpl->set_tpl($renderPath);
        $this->tpl->set_data(array('baseInfo'=>$baseInfo));
        $this->tpl->render();
    }

    public function disabled(){
        if($this->mValid){
            return $this->mValid->getDisableValid();
        }
    }

    public function validElement(){
        if($this->mValid){
            return $this->mValid->validElement();
        }
    }

    public function renderItemTable($itemInfo,$assessAttrType){
        $renderPath = BATH_PATH.'template/assessment/widget/renderItemTable.php';
        $this->tpl->set_tpl($renderPath);
        $this->tpl->set_data(array('itemInfo'=>$itemInfo,'widget'=>$this,'assessAttrType'=>$assessAttrType));
        $this->tpl->render();
    }
}