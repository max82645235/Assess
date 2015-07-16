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

    public function disabled($element=''){
        if($this->mValid){
            return $this->mValid->getDisableValid($element);
        }
    }

    public function validElement($element=''){
        if($this->mValid){
            return $this->mValid->validElement($element);
        }
    }

    static $compareStatus = false;
    static $diffData;
    public function renderItemTable($record_info,$compareStatus=false){
        $itemInfo = $record_info['item'];
        $assessAttrType = $record_info['relation']['assess_attr_type'];
        self::$diffData = $record_info['relation']['diffData'];

        $prefixPathArr = explode(',',self::$renderPathMaps[$assessAttrType]);
        $itemList = array();
        foreach($itemInfo as $k=>$item){
            $t = AssessDao::$AttrRecordTypeMaps[$item['attr_type']];
            if(in_array($t,$prefixPathArr)){
                $itemList[] = $item;
            }
        }
        self::$compareStatus = $compareStatus;
        $renderPath = BATH_PATH.'template/assessment/widget/renderItemTable.php';
        $this->tpl->set_tpl($renderPath);
        $this->tpl->set_data(array('itemInfo'=>$itemList,'widget'=>$this,'assessAttrType'=>$assessAttrType,'relation'=>$record_info['relation']));
        $this->tpl->render();
    }


    public function getDifferShow($attr_type='',$itemData=array(),$isItemData=true){
        $sameStatus = true;
        $style = '';
        if(self::$compareStatus && self::$diffData){
            if(self::$diffData['type_differ']==1){
                $sameStatus = false;
            }elseif(self::$diffData['same']==0){
                if($itemData){
                    $attr = @$itemData['attr'];
                    $index = @$itemData['index'];

                    if(!$isItemData && self::$diffData['compare_data'][$attr_type][$attr]){
                        $sameStatus = false;
                    }

                    if($isItemData && self::$diffData['compare_data'][$attr_type]['itemData'][$index][$attr]){
                        $sameStatus = false;
                    }
                }
            }
        }

        if(!$sameStatus){
            $style = 'style="background-color:#FFB6C1;"';
        }
        return $style;
    }

    public function getTrIsShow(){
        $style = '';
        $className = $this->mValid->getClassName();
        $hidden = false;
        if($className =='HrValid'&& $this->mValid->record && $this->mValid->record['assess_attr_type']){
            $hidden = true;
        }

        if($className =='LeaderValid' ||$className =='StaffValid'){
            if($this->mValid->userAssessData && $this->mValid->userAssessData['assess_attr_type']){
                $hidden = true;
            }
        }
        if($hidden){
            $style = 'display:none;';
        }
        return $style;
    }

    public function rewardPunish($relationData){
        $rpData = unserialize($relationData['rpData']);
        $renderPath = BATH_PATH."template/assessment/widget/rewardPunishWidget.php";
        $this->tpl->set_tpl($renderPath);
        $this->tpl->set_data(array('rpData'=>$rpData));
        $this->tpl->render();
    }

}