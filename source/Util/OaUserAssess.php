<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 15-8-3
 * Time: 下午1:58
 */
class OaUserAssess{
    public $db;
    protected $assessInfo;
    protected $uid;
    protected $flowDao;
    protected $userRecord;
    protected $errorMsg;

    public function __construct($uid,AssessFlowDao $flowDao){
        //数据库初始化
        global $db;
        global $ADODB_FETCH_MODE;
        $this->db = $db;
        $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;//只查询关联索引结果
        $this->uid = $uid;
        $this->flowDao = $flowDao;
        $this->init();
    }

    protected function init(){
        $sql = "select * from sa_user where uid='".$this->uid."'";
        $this->userRecord = $this->db->GetRow($sql);
        if(!$this->userRecord){
            $this->addErrorMsg('用户uid不存在');
        }
    }

    protected function addErrorMsg($msg){
        $this->errorMsg[] = $msg;
    }

    protected function getErrorMsg(){
        return $this->errorMsg;
    }

    public function getAssessInfo(){
        if(!$this->getErrorMsg()){
            $this->assessInfo['staff'] = $this->getStaffAssessInfo();
            $this->assessInfo['lead'] = $this->getLeadAssessInfo();
        }else{
            $this->assessInfo['errorMsg'] = $this->getErrorMsg();
        }

        return $this->assessInfo;
    }

    //获取uid身为员工的考核信息项
    public function getStaffAssessInfo(){
        $userId = $this->userRecord['userId'];
        $assessStatusMapIn = '1,4'; //待我填写计划 ，待我自评
        $sql = "select a.user_assess_status,a.rejectStatus,a.base_id,a.userId,b.assess_year,b.assess_month,b.base_name from sa_assess_user_relation as a
                inner join sa_assess_base as b on a.base_id=b.base_id and a.userId={$userId} and a.user_assess_status in ($assessStatusMapIn)
                group by a.base_id order by a.updateTime desc";
        $res = $this->db->getAll($sql);
        $cnRes = array();
        if($res){
            foreach($res as $key=>$data){
                $cnRes[$key]['baseName'] = "(".$data['assess_year']."-".$data['assess_month'].")".$data['base_name'];
                $cnRes[$key]['assessStatusCn'] = AssessFlowDao::$UserAssessStatusByStaff[$data['user_assess_status']];
                $cnRes[$key]['link'] = "?m=myassessment&a=myAssess&act=myAssessList&user_assess_status={$data['user_assess_status']}&base_name=".urlencode($data['base_name']);
                if($data['rejectStatus']>0){
                    //增加驳回中文状态
                    $cnRes[$key]['baseName'] = $cnRes[$key]['baseName']."[".AssessFlowDao::$rejectTextMapsForStaff[$data['rejectStatus']]."]";
                }
            }
        }
        return $cnRes;
    }

    //获取uid身为领导的考核信息项
    public function getLeadAssessInfo(){
        $leadInfo = array();
        //获取领导下属userIds
        $userId = $this->userRecord['userId'];
        $sql = "select low_userId from sa_user_relation where super_userId={$userId} and status=1";
        $lowListRes = $this->db->getAll($sql);
        $lowList = array();
        foreach($lowListRes as $data){
            $lowList[] = $data['low_userId'];
        }
        $userIdInCond = implode(',',$lowList);
        $assessStatusMapIn = '0,2,5'; //待我创建计划 ，待我审核计划,待我评估确认
        $sql = "select a.userId,b.username,a.user_assess_status,a.rejectStatus,a.base_Id,c.base_name,c.assess_year,c.assess_month from sa_assess_user_relation as a
                inner join sa_user as b on a.userId=b.userId and a.userId in ({$userIdInCond}) and a.user_assess_status in ({$assessStatusMapIn}) inner join sa_assess_base as c on a.base_id=c.base_id order by a.base_id desc";
        $relationList = $this->db->getAll($sql);
        foreach($relationList as $relationData){
            $rejectCn = '';
            if($relationData['rejectStatus']>0){
                //增加驳回中文状态
                $rejectCn = "[".AssessFlowDao::$rejectTextMapsForLead[$relationData['rejectStatus']]."]";
            }
            $baseId = $relationData['base_Id'];
            $leadInfo[$baseId][] = array(
                'baseName'=>"(".$relationData['assess_year']."-".$relationData['assess_month'].")".$relationData['base_name'].$rejectCn,
                'userName'=>$relationData['username'],
                'assessStatusCn'=> AssessFlowDao::$UserAssessStatusByLeader[$relationData['user_assess_status']],
                'link'=>"?m=myassessment&a=waitMeAssess&act=myStaffList&base_id={$baseId}&username=".urlencode($relationData['username'])
            );

        }

        return $leadInfo;
    }
}
