<?php
/**
 * Created by PhpStorm.
 * User: wmc
 * Date: 15-6-13
 * Time: ����1:25
 * Describe: ���˱�ҵ��SQL
 */
require_once 'BaseDao.php';
class AssessDao extends BaseDao{
    static $AssessPeriodTypeMaps = array(
        '1'=>'�¶�',
        '3'=>'����',
        '6'=>'�����',
        '12'=>'���'
    );

    static $AttrRecordTypeMaps = array(
        '1'=>'commission','2'=>'job','3'=>'score','4'=>'target'
    );

    static $HrAssessBaseStatus =  array(
        '0'=>'������',
        '1'=>'�ѷ���',
        '2'=>'������',
        '3'=>'������'
    );

    const AssessCreate= 0;//���쵼����
    const AssessPreStaffWrite = 1;//��Ա����д
    const AssessPreLeadVIew = 2;//���쵼����|Ա������д���Ԥ��
    const AssessPreSuccess = 3;//�쵼����ͨ��
    const AssessRealLeadView = 4;//���쵼����|Ա������д��ʵ��
    const AssessRealSuccess = 5;//�쵼����ͨ��
    static  function get_insert_sql($tbl,$arrFields){
        $ctn = 0;
        foreach($arrFields as $k=>$val){
            if($ctn == 0){
                $ss1 = "`".$k."`";
                $ss2 = "'".($val)."'";
            }
            else{
                $ss1.= ", `".$k."`";
                $ss2.= ", '".($val)."'";
            }
            $ctn++;
        }

        $sql = "INSERT INTO $tbl ($ss1) VALUES ($ss2)";
        return $sql;
    }

    static  function get_update_sql($tbl,$arrFields,$where=false){
        $ctn = 0;
        foreach($arrFields as $k=>$val){
            if($ctn==0){
                $ss = "`".$k."`='".($val)."' ";
            }else{
                $ss.=  ",`".$k."`='".($val)."' ";
            }
            $ctn++;
        }
        if($where) $sql = "UPDATE $tbl SET $ss WHERE $where";
        else $sql = "UPDATE $tbl SET $ss";
        return $sql;
    }


    //����base_id��ȡ���������Ϣ
    public function getAssessRecordInfo($base_id){
        $record_info = array();
        //��ȡ�����������Ϣ
        $base_sql = "select * from sa_assess_base where base_id={$base_id} ";
        $base_info = $this->db->GetRow($base_sql);
        if($base_info){
            $record_info['base_info'] = $base_info;
        }

        //��ȡ�������ͱ������Ϣ
        $attr_sql = "select * from sa_assess_attr where  base_id={$base_id} order  by attr_type asc";
        $attr_info = $this->db->GetAll($attr_sql);
        if($attr_info){
            $record_info['attr_info'] = $attr_info;
        }

        return $record_info;
    }

    //���ݿ������ڣ���ʼʱ���ȡ���˽���ʱ��
    static function getAssessBaseEndDate($periodType,$startDate){
        return date('Y-m-d',strtotime('+'.$periodType.' month',strtotime($startDate)));
    }

    //���ÿ��˻�������Ϣ
    public function setAssessBaseRecord($baseRecord){
        try{
            $tableSafeAttr = array(
                'base_id','base_name','base_start_date','bus_area_parent','bus_area_child','lead_direct_set_status','lead_plan_end_date','lead_plan_start_date',
                'lead_sub_end_date','lead_sub_start_date','staff_plan_end_date','staff_plan_start_date','staff_sub_end_date','staff_sub_start_date',
                'uid','assess_attr_type','assess_period_type','base_end_date'
            );
            $tbl = "`".DB_PREFIX."assess_base`";
            $baseRecord['base_end_date'] = self::getAssessBaseEndDate($baseRecord['assess_period_type'],$baseRecord['base_start_date']);
            foreach($baseRecord as $key=>$attr){
                if(!in_array($key,$tableSafeAttr)){
                    unset($baseRecord[$key]);
                }
            }
            $baseRecord['base_name'] = iconv('UTF-8','GBK',$baseRecord['base_name']);
            if(isset($baseRecord['base_id']) && $baseRecord['base_id']){
                $base_sql = "select * from sa_assess_base where base_id={$baseRecord['base_id']} ";
                $findRecord = $this->db->GetRow($base_sql);
                if($findRecord['assess_attr_type']!=$baseRecord['assess_attr_type']){
                    $this->updateAttrTypeChangeEvent($baseRecord['base_id']);//�жϿ������͸ı��¼�
                }

                foreach($findRecord as $k=>$v){
                    if(isset($baseRecord[$k])){
                        $findRecord[$k] = $baseRecord[$k];
                    }
                }

                $baseRecord['update_time'] = date("Y-m-d H:i:s");

                $where = " base_id={$findRecord['base_id']}";
                $sql = self::get_update_sql($tbl,$findRecord,$where);
                $this->db->Execute($sql);
                $base_id = $findRecord['base_id'];
            }else{
                $baseRecord['create_time'] = date("Y-m-d H:i:s");
                $baseRecord['uid'] = getUserId();
                $sql = self::get_insert_sql($tbl,$baseRecord);
                $this->db->Execute($sql);
                $base_id = $this->db->Insert_ID();
            }
            return $base_id;
        }catch (Exception $e){
            throw new Exception('setAssessBaseRecord new exception');
        }
    }

    public function updateAttrTypeChangeEvent($base_id){
        $del_attr_sql = "delete from sa_assess_attr where base_id = {$base_id}";
        $this->db->Execute($del_attr_sql);
        $del_item_sql = "delete from sa_assess_user_item  where base_id = {$base_id}";
        $this->db->Execute($del_item_sql);
    }

    //���ÿ��˷������Ա���Ϣ
    public function setAssessAttrRecord($attrRecords){
        $attrRetRecord = array();
        if($attrRecords){
            $tbl = "`".DB_PREFIX."assess_attr`";

            foreach($attrRecords as $key=>$data){
                $findRecordSql = "select * from {$tbl} where base_id = {$data['base_id']} and  attr_type = {$data['attr_type']} ";
                $tableSafeAttr = array(
                    'attr_id','base_id','weight','attr_type','itemData','cash'
                );
                foreach($data as $k=>$attr){
                    if(!in_array($k,$tableSafeAttr)){
                        unset($data[$k]);
                    }

                    if($k=='itemData' && is_array($data['itemData'])){
                        foreach($data['itemData'] as $i=>$tt){
                            foreach($tt as $attr=>$v){
                                $data['itemData'][$i][$attr] = iconv('UTF-8','GBK',$v);
                            }
                        }
                        $data['itemData'] = serialize($data['itemData']);
                    }
                }

                if($findRecord = $this->db->GetRow($findRecordSql)){
                    foreach($findRecord as $k=>$v){
                        if(isset($data[$k])){
                            $findRecord[$k] = $data[$k];
                        }
                    }
                    $where = " attr_id={$findRecord['attr_id']}";
                    $sql =  self::get_update_sql($tbl,$findRecord,$where);
                    $this->db->Execute($sql);
                    $attrRetRecord[] = $findRecord;
                }else{
                    $sql = self::get_insert_sql($tbl,$data);
                    $this->db->Execute($sql);
                    $data['attr_id'] = $this->db->Insert_ID();
                    $attrRetRecord[] = $data;
                }
            }
        }
        return $attrRetRecord;
    }

    //���ÿ�����Աitem����Ϣ
    public function setAssessUserItemRecord($uidArr,$attrResult){
        if($uidArr && $attrResult){
            foreach($uidArr as $uid){
                $tmpArr = array();
                foreach($attrResult as $k=>$attrData){

                    //assess_user_item����� start---
                    $tbl = "`".DB_PREFIX."assess_user_item`";
                    $tmpArr['uid'] = $uid;
                    $tmpArr['attr_id'] = $attrData['attr_id'];
                    $tmpArr['itemData'] = $attrData['itemData'];
                    $tmpArr['base_id'] = $attrData['base_id'];
                    $tmpArr['item_weight'] = $attrData['weight'];
                    $findRecordSql = "select * from {$tbl} where uid = $uid and  attr_id = {$attrData['attr_id']}";
                    if($findRecord = $this->db->GetRow($findRecordSql)){
                        foreach($findRecord as $k=>$v){
                            if(isset($tmpArr[$k])){
                                $findRecord[$k] = $tmpArr[$k];
                            }
                        }
                        $where = " item_id = {$findRecord['item_id']}";
                        $sql = self::get_update_sql($tbl,$findRecord,$where);
                        $this->db->Execute($sql);
                    }else{
                        $sql = self::get_insert_sql($tbl,$tmpArr);
                        $this->db->Execute($sql);
                    }
                    //end ---assess_user_item�����


                    //assess_user_relation����� ----start
                    $tbl = "`".DB_PREFIX."assess_user_relation`";
                    $relationTmp = array();
                    $relationTmp['uid'] = $uid;
                    $relationTmp['base_id'] = $attrData['base_id'];
                    $relationTmp['user_assess_status'] = self::AssessCreate;
                    //end --- assess_user_relation�����

                }
            }

        }
    }

    public function setAssessUserRelation($uidArr,$base_id){
        if($uidArr && $base_id){
            $tbl =  "`".DB_PREFIX."assess_user_relation`";
            foreach($uidArr as $uid){
                $tmpArr = array();
                $tmpArr['uid'] = $uid;
                $tmpArr['base_id'] = $base_id;
                $findRecordSql = "select * from {$tbl} where uid = {$uid} and  base_id = {$base_id}";
                if(!$findRecord = $this->db->GetRow($findRecordSql)){
                    $tmpArr['user_assess_status'] = self::AssessCreate;
                    $sql = self::get_insert_sql($tbl,$tmpArr);
                    $this->db->Execute($sql);
                }
            }
        }
    }
    /**
     *  output :
     *   $searchResult
            1.sqlWhere    ����������SQLƴ���ַ���
           2.pageConditionUrl  ����ƴ�ӵ�����URL
     */
    public function getHrSearchHandlerList($tableName){
        $searchResult = array();
        $sqlWhere = '';
        $pageConditionUrl = '';
        if(isset($_REQUEST['base_name']) && $_REQUEST['base_name']){
            $sqlWhere.=" AND $tableName.base_name like '%".$_REQUEST['base_name']."%'";
            $pageConditionUrl.="&base_name=".$_REQUEST['base_name'];
        }

        if(isset($_REQUEST['bus_area_parent']) && $_REQUEST['bus_area_parent']){
            $sqlWhere.=" AND $tableName.bus_area_parent={$_REQUEST['bus_area_parent']} ";
            $pageConditionUrl.="&bus_area_parent=".$_REQUEST['bus_area_parent'];
        }

        if(isset($_REQUEST['bus_area_child']) && $_REQUEST['bus_area_child']){
            $sqlWhere.=" AND $tableName.bus_area_child={$_REQUEST['bus_area_child']} ";
            $pageConditionUrl.="&bus_area_child=".$_REQUEST['bus_area_child'];
        }

        $_REQUEST['base_status'] = (!isset($_REQUEST['base_status']))?'0':$_REQUEST['base_status']; //״̬��ʼĬ��Ϊ0  ������״̬
        if($_REQUEST['base_status']!==''){
            $sqlWhere.=" AND $tableName.base_status={$_REQUEST['base_status']} ";
            $pageConditionUrl.="&base_status=".$_REQUEST['base_status'];
        }

        $_REQUEST['byme_status'] = (!isset($_REQUEST['byme_status']))?1:$_REQUEST['byme_status']; //״̬��ʼĬ��Ϊ1 ���ҷ���
        if($_REQUEST['byme_status']==1){
            $uid = getUserId();
            if($uid){
                $sqlWhere.=" AND $tableName.uid=$uid";
            }
            $pageConditionUrl.="&byme_status=".$_REQUEST['base_status'];
        }

        if(isset($_REQUEST['assess_period_type']) && $_REQUEST['assess_period_type']){
            $sqlWhere.=" AND $tableName.assess_period_type={$_REQUEST['assess_period_type']} ";
            $pageConditionUrl.="&assess_period_type=".$_REQUEST['assess_period_type'];
        }
        $page = isset($_GET['pn']) ? (int)$_GET['pn'] : 1;
        $searchResult['sqlWhere'] = $sqlWhere;
        $searchResult['pageConditionUrl'] = $pageConditionUrl."&pn=".$page;
        return $searchResult;
    }
}