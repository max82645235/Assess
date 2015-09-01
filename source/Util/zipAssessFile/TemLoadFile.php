<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 15-8-7
* Time: 下午3:54
 * Description: 本类主要是为了给考核相关文件进行下载|生成，并创建临时文件夹，以此配合zip类进行压缩，供用户下载
*/
require_once BATH_PATH."source/Util/AssessExcel.php";
require_once BATH_PATH."source/Dao/AssessFlowDao.php";
class TemLoadFile{
    protected $baseId;
    protected $userList;
    protected $db;
    protected $filePushList;
    protected $baseInfoList;
    protected $curIndex;
    public function __construct($baseId,$userList){
        global $db;
        global $ADODB_FETCH_MODE;
        $this->db = $db;
        $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;//只查询关联索引结果
        $this->curIndex = 1;
    }


    public function setBaseInfo($baseId,$userList){
        $this->baseId = intval($baseId);
        if(!is_array($userList)){
            $userList =(array) $userList;
        }
        $this->userList = $userList;

        $arr = array(
            'baseId'=>$this->baseId,
            'userList'=>$this->userList
        );
        $this->baseInfoList[$this->curIndex]['base'][] = $arr;
    }

    public function run(){
        $this->getRemoteFileList();
        $this->getExcelFileList();
        $this->curIndex++;
    }

    //获取远程文件路径到 filePushList
    protected function getRemoteFileList(){
        //获取当前 baseId下 userList中的所有上传的远程文件
        $sql = "select a.*,b.userId,b.base_id from sa_upload_file as a
                inner join sa_assess_user_relation as b on a.rid=b.rid and b.user_assess_status>=6
                and b.userId in (".implode(',',$this->userList).") and b.base_Id=".$this->baseId;
        $dbRes = $this->db->getAll($sql);
        if($dbRes){
            foreach($dbRes as $data){
                $tmp = array(
                    'encodeFileName'=>AssessExcel::getEncodeFileName($data['userId'],$data['filePath']),//对文件进行加md5重命名
                    'base_id'=>$data['base_id'],
                    'cName'=>'[##]'
                );
                $localPath = $this->LocalFileExist($tmp);
                if(!$localPath['status']){
                    file_put_contents($localPath['path'],file_get_contents($data['filePath']));//把远程文件下载到upload路径下
                }

                $data['cName'] = substr($data['cName'],0,strrpos($data['cName'],'.'));

                $this->filePushList[$this->curIndex][$this->baseId]['remote'][] = array(
                    'cPath'=>$localPath['cPath'],
                    'localPath'=>$localPath['path'],
                    'userId'=>$data['userId'],
                    'cName'=>$data['cName']
                );
            }
        }
    }


    //获取excel路径到 filePushList
    protected function getExcelFileList(){
        $flowInstanceDao = AssessFlowDao::getInstance();
        foreach($this->userList as $userId){
            $fileKey = AssessExcel::getExcelSaltKey($this->baseId,$userId);
            $excelPath = AssessExcel::getEncodeFileName($userId,$fileKey);
            $data = array(
                'encodeFileName'=>$excelPath,
                'base_id'=>$this->baseId,
                'cName'=>'[##]'
            );

            $localPath = $this->LocalFileExist($data);
            if(!$localPath['status']){
                $assessData = $flowInstanceDao->getAssessDataForExcel($this->baseId,$userId);
                AssessExcel::setExcelData($assessData);
                AssessExcel::createExcel($this->baseId,$userId);
            }
            $this->filePushList[$this->curIndex][$this->baseId]['excel'][] = array(
                'cPath'=>$localPath['cPath'],
                'localPath'=>$localPath['path'],
                'userId'=>$userId
            );
        }
    }

    protected function LocalFileExist($data){
        $baseId = $data['base_id'];
        $dirPath = BATH_PATH.'upload/'.$baseId;
        if(!is_dir($dirPath)){
            mkdir($dirPath);
        }

        $fileArr = explode('.',$data['encodeFileName']);
        $suffix = $fileArr[count($fileArr)-1];

        $retArr = array();
        $retArr['path'] = $dirPath."/".$data['encodeFileName'];
        $retArr['cPath'] = $dirPath."/".$data['cName'].".".$suffix;
        if(file_exists($dirPath."/".$data['encodeFileName'])){
            $retArr['status'] = true;
        }else{
            $retArr['status'] = false;
        }

        return $retArr;

    }

    public function createTmpDir(){
        $time = time();
        $dirPath = BATH_PATH."tmp/".$time.rand(0,1000);
        mkdir($dirPath);
        for($i=1;$i<$this->curIndex;$i++){
            $this->getCnReflect($i);
            $refList = $this->baseInfoList[$i]['ref'];
            foreach($this->baseInfoList[$i]['base'] as $info){
                $curBaseDir = $dirPath."/".$refList['assess'][$info['baseId']]."_".$info['baseId'];
                if(!is_dir($curBaseDir)){
                    mkdir($curBaseDir);
                }
                foreach($this->filePushList[$i][$info['baseId']] as $k=>$data){
                    foreach($data as $ii=>$d){
                        //更换excel中文文件名
                        if($k=='excel'){
                            $d['cPath'] = str_replace("[##]",$refList['assess'][$info['baseId']]."_".$refList['user'][$d['userId']],$d['cPath']);
                        }else{
                            $d['cPath'] = str_replace("[##]",$refList['user'][$d['userId']]."_".$d['cName'],$d['cPath']);
                        }
                        $tmpPath = explode('/',$d['cPath']);
                        $d['cPath'] = $curBaseDir."/".$tmpPath[count($tmpPath)-1];
                        copy($d['localPath'],$d['cPath']);
                    }
                }
            }
        }
       return $dirPath;
    }

    //获取baseId 和userId对应中文映射
   protected function getCnReflect($curIndex){
        $userList = array();
        $baseIdList = array();
        foreach($this->baseInfoList[$curIndex]['base'] as $info){
            $baseIdList[] = $info['baseId'];
            $userList = array_merge($userList,$info['userList']);
        }

        $baseIdList = array_unique($baseIdList);
        $userList = array_unique($userList);

       $sql = "select base_name,base_id from sa_assess_base where base_Id in (".implode(',',$baseIdList).")";
       $dbRow = $this->db->GetAll($sql);
       if($dbRow){
           foreach($dbRow as $d){
               $this->baseInfoList[$curIndex]['ref']['assess'][$d['base_id']] = $d['base_name'];
           }
       }

       $sql = "select username,userId from sa_user where userId in (".implode(',',$userList).")";
       $dbRow = $this->db->GetAll($sql);
       if($dbRow){
           foreach($dbRow as $d){
               $this->baseInfoList[$curIndex]['ref']['user'][$d['userId']] = $d['username'];
           }
       }
   }

}