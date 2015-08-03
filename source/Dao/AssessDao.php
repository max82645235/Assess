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
        '3'=>'�ᱨ��',
        '4'=>'������'
    );


    const HrAssessWait = 0;
    const HrAssessPublish = 1;
    const HrAssessChecking = 2;
    const HrAssessSubbing = 3;
    const HrAssessOver = 4;



    public function getAssessBaseRecord($base_id){
        //��ȡ�����������Ϣ
        $base_sql = "select * from sa_assess_base where base_id={$base_id} ";
        $base_info = $this->db->GetRow($base_sql);
        return $base_info;
    }

    //����base_id��ȡ���������Ϣ
    public function getAssessRecordInfo($base_id){
        $record_info = array();
        //��ȡ�����������Ϣ
        $base_info = $this->getAssessBaseRecord($base_id);
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
        return date('Y-m-d',strtotime('+'.$periodType.' month',strtotime($startDate))-1);
    }

    protected function getAssessYearMonth($base_start_date){
        if(preg_match("/^[0-9]{4}-[0-9]{1,2}-[0-9]{1,2}$/",$base_start_date)){
            $tmpArr = explode('-',$base_start_date);
            $ret = array();
            if($tmpArr[2]>15){
                $tmpArr[1]++;
                if($tmpArr[1]>12){
                    $tmpArr[0]++;
                    $tmpArr[1] = $tmpArr[1]-12;
                }
            }
            $ret['assess_year'] = $tmpArr[0];
            $ret['assess_month'] = $tmpArr[1];
            return $ret;
        }
    }

    //���ÿ��˻�������Ϣ
    public function setAssessBaseRecord($baseRecord){
        global $p_uid;
        try{
            $tableSafeAttr = array(
                'base_id','base_name','base_start_date','bus_area_parent','bus_area_child','lead_direct_set_status','staff_sub_start_date','uid','assess_attr_type','assess_period_type','base_end_date','base_status','userId','create_on_month_status','assess_year','assess_month'
            );
            $tbl = "`".DB_PREFIX."assess_base`";
            $baseRecord['base_end_date'] = self::getAssessBaseEndDate($baseRecord['assess_period_type'],$baseRecord['base_start_date']);
            $yearMonthArr = $this->getAssessYearMonth($baseRecord['base_start_date']);
            if($yearMonthArr){
                $baseRecord = array_merge($baseRecord,$yearMonthArr);
            }

            foreach($baseRecord as $key=>$attr){
                if(!in_array($key,$tableSafeAttr)){
                    unset($baseRecord[$key]);
                }
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

                $findRecord['update_time'] = date("Y-m-d H:i:s");
                $where = " base_id={$findRecord['base_id']}";
                $sql = self::get_update_sql($tbl,$findRecord,$where);
                $this->db->Execute($sql);
                $base_id = $findRecord['base_id'];
            }else{
                $baseRecord['base_status'] = self::HrAssessWait;
                $baseRecord['create_time'] = date("Y-m-d H:i:s");
                $baseRecord['uid'] = $p_uid;
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
                    'attr_id','base_id','attr_type','itemData','cash'
                );
                foreach($data as $k=>$attr){
                    if(!in_array($k,$tableSafeAttr)){
                        unset($data[$k]);
                    }

                    if($k=='itemData' && is_array($data['itemData'])){
                        foreach($data['itemData'] as $i=>$tt){
                            foreach($tt as $attr=>$v){
                                if(mb_detect_encoding($v)=='UTF-8'){
                                    $data['itemData'][$i][$attr] = iconv('UTF-8','GBK//IGNORE',$v);
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
                    $tmpArr['attr_type'] = $attrData['attr_type'];
                    $tmpArr['itemData'] = $attrData['itemData'];
                    $tmpArr['base_id'] = $attrData['base_id'];
                    $tmpArr['cash'] = $attrData['cash'];
                    $findRecordSql = "select * from {$tbl} where userId = $userId and  base_id={$attrData['base_id']}  and  attr_type = {$attrData['attr_type']}";
                    if($findRecord = $this->db->GetRow($findRecordSql)){
                        foreach($findRecord as $k=>$v){
                            if(isset($tmpArr[$k])){
                                $findRecord[$k] = $tmpArr[$k];
                            }
                        }
                        $where = " item_id = {$findRecord['item_id']}";
                        $sql = self::get_update_sql($tbl,$findRecord,$where);
                       // echo $sql."</br>";
                        $this->db->Execute($sql);
                    }else{
                        $sql = self::get_insert_sql($tbl,$tmpArr);
                      //  echo $sql."</br>";
                        $this->db->Execute($sql);
                    }
                    //end ---assess_user_item�����
                }
            }

        }
    }

    public function getUserRelationRecord($userId,$base_Id){
        $base_sql = "select * from sa_assess_user_relation where base_id={$base_Id} and userId={$userId} ";
        $base_info = $this->db->GetRow($base_sql);
        return $base_info;
    }

    //�����û��������͸��£�ɾ��user_item��ɵĿ�����������
    public function triggerUserNewAttrTypeUpdate($userRelationRecord=array(),$delStatus=false){
        $tbl = "`".DB_PREFIX."assess_user_relation`";
        $where = " rid={$userRelationRecord['rid']}";
        $userRelationRecord['updateTime'] = date("Y-m-d H:i:s");
        $sql = self::get_update_sql($tbl,$userRelationRecord,$where);
        if($userRelationRecord){
            $this->db->Execute($sql);
        }

        if($delStatus){
            $del_attr_sql = "delete from sa_assess_user_item where base_id = {$userRelationRecord['base_id']} and userId={$userRelationRecord['userId']}";
            $this->db->Execute($del_attr_sql);
        }
    }

    public function clearDeleteUser($base_id,$uidArr){
        $tbl =  "`".DB_PREFIX."assess_user_relation`";
        $uids = implode(',',$uidArr);
        $sql = "select count(*) from  {$tbl} where  base_id={$base_id} and userId not in($uids)";
        $rs = $this->db->GetOne($sql);
        if($rs>0){
            $deleteSql = "delete from {$tbl} where base_id={$base_id} and userId not in($uids)";
            $this->db->Execute($deleteSql);
            return true;
        }
    }

    public function clearDeleteUserItem($base_id,$uidArr,$not = true){
        $tbl =  "`".DB_PREFIX."assess_user_item`";
        $uids = implode(',',$uidArr);
        if($not){
            $notSql = 'not';
        }else{
            $notSql = '';
        }
        $deleteSql = "delete from {$tbl} where base_id={$base_id} and userId {$notSql} in($uids)";
        $this->db->Execute($deleteSql);
    }

    public function setAssessUserRelation($uidArr,$baseRecord){
        $base_id = $baseRecord['base_id'];
        if($uidArr && $base_id){
            $tbl =  "`".DB_PREFIX."assess_user_relation`";

            foreach($uidArr as $userId){
                $tmpArr = array();
                $tmpArr['userId'] = $userId;
                $tmpArr['base_id'] = $base_id;
                $tmpArr['assess_attr_type'] = $baseRecord['assess_attr_type'];
                $tmpArr['updateTime'] = date("Y-m-d H:i:s");
                $findRecordSql = "select * from {$tbl} where userId = {$userId} and  base_id = {$base_id}";
                if(!$findRecord = $this->db->GetRow($findRecordSql)){
                    $tmpArr['user_assess_status'] = 0;
                    $sql = self::get_insert_sql($tbl,$tmpArr);
                    $this->db->Execute($sql);
                }else{
                    $where = ' rid='.$findRecord['rid'];
                    $sql = self::get_update_sql($tbl,$tmpArr,$where);
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
    public function getBaseSearchHandlerList($tableName,$conditionParams){
        $searchResult = array();
        $sqlWhere = '';
        $pageConditionUrl = '';
        if(isset($conditionParams['base_name']) && $conditionParams['base_name']){
            $sqlWhere.=" AND $tableName.base_name like '%".$conditionParams['base_name']."%'";
            $pageConditionUrl.="&base_name=".$conditionParams['base_name'];
        }

        if(isset($conditionParams['bus_area_parent']) && $conditionParams['bus_area_parent']){
            if($this->validBusAuth($conditionParams['bus_area_parent'])){
                $sqlWhere.=" AND $tableName.bus_area_parent={$conditionParams['bus_area_parent']} ";
            }else{
                $sqlWhere.=" AND 1=0 ";
            }
            $pageConditionUrl.="&bus_area_parent=".$conditionParams['bus_area_parent'];
        }

        if(isset($conditionParams['bus_area_child']) && $conditionParams['bus_area_child']){
            if(!$conditionParams['bus_area_parent'] || $this->validBusAuth($conditionParams['bus_area_parent'],$conditionParams['bus_area_child'])){
                $sqlWhere.=" AND $tableName.bus_area_child={$conditionParams['bus_area_child']} ";
            }else{
                $sqlWhere.=" AND 1=0 ";
            }
            $pageConditionUrl.="&bus_area_child=".$conditionParams['bus_area_child'];
        }

        if(isset($conditionParams['base_status'])  && $conditionParams['base_status']!==''){
            $sqlWhere.=" AND $tableName.base_status={$conditionParams['base_status']} ";
            $pageConditionUrl.="&base_status=".$conditionParams['base_status'];
        }

        if(isset($conditionParams['byme_status'])){
            if($conditionParams['byme_status']==1){
                $userId = getUserId();
                $sqlWhere.=" AND $tableName.userId='$userId'";
            }
            $pageConditionUrl.="&byme_status=".$conditionParams['byme_status'];
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





    //���÷���״̬
    public function setAssessPublishStatus($baseId,Auth $auth){
        $tbl = "`".DB_PREFIX."assess_base`";
        $findRecordSql = "select * from {$tbl} where base_id = {$baseId}";
        if($findRecord = $this->db->GetRow($findRecordSql)){
            $isMy = $findRecord['userId'] == getUserId();
            if($auth->setIsMy($isMy)->validIsAuth('publishAssess')){
                $findRecord['base_status'] = self::HrAssessPublish;//�ѷ���
                $findRecord['publish_date'] = date("Y-m-d");
                $where = " base_id={$findRecord['base_id']}";
                //����������쵼ֱ�����ã���Ҫ�Ѹ�base_id��Ӧ�Ŀ����˿���״̬��Ϊ������
                if($findRecord['lead_direct_set_status']==0){
                    $findRecord['base_status'] = self::HrAssessChecking;//������
                    $where = " base_id={$baseId} ";
                    $data['user_assess_status']  = 3;
                    $sql = self::get_update_sql(" sa_assess_user_relation ",$data,$where);
                    $this->db->Execute($sql);
                }
                $sql = self::get_update_sql($tbl,$findRecord,$where);
                $this->db->Execute($sql);
                if($findRecord['lead_direct_set_status']==1){
                    //��Ϊ���쵼ֱ�����ã�����ǰУ���� sa_assess_user_item ����Ҫɾ��Hr���ܸı��쵼״̬����²�����item����
                    $sql = "select count(*) from sa_assess_user_item where base_id={$baseId}";
                    $tmpRecord = $this->db->GetOne($sql);
                    if($tmpRecord>0){
                        $delSql = "delete from sa_assess_user_item where base_id={$baseId}";
                        $this->db->GetOne($delSql);
                    }
                }
                return true;
            }
        }
    }

    //��¡��������
    public function copyAssessRecord($baseId,Auth $auth){
        $findRecordSql = "select * from sa_assess_base where base_id = {$baseId}";
        if($findBaseRecord = $this->db->GetRow($findRecordSql)){
            $isMy = $findBaseRecord['userId'] == getUserId();
            if($auth->setIsMy($isMy)->validIsAuth('cloneAssess')){
                //��¡����-start
                $unsetBaseAttr = array('base_id','base_status','uid','userId','create_time','update_time','publish_date');
                foreach($findBaseRecord as $k=>$v){
                    if(in_array($k,$unsetBaseAttr)){
                        unset($findBaseRecord[$k]);
                    }
                }
                $relationRecords = $this->getRelatedUserRecord($baseId);
                $uids = array();
                if($relationRecords){
                    foreach($relationRecords as $record){
                        $uids[] =$record['userId'];
                    }
                }
                if($base_id = $this->setAssessBaseRecord($findBaseRecord)){
                    $baseRecord = $this->getAssessBaseRecord($base_id);
                    $this->setAssessUserRelation($uids,$baseRecord); //���ÿ�����Ա��ϵ��
                    if($findBaseRecord['lead_direct_set_status']==0){ //û�й�ѡֱ�����쵼����ʱ
                        $findAttrRecordSql = " select * from sa_assess_attr where base_id={$baseId} ";
                        $findAttrRecords = $this->db->GetAll($findAttrRecordSql);
                        if($findAttrRecords){
                            foreach($findAttrRecords as $k=>$record){
                                $findAttrRecords[$k]['base_id'] = $base_id;
                                unset($findAttrRecords[$k]['attr_id']);
                            }
                            if($attrResult = $this->setAssessAttrRecord($findAttrRecords)){
                                $this->setAssessUserItemRecord($uids,$attrResult);
                            }
                        }
                    }
                }
                //��¡����-end
            }

        }
    }

    //��ȡbase_id�����
    public function getRelatedUserRecord($base_id){
        $sql = "select a.*,b.username from sa_assess_user_relation as a
                inner  join sa_user as b on a.userId=b.userId
                where a.base_id={$base_id}";
        $findRecords = $this->db->GetAll($sql);
        return $findRecords;
    }



    public function getBusParentDropList(){
        global $cfg,$p_tixi;
        $dropList = array();
        $isRoot = getIsRootGroup();
        foreach($cfg['tixi'] as $k=>$v){
            if($isRoot ||  $k==$p_tixi || $this->validBusAuth($k)){
                $dropList[$k] = $v['title'];
            }
        }
        return $dropList;
    }

    /**
     *  ��֤����Ȩ��
     *  ���룺 int  $parentId  һ������Id (����)
     *        int  $childId  ��������Id (ѡ��)
     *  ��� ��bool $authStatus  ��֤Ȩ��״̬
     * */
    static function validBusAuth($parentId,$childId=''){
        global $p_userinfo;
        $authStatus = false;
        if(getIsRootGroup()){
            return true;
        }
        $tixiAuth = $p_userinfo['tixi_auth'];
        if($p_userinfo['tixi_auth']){
            if(isset($p_userinfo['tixi_auth'][$parentId]) && count($p_userinfo['tixi_auth'][$parentId])>0){
                $authStatus = true;
                if($childId){
                    $authStatus = false;
                    foreach($p_userinfo['tixi_auth'][$parentId] as $k=>$cId){
                        if($childId==$cId){
                            $authStatus = true;
                            break;
                        }
                    }
                }
            }
        }
        return $authStatus;
    }

    public function getTableBaseInfo($base_id,$userId){
        $sql = "select a.user_assess_status,b.* from sa_assess_user_relation a
                inner join sa_assess_base  b on a.base_id=b.base_id
                where a.base_id={$base_id} and a.userId={$userId}";
        //echo $sql."<br/>";
        $baseRecord = $this->db->GetRow($sql);
        if($baseRecord){
            $baseRecord = $baseRecord+$this->getSelectBusName($baseRecord['bus_area_parent'],$baseRecord['bus_area_child']);
        }
        return $baseRecord;
    }

    public function getSelectBusName($parentId,$childId=''){
        global $cfg;
        $selectBusList = array();
        foreach($cfg['tixi'] as $k=>$v){
            if($parentId == $k){
                $selectBusList['bus_area_parent_name'] = $v['title'];
                if($childId){
                    foreach($v['deptlist'] as $i=>$d){
                        if($childId==$i){
                            $selectBusList['bus_area_child_name'] = $d;
                            break;
                        }
                    }
                }
                break;
            }
        }
        return $selectBusList;
    }

    public function getAutoUserList($params){
        $retList = array();
        $username = utfToGbk($params['s']);
        $tixi = $params['pid'];
        $comp_dept = $params['cid'];
        if($username){
            $sql = "select userId,username,city,dept from sa_user where tixi={$tixi} and comp_dept={$comp_dept} and (username like '%{$username}%' or uid like '%{$username}%' )";
            $userList = $this->db->GetAll($sql);
            if($userList){
                foreach($userList as $k=>$data){
                    $retList[$k]['id'] = $data['userId'];
                    $v = $data['city'].'_'."_".$data['dept']."_".$data['username'];
                    $retList[$k]['label'] = $retList[$k]['value'] = iconv('GBK','UTF-8//IGNORE',$v);
                }
            }
        }
        return $retList;
    }

    //�ж��û�����״̬��ȫΪ �������С�ʱ����Ҫ��������
    public function checkAssessAllUserCheckingStatus($base_id){
        $record = $this->getRelatedUserRecord($base_id);
        if($record){
            $allStatus = true;
            foreach($record as $data){
                if($data['user_assess_status']!=3){
                    $allStatus = false;
                    break;
                }
            }
            if($allStatus){
                $updateBaseStatus = self::HrAssessChecking;
                //��base��[�ѷ���]״̬��Ϊ[������]
                $updateSql = "update sa_assess_base set base_status={$updateBaseStatus} where base_id={$base_id} and base_status =1";
                $this->db->Execute($updateSql);
            }
        }
    }

    //�ж��û�����״̬��ȫΪ ���ᱨ״̬������Ҫ��������
    public function checkAssessAllUserSubbingStatus($base_id){
        $record = $this->getRelatedUserRecord($base_id);
        if($record){
            $allStatus = true;
            foreach($record as $data){
                if($data['user_assess_status']!=4){
                    $allStatus = false;
                    break;
                }
            }
            if($allStatus){
                $updateBaseStatus = self::HrAssessSubbing;
                //��base��[������]״̬��Ϊ[�ᱨ��]
                $updateSql = "update sa_assess_base set base_status={$updateBaseStatus} where base_id={$base_id} and base_status =2";
                $this->db->Execute($updateSql);
            }
        }
    }

    //�ж��û�����״̬��ȫΪ �����ͨ����ʱ����Ҫ��������
    public function checkAssessAllUserSuccessStatus($base_id){
        $record = $this->getRelatedUserRecord($base_id);
        if($record){
            $allStatus = true;
            foreach($record as $data){
                if($data['user_assess_status']!=6){
                    $allStatus = false;
                    break;
                }
            }
            if($allStatus){
                $updateBaseStatus = self::HrAssessOver;
                //��base��[�ᱨ��]״̬��Ϊ[���˽���]
                $updateSql = "update sa_assess_base set base_status={$updateBaseStatus} where base_id={$base_id} and base_status =3";
                $this->db->Execute($updateSql);
            }
        }
    }

    //��ȡĳһ��������������Ա��
    public function getStaffListForHrSql($conditionParams = array()){
        $base_Id = $conditionParams['base_id'];
        $curUserId = getUserId();
        $addSql = "";
        $pageConditionUrl = '';
        $resultList = array();
        if(isset($conditionParams['user_assess_status']) && $conditionParams['user_assess_status']!==''){
            $addSql.= "and b.user_assess_status={$conditionParams['user_assess_status']}";//�����û���д״̬����
            $pageConditionUrl.="&user_assess_status={$conditionParams['user_assess_status']}";
        }

        if(isset($conditionParams['username']) && $conditionParams['username']){
            $addSql.= "and a.username like'%{$conditionParams['username']}%'"; //�����û���ģ����ѯ����
            $pageConditionUrl.="&username={$conditionParams['username']}";
        }
        $resultList['staffListSql'] = "select [*] from sa_user as a
                inner join sa_assess_user_relation as b on a.userId=b.userId and b.base_id={$base_Id} $addSql";
        $resultList['pageConditionUrl'] = $pageConditionUrl;
        return $resultList;

    }

    //������ʷ��¼�Ա�
    public function triggerUserItemHistoryWrite($historyData,AssessFlowDao $assessFlowDao){
        if($historyData){
            $base_id = $historyData['relation']['base_id'];
            $userId = $historyData['relation']['userId'];
            $newData = $assessFlowDao->getUserAssessRecord($base_id,$userId);
            $diffData = array();
            $diffData['history'] = serialize($historyData);
            if($historyData['relation']['assess_attr_type']==$newData['relation']['assess_attr_type']){
                $diffData['type_differ'] = 0;
                $diffData['same'] = 1;
                foreach($newData['item'] as $i=>$data){
                    $newItemData = unserialize($data['itemData']);
                    $historyItemData = unserialize($historyData['item'][$i]['itemData']);
                    $itemDiffer = array();
                    foreach($newItemData as $k=>$trList){
                        foreach($trList as $attr=>$d){
                            if(!in_array($attr,array('finishCash','selfScore','selfAssess')) && $historyItemData[$k][$attr]!=$d){
                                $diffData['same'] = 0;
                                $itemDiffer[$k][$attr] = 1;
                            }
                        }
                    }
                    $diffData['compare_data'][$data['attr_type']]['itemData'] = $itemDiffer;
                    if($data['cash'] && $data['cash']!=$historyData['item'][$i]['cash']){
                        $diffData['compare_data'][$data['attr_id']]['cash'] = 1;
                        $diffData['same'] = 0;
                    }
                }
            }else{
                $diffData['type_differ'] = 1;
                $diffData['same'] = 0;
            }
            $newData['relation']['diffData'] = serialize($diffData);
            unset($newData['relation']['username']);
            $this->triggerUserNewAttrTypeUpdate($newData['relation']);
        }
    }

    //��������
    public function plugFileSave($fileList,$rid){
        $record = array();
        $record['rid'] = $rid;
        $record['createTime'] = date("Y-m-d H:i:s");
        $tbl =  "`".DB_PREFIX."upload_file`";
        foreach($fileList as $data){
            $record['filePath'] = $data['url'];
            $record['cName'] =  iconv('utf-8','gbk',$data['cName']);
            $sql = self::get_insert_sql($tbl,$record);
            $this->db->Execute($sql);
        }
    }
}