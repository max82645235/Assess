<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 15-7-8
 * Time: 下午1:19
 */
abstract class BaseValid{
    static $elementsMap = array(
        'basicElement'=>array(
            'base_name','base_start_date','staff_sub_start_date',
        ),
        'flowElement'=>array(
            'assess_period_type','create_on_month_status','bus_area_parent','bus_area_child','bus_area_third',
            'username','adduser','selectUserList',
            'uids','lead_direct_set_status','assess_attr_type'
        )
    );
    public $baseId;
    public $record;
    protected $isLeadDirect;
    public function __construct($baseId){
        global $db;
        global $ADODB_FETCH_MODE;
        $this->db = $db;
        $this->baseId = $baseId;
        $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;//只查询关联索引结果
        $sql = "select * from sa_assess_base where base_id={$baseId}";
        $this->record = $this->db->GetRow($sql);
    }

    public function getDisableValid($element=''){
        $ret = $this->validElement($element);
        if(!$ret){
            return "disabled=\"disabled\"";
        }
    }

    public function getIsLeadDirect(){
        return $this->record['lead_direct_set_status']==1;
    }

    abstract public function validElement();

    public function getClassName(){
        return get_class($this);
    }
}