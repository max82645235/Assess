<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 15-6-16
 * Time: ÏÂÎç3:32
 */
require_once 'BaseDao.php';
class IndicatorDao extends BaseDao{
    public function getAllTypeList(){
        $retList = array();
        $sql = "select * from sa_indicator_type where status=1";
        $list = $this->db->GetAll($sql);
        if($list){
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
        return$list;
    }
}