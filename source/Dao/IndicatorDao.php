<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 15-6-16
 * Time: ����3:32
 */
require_once 'BaseDao.php';
class IndicatorDao extends BaseDao{
    public function getAllTypeList(){
        $retList = array();
        $sql = "select * from sa_indicator_type where status=1";
        $list = $this->db->GetAll($sql);
        if($list){
            $retList[0] = array('id'=>'','name'=>'��ѡ��');
            foreach($list as $k=>$v){
                $retList[] = array('id'=>$v['typeid'],'name'=>$v['type']);
            }
        }
        return $retList;
    }

    public function getIndicatorChildList($typeId){
        $sql = "select id as childId,title from sa_indicator  where typeid={$typeId} and status=1";
        $list = $this->db->GetAll($sql);
        if($list){
            foreach($list as $k=>$v){
                $list[$k]['title'] = iconv('GBK','UTF-8',$v['title']);
            }
        }
        return $list;
    }

    public function getSingleType($typeId){
        $sql = "select * from sa_indicator_type where status=1 and typeid={$typeId}";
        $record = $this->db->GetRow($sql);
        return $record;
    }

    public function getSingleIndicatorChild($id){
        $sql = "select * from sa_indicator  where id={$id} and status=1";
        $record = $this->db->GetRow($sql);
        return $record;
    }
}