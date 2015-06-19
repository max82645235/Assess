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

    static  $attrTypeMaps = array(
    '1'=>'����ָ����',
    '2'=>'����������',
    '3'=>'�����',
    '4'=>'�����'
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


    const HrAssessWait = 0;
    const HrAssessPublish = 1;
    const HrAssessChecking = 2;
    const HrAssessOver = 3;


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
        global $p_gid;
        try{
            $tableSafeAttr = array(
                'base_id','base_name','base_start_date','bus_area_parent','bus_area_child','lead_direct_set_status','lead_plan_end_date','lead_plan_start_date','lead_sub_end_date','lead_sub_start_date','staff_plan_end_date','staff_plan_start_date','staff_sub_end_date','staff_sub_start_date','uid','assess_attr_type','assess_period_type','base_end_date','base_status','userId'
            );
            $tbl = "`".DB_PREFIX."assess_base`";
            $baseRecord['base_end_date'] = self::getAssessBaseEndDate($baseRecord['assess_period_type'],$baseRecord['base_start_date']);
            foreach($baseRecord as $key=>$attr){
                if(!in_array($key,$tableSafeAttr)){
                    unset($baseRecord[$key]);
                }
            }
            $baseRecord['base_status'] = self::HrAssessWait;
            if(mb_detect_encoding($baseRecord['base_name'],'GBK,UTF-8')=='UTF-8'){
                $baseRecord['base_name'] = iconv('UTF-8','GBK',$baseRecord['base_name']);
            }
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
                $baseRecord['uid'] = $p_gid;
                $baseRecord['userId'] = getUserId();
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
                                if(mb_detect_encoding($v,'UTF-8,GBK')=='UTF-8'){
                                    $data['itemData'][$i][$attr] = iconv('UTF-8','GBK',$v);
                                }
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
            foreach($uidArr as $userId){
                $tmpArr = array();
                foreach($attrResult as $k=>$attrData){

                    //assess_user_item����� start---
                    $tbl = "`".DB_PREFIX."assess_user_item`";
                    $tmpArr['userId'] = $userId;
                    $tmpArr['attr_id'] = $attrData['attr_id'];
                    $tmpArr['itemData'] = $attrData['itemData'];
                    $tmpArr['base_id'] = $attrData['base_id'];
                    $tmpArr['item_weight'] = $attrData['weight'];
                    $findRecordSql = "select * from {$tbl} where userId = $userId and  attr_id = {$attrData['attr_id']}";
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
                }
            }

        }
    }

    public function setAssessUserRelation($uidArr,$base_id){
        if($uidArr && $base_id){
            $tbl =  "`".DB_PREFIX."assess_user_relation`";
            foreach($uidArr as $userId){
                $tmpArr = array();
                $tmpArr['userId'] = $userId;
                $tmpArr['base_id'] = $base_id;
                $findRecordSql = "select * from {$tbl} where userId = {$userId} and  base_id = {$base_id}";
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
    public function getHrSearchHandlerList($tableName,$conditionParams){
        $searchResult = array();
        $sqlWhere = '';
        $pageConditionUrl = '';
        if(isset($conditionParams['base_name']) && $conditionParams['base_name']){
            $sqlWhere.=" AND $tableName.base_name like '%".$conditionParams['base_name']."%'";
            $pageConditionUrl.="&base_name=".$conditionParams['base_name'];
        }

        if(isset($conditionParams['bus_area_parent']) && $conditionParams['bus_area_parent']){
            $sqlWhere.=" AND $tableName.bus_area_parent={$conditionParams['bus_area_parent']} ";
            $pageConditionUrl.="&bus_area_parent=".$conditionParams['bus_area_parent'];
        }

        if(isset($conditionParams['bus_area_child']) && $conditionParams['bus_area_child']){
            $sqlWhere.=" AND $tableName.bus_area_child={$conditionParams['bus_area_child']} ";
            $pageConditionUrl.="&bus_area_child=".$conditionParams['bus_area_child'];
        }

        if(isset($conditionParams['base_status'])  && $conditionParams['base_status']!==''){
            $sqlWhere.=" AND $tableName.base_status={$conditionParams['base_status']} ";
            $pageConditionUrl.="&base_status=".$conditionParams['base_status'];
        }

        if(isset($conditionParams['byme_status']) && $conditionParams['byme_status']==1){
            $userId = getUserId();
            $sqlWhere.=" AND $tableName.userId='$userId'";
            $pageConditionUrl.="&byme_status=".$conditionParams['base_status'];
        }

        if(isset($conditionParams['assess_period_type']) && $conditionParams['assess_period_type']){
            $sqlWhere.=" AND $tableName.assess_period_type={$conditionParams['assess_period_type']} ";
            $pageConditionUrl.="&assess_period_type=".$conditionParams['assess_period_type'];
        }
        $page = isset($conditionParams['pn']) ? (int)$conditionParams['pn'] : 1;
        $searchResult['sqlWhere'] = $sqlWhere;
        $searchResult['pageConditionUrl'] = $pageConditionUrl."&pn=".$page;
        return $searchResult;
    }

    //ƴ�ӻ�ȡ������ѯ����url
    public function getConditionParamUrl($filterParam = array()){
        $pageUrl = '';
        foreach($_GET as $key=>$v){
            if(!$filterParam ||!in_array($key,$filterParam)){
                $pageUrl .= "{$key}={$v}&";
            }
        }
        return substr($pageUrl,0,-1);
    }

    //���÷���״̬
    public function setAssessPublishStatus($baseId,$userId){
        $tbl = "`".DB_PREFIX."assess_base`";
        $findRecordSql = "select * from {$tbl} where base_id = {$baseId} and userId={$userId}";
        if($findRecord = $this->db->GetRow($findRecordSql)){
            $findRecord['base_status'] = 1;//�ѷ���
            $where = " base_id={$findRecord['base_id']}";
            $sql = self::get_update_sql($tbl,$findRecord,$where);
            $this->db->Execute($sql);
            return true;
        }
    }

    //��¡��������
    public function copyAssessRecord($baseId){
        $findRecordSql = "select * from sa_assess_base where base_id = {$baseId}";
        if($findBaseRecord = $this->db->GetRow($findRecordSql)){
            //��¡����-start
            $unsetBaseAttr = array('base_id','base_status','uid','userId','create_time','update_time','publish_date');
            foreach($findBaseRecord as $k=>$v){
                if(in_array($k,$unsetBaseAttr)){
                    unset($findBaseRecord[$k]);
                }
            }
            $relationRecords = $this->getRelatedUserRecord($baseId);
            $uids = '';
            if($relationRecords){
                foreach($relationRecords as $record){
                    $uids.=$record['userId'].",";
                }
                $uids = substr($uids,0,-1);
            }
            if($base_id = $this->setAssessBaseRecord($findBaseRecord)){
                $findAttrRecordSql = " select * from sa_assess_attr where base_id={$base_id} ";
                $findAttrRecords = $this->db->GetAll($findAttrRecordSql);
                if($findAttrRecords){
                    foreach($findAttrRecords as $k=>$record){
                        $findAttrRecords[$k]['base_id'] = $base_id;
                        unset($findAttrRecords[$k]['attr_id']);
                    }
                    if($attrResult = $this->setAssessAttrRecord($findAttrRecords)){
                        if($findBaseRecord['lead_direct_set_status']==0){//û�й�ѡֱ�����쵼����ʱ
                            $this->setAssessUserItemRecord($uids,$attrResult);
                            $this->setAssessUserRelation($uids,$base_id);
                        }
                    }
                }
            }
            //��¡����-end
        }
    }

    //��ȡbase_id�����
    public function getRelatedUserRecord($base_id){
        $sql = "select * from sa_assess_user_relation where base_id={$$base_id}";
        $findRecords = $this->db->GetAll($sql);
        return $findRecords;
    }

    public function getBusParentDropList(){
        global $cfg;
            $dropList = array();
            $userBusId = getUserBusId();//����id
            $isRoot = getIsRootGroup();
            foreach($cfg['tixi'] as $k=>$v){
                if($isRoot ||  $userBusId==$k){
                    $dropList[$k] = $v['title'];
                }
        }
        return $dropList;
    }

    //׷���û�һ������Sql��������
    static  function addBusParentAuthValidSql($table,$table_attr = 'bus_area_parent'){
        if(!getIsRootGroup()){
            $userBusId = getUserBusId();//����id
            return "AND ".$table.".".$table_attr="$userBusId ";
        }
    }
}