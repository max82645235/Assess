<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 15-6-25
 * Time: 下午6:44
 *  Hr考核发布页各元素修改权限验证
 */
class ModificationValid{
    public $db;
    public $record;
    protected $isLeadDirect;
    public function __construct($baseId){
        global $db;
        global $ADODB_FETCH_MODE;
        $this->db = $db;
        $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;//只查询关联索引结果
        $sql = "select * from sa_assess_base where base_id={$baseId}";
        $this->record = $this->db->GetRow($sql);
    }


    public function getIsLeadDirect(){
        return $this->record['lead_direct_set_status']==1;
    }

    static $elementsMap = array(
        'basicElement'=>array(
            'base_name','base_start_date','staff_sub_start_date',
        ),
        'flowElement'=>array(
            'assess_period_type','create_on_month_status','bus_area_parent','bus_area_child',
            'username','adduser','selectUserList',
            'uids','lead_direct_set_status','assess_attr_type'
        )
    );

    public function getDisableValid($element=''){
        $ret = $this->validElement($element);
        if(!$ret){
            return "disabled=\"disabled\"";
        }
    }

    public function validElement($element=''){
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
                            return false;
                break;
            case AssessDao::HrAssessOver:
                            return false;
                break;
        }
        return false;
    }
}