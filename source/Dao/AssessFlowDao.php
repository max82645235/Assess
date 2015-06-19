<?php
/**
* Created by PhpStorm.
* User: Administrator
*  Date: 15-6-19
*  Time: 下午5:03
*  考核流程类
*/
require_once 'BaseDao.php';
class AssessFlowDao extends BaseDao{
    protected $assessDao;
    public function setAssessDao(AssessDao $assessDao){
        $this->assessDao = $assessDao;
    }

    public function waitMeSearchHandlerList($searchParam){
        $searchResult = array();
        $searchParam = $this->filterConditionParam($searchParam,array('byme_status'));

        $hrSearchResult = $this->assessDao->getBaseSearchHandlerList('sa_assess_base',$searchParam);
    }

    public function filterConditionParam($searchParam=array(),$filterParam = array()){
        foreach($searchParam as $key=>$v){
            if($filterParam && in_array($key,$filterParam)){
                unset($searchParam[$key]);
            }
        }
        return $searchParam;
    }


}