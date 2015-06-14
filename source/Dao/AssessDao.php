<?php
/**
 * Created by PhpStorm.
 * User: wmc
* Date: 15-6-13
* Time: ����1:25
* Describe: ���˱�ҵ��SQL
*/
class AssessDao{
    public $db;
    public function __construct(){
        global $db;
        $this->db = $db;
    }

    static $AssessPeriodTypeMaps = array(
        '1'=>'�¶�',
        '3'=>'����',
        '6'=>'�����',
        '12'=>'���'
    );

    static $AttrRecordTypeMaps = array(
        '1'=>'commission','2'=>'job','3'=>'score','4'=>'target'
    );

    //����base_id��ȡ���������Ϣ
    public function getAssessRecordInfo($base_id){
        $record_info = array();
        //��ȡ�����������Ϣ
        $base_sql = "select * from sa_assess_base where base_id={$base_id} ";
        $base_info = $this->GetOne($base_sql);
        if($base_info){
            $record_info['base_info'] = $base_info;
        }

        //��ȡ�������ͱ������Ϣ
        $attr_sql = "select * from sa_assess_attr where  base_id={$base_id}";
        $attr_info = $this->GetAll($attr_sql);
        if($attr_info){
            $record_info['attr_info'] = $attr_info;
        }

        return $record_info;
    }

    //���ݿ������ڣ���ʼʱ���ȡ���˽���ʱ��
    static function getAssessBaseEndDate($periodType,$startDate){
        return date('Y-m-d H:i:s',strtotime('+'.$periodType.' month',strtotime($startDate)));
    }

    //���ÿ��˻�������Ϣ
    public function setAssessBaseRecord($baseRecord){
        try{
            $tableSafeAttr = array(
                'base_id','base_name','base_start_date','bus_area_parent','bus_area_child','lead_direct_set_status','lead_plan_end_date','lead_plan_start_date',
                'lead_sub_end_date','lead_sub_start_date','staff_plan_end_date','staff_plan_start_date','staff_sub_end_date','staff_sub_start_date',
                'uid','assess_attr_type','assess_period_type'
            );
            $tbl = "`".DB_PREFIX."assess_base`";
            $baseRecord['base_end_date'] = self::getAssessBaseEndDate($baseRecord['assess_period_type'],$baseRecord['base_start_date']);
            foreach($baseRecord as $key=>$attr){
                if(!in_array($key,$tableSafeAttr)){
                    unset($baseRecord[$key]);
                }
            }
            if(isset($baseRecord['base_id'])){
                $sql = get_update_sql($tbl,$baseRecord);
                $this->db->Execute($sql);
                $base_id = $baseRecord['base_id'];
            }else{
                $sql = get_insert_sql($tbl,$baseRecord);
                $this->db->Execute($sql);
                $base_id = $this->db->Insert_ID();
            }
            return $base_id;
        }catch (Exception $e){
            throw new Exception('setAssessBaseRecord new exception');
        }
    }

    //���ÿ��˷������Ա���Ϣ
    public function setAssessAttrRecord($attrRecord){
        $attrRetRecord = array();
        if($attrRecord){
            $tbl = "`".DB_PREFIX."assess_attr`";
            foreach($attrRecord as $key=>$data){
                $findRecordSql = "select * from {$tbl} where base_id = {$data['base_id']} and  attr_type = {$data['attr_type']}";
                $tableSafeAttr = array(
                    'attr_id','base_id','weight','attr_type','itemData','cash'
                );
                foreach($data as $k=>$attr){
                    if(!in_array($k,$tableSafeAttr)){
                        unset($data[$k]);
                    }

                    if($key=='itemData' && is_array($attr['itemData'])){
                        $data['itemData'] = serialize($data['itemData']);
                    }
                }
                if($findRecord = $this->db->GetRow($findRecordSql)){
                    foreach($findRecord as $k=>$v){
                        if(isset($data[$k])){
                            $findRecord[$k] = $data[$k];
                        }
                    }
                    $sql = get_update_sql($tbl,$findRecord);
                    $this->db->Execute($sql);
                    $attrRetRecord[] = $findRecord;
                }else{
                    $sql = get_insert_sql($tbl,$data);
                    $this->db->Execute($sql);
                    $attrRetRecord[] = $data;
                }
            }
        }
        return $attrRetRecord;
    }

    //���ÿ�����Աitem����Ϣ
    public function setAssessUserItemRecord($uidArr,$attrResult){
        if($uidArr && $attrResult){
            $tbl = "`".DB_PREFIX."assess_user_item`";
            foreach($uidArr as $uid){
                $tmpArr = array();
                foreach($attrResult as $k=>$attrData){
                    $tmpArr['uid'] = $uid;
                    $tmpArr['attr_id'] = $attrData['attr_id'];
                    $tmpArr['itemData'] = $attrData['itemData'];
                    $findRecordSql = "select * from {$tbl} where uid = $uid and  attr_id = {$attrData['attr_id']}";
                    if($findRecord = $this->db->GetRow($findRecordSql)){
                        foreach($findRecord as $k=>$v){
                            if(isset($tmpArr[$k])){
                                $findRecord[$k] = $tmpArr[$k];
                            }
                        }
                        $sql = get_update_sql($tbl,$findRecord);
                        $this->db->Execute($sql);
                    }else{
                        $sql = get_insert_sql($tbl,$tmpArr);
                        $this->db->Execute($sql);
                    }
                }
            }

        }
    }
}