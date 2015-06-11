<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 15-6-11
* Time: 下午1:26
*/
class AssessAttrWidget{
    public $tpl;
    public function __construct($tpl){
        $this->tpl = $tpl;
    }

    static $renderPathMaps = array(
        '1'=>'commission,job',
        '2'=>'score',
        '3'=>'target'
    );
    public function renderAttr($renderData,$attrType=false){
        $index = $attrType;
        if(array_key_exists($index,self::$renderPathMaps)){
            $prefixPathArr = explode(',',self::$renderPathMaps[$index]);
            foreach($prefixPathArr as $prefix){
                if($renderPath = $this->getRenderPath($prefix)){
                    $this->tpl->set_tpl($renderPath);
                    $this->tpl->set_data(array('renderData'=>$renderData));
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
}