<?php $totalScore = 0;?>
<?php if($assessAttrType==1){?>
    <?php
        require_once BATH_PATH.'source/Dao/IndicatorDao.php';
        $ind_dao = new IndicatorDao();
    ?>
    <table cellpadding="0" cellspacing="0" width="100%" class="jbtab">
        <tr>
            <th width="15%">考核类型</th>
            <th width="20%">考核项</th>
            <th width="10%">权重</th>
            <th width="10%">自评分</th>
            <th width="25%">自我评价</th>
            <th width="10%">实际评分</th>
            <th width="15%">汇总评分</th>
        </tr>
        <?php foreach($itemInfo as $item){?>
            <?php $itemList = unserialize($item['itemData']);?>
            <?php if($item['attr_type']==1){?>
                <?php foreach($itemList as $key=>$data){?>
                    <tr <?=$widget->getDifferShow(1);?>>
                         <?php if($key==0){?>
                            <td rowspan="<?=count($itemList)?>"
                                <?=$widget->getDifferShow(1,array('attr'=>'attr_type'),false);?>
                            >
                                <?=($key==0)?AssessDao::$attrTypeMaps[$item['attr_type']]:'';?>
                            </td>
                         <?php }?>
                        <?php
                            $pStyle = $widget->getDifferShow(1,array('index'=>$key,'attr'=>'indicator_parent'));
                            if(!$pStyle){
                                $pStyle = $widget->getDifferShow(1,array('index'=>$key,'attr'=>'indicator_child'));
                            }
                        ?>
                        <td <?=$pStyle;?>>
                            <?php
                            $pInfo = $ind_dao->getSingleType($data['indicator_parent']);
                            $cInfo = $ind_dao->getSingleIndicatorChild($data['indicator_child']);
                            $indicatorInfo = $pInfo['type']."-".$cInfo['title'];
                            echo $indicatorInfo;
                            ?>
                        </td>
                        <td <?=$widget->getDifferShow(1,array('index'=>$key,'attr'=>'qz'));?>><?=($data['qz'])?$data['qz'].'%':'';?></td>
                        <td ><?=$data['selfScore']?></td>
                        <td class="left"><?=($data['selfAssess'])?$data['selfAssess']:'';?></td>
                        <td><?=($data['leadScore'])?$data['leadScore']:'';?></td>
                        <td>
                            <?php
                                if($data['qz'] && $data['leadScore']){
                                    $t = $data['qz']*$data['leadScore']/100;
                                    $totalScore+=$t;
                                    echo intval($t);
                                }

                            ?>
                        </td>
                    </tr>
                <?php }?>
            <?php }elseif($item['attr_type']==2){?>
                <?php foreach($itemList as $key=>$data){?>
                    <tr <?=$widget->getDifferShow(2);?>>
                        <?php if($key==0){?>
                            <td rowspan="<?=count($itemList)?>"
                                <?=$widget->getDifferShow(2,array('attr'=>'attr_type'),false);?>
                            >
                                <?=($key==0)?AssessDao::$attrTypeMaps[$item['attr_type']]:'';?>
                            </td>
                        <?php }?>
                        <td <?=$widget->getDifferShow(2,array('index'=>$key,'attr'=>'job_name'));?>><?=$data['job_name']?></td>
                        <td <?=$widget->getDifferShow(2,array('index'=>$key,'attr'=>'qz'));?>><?=($data['qz'])?$data['qz'].'%':'';?></td>
                        <td ><?=$data['selfScore']?></td>
                        <td class="left"><?=($data['selfAssess'])?$data['selfAssess']:'';?></td>
                        <td><?=($data['leadScore'])?$data['leadScore']:'';?></td>
                        <td>
                            <?php
                                 if($data['qz'] && $data['leadScore']){
                                    $t = $data['qz']*$data['leadScore']/100;
                                    $totalScore+=$t;
                                    echo intval($t);
                                 }
                            ?></td>
                    </tr>
                <?php }?>
            <?php }?>
        <?php }?>
        <tr>
            <td colspan="6">合计分</td>
            <td><?=(intval($totalScore))?intval($totalScore):'';?></td>
        </tr>
    </table>
<?php }elseif($assessAttrType==2){?>

    <?php $itemList = unserialize($itemInfo[0]['itemData']);?>
    <table cellpadding="0" cellspacing="0" width="100%" class="jbtab">
        <tr>
            <th width="15%">考核类型</th>
            <th width="15%">分值金额转化率(元/分)</th>
            <th width="15%">考核项</th>
            <th width="10%">自评分</th>
            <th width="15%">自我评价</th>
            <th width="10%">实际评分</th>
            <th width="15%">汇总评分</th>
        </tr>
        <?php foreach($itemList as $key=>$data){?>
            <tr <?=$widget->getDifferShow(3);?>>
                <?php if($key==0){?>
                    <td rowspan="<?=count($itemList)?>"
                        <?=$widget->getDifferShow(3,array('attr'=>'attr_type'),false);?>
                    >
                        <?=($key==0)?AssessDao::$attrTypeMaps[$itemInfo[0]['attr_type']]:'';?>
                    </td>
                <?php }?>

                <?php if($key==0){?>
                     <td rowspan="<?=count($itemList)?>"  <?=$widget->getDifferShow(3,array('attr'=>'cash'),false);?>>
                        <?=($key==0)?$itemInfo[0]['cash']:''?>
                    </td>
                <?php }?>
                <td <?=$widget->getDifferShow(3,array('index'=>$key,'attr'=>'score_name'));?>><?=$data['score_name']?></td>
                <td ><?=$data['selfScore']?></td>
                <td class="left"><?=($data['selfAssess'])?$data['selfAssess']:'';?></td>
                <td><?=$data['leadScore']?></td>
                <td>
                    <?php
                        if($itemInfo[0]['cash']&& $data['leadScore']){
                            $t = $itemInfo[0]['cash']*$data['leadScore'];
                            $totalScore+=$t;
                            echo intval($t);
                        }
                    ?></td>
            </tr>
        <?php }?>
        <tr>
            <td colspan="6">合计分</td>
            <td><?=(intval($totalScore))?intval($totalScore):'';?></td>
        </tr>
    </table>
<?php }elseif($assessAttrType==3){?>
    <?php $itemList = unserialize($itemInfo[0]['itemData']);?>
    <table cellpadding="0" cellspacing="0" width="100%" class="jbtab">
        <tr>
            <th width="20%">考核类型</th>
            <th width="10%">提成点</th>
            <th width="20%">完成金额</th>
            <th width="30%">自我评价</th>
            <th width="20%">合计</th>
        </tr>
        <tr  <?=$widget->getDifferShow(4);?>>
            <td>提成类 </td>
            <td <?=$widget->getDifferShow(4,array('index'=>0,'attr'=>'tc_name'));?>><?=$itemList[0]['tc_name']?>%</td>
            <td ><?=$itemList[0]['finishCash']?></td>
            <td class="left"><?=($data['selfAssess'])?$data['selfAssess']:'';?></td>
            <td><?=($itemList[0]['tc_name'] &&$itemList[0]['finishCash'])?$itemList[0]['tc_name']*$itemList[0]['finishCash']:''?></td>
        </tr>
    </table>

<?php }?>

<?php $rpData = unserialize($relation['rpData']);?>
<?php if($rpData['itemDataList']){?>
<p class="tjtip">奖惩</p>
<table cellpadding="0" cellspacing="0" width="100%" class="jbtab">
    <tr>
        <th width="20%">奖惩类型</th>
        <th width="50%">说明</th>
        <th width="10%">数值</th>
        <th width="20%">金额/百分比</th>

    </tr>
    <?php foreach($rpData['itemDataList'] as $key=>$itemData){?>
        <tr>
            <td><?=($itemData['rpType']==1)?'奖励':'惩罚';?> </td>
            <td ><?=$itemData['rpIntro']?></td>
            <td><?=($itemData['rpType']==1)?'+':'-';?><?=$itemData['rpUnitValue']?></td>
            <td><?=($itemData['unitType']==1)?'元':'%';?> </td>
        </tr>
    <?php }?>
    <tr>
        <td colspan="2">
            金额合计：
            <?php if($rpData['total'][1]['totalValue']){?>
                 <?=$rpData['total'][1]['totalValue']?>元
            <?php }?>
        </td>
        <td colspan="2">百分比合计：&nbsp;
            <?php if($rpData['total'][2]['totalValue']){?>
            <?=$rpData['total'][2]['totalValue']*100?>%</td>
             <?php }?>
    </tr>
</table>
<?php }?>


