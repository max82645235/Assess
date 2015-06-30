<?php $totalScore = 0;?>
<?php if($assessAttrType==1){?>
    <?php
        require_once BATH_PATH.'source/Dao/IndicatorDao.php';
        $ind_dao = new IndicatorDao();
    ?>
    <table cellpadding="0" cellspacing="0" width="100%" class="jbtab">
        <tr>
            <th width="40%">考核类型</th>
            <th>考核项</th>
            <th>权重</th>
            <th>自评分</th>
            <th>实际得分</th>
            <th>汇总得分</th>
        </tr>
        <?php foreach($itemInfo as $item){?>
            <?php $itemList = unserialize($item['itemData']);?>
            <?php if($item['attr_type']==1){?>
                <?php foreach($itemList as $key=>$data){?>
                    <tr>
                         <?php if($key==0){?>
                            <td rowspan="<?=count($itemList)?>">
                                <?=($key==0)?AssessDao::$attrTypeMaps[$item['attr_type']]:'';?>
                            </td>
                         <?php }?>
                        <td>
                            <?php
                            $pInfo = $ind_dao->getSingleType($data['indicator_parent']);
                            $cInfo = $ind_dao->getSingleIndicatorChild($data['indicator_child']);
                            $indicatorInfo = $pInfo['type']."-".$cInfo['title'];
                            echo $indicatorInfo;
                            ?>
                        </td>
                        <td><?=($data['qz'])?$data['qz'].'%':'';?></td>
                        <td><?=$data['selfScore']?></td>
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
                    <tr>
                        <?php if($key==0){?>
                            <td rowspan="<?=count($itemList)?>">
                                <?=($key==0)?AssessDao::$attrTypeMaps[$item['attr_type']]:'';?>
                            </td>
                        <?php }?>
                        <td><?=$data['job_name']?></td>
                        <td><?=($data['qz'])?$data['qz'].'%':'';?></td>
                        <td><?=$data['selfScore']?></td>
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
            <td colspan="5">合计分</td>
            <td><?=(intval($totalScore))?intval($totalScore):'';?></td>
        </tr>
    </table>
<?php }elseif($assessAttrType==2){?>

    <?php $itemList = unserialize($itemInfo[0]['itemData']);?>
    <table cellpadding="0" cellspacing="0" width="100%" class="jbtab">
        <tr>
            <th>考核类型</th>
            <th width="15%">分值金额转化率(元/分)</th>
            <th>考核项</th>
            <th>自评分</th>
            <th>实际得分</th>
            <th>汇总得分</th>
        </tr>
        <?php foreach($itemList as $key=>$data){?>
            <tr>
                <?php if($key==0){?>
                    <td rowspan="<?=count($itemList)?>">
                        <?=($key==0)?AssessDao::$attrTypeMaps[$itemInfo[0]['attr_type']]:'';?>
                    </td>
                <?php }?>

                <?php if($key==0){?>
                     <td    <td rowspan="<?=count($itemList)?>">
                        <?=($key==0)?$itemInfo[0]['cash']:''?>
                    </td>
                <?php }?>
                <td><?=$data['score_name']?></td>
                <td><?=$data['selfScore']?></td>
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
            <td colspan="5">合计分</td>
            <td><?=(intval($totalScore))?intval($totalScore):'';?></td>
        </tr>
    </table>
<?php }elseif($assessAttrType==3){?>
    <?php $itemList = unserialize($itemInfo[0]['itemData']);?>
    <table cellpadding="0" cellspacing="0" width="100%" class="jbtab">
        <tr>
            <th>考核类型</th>
            <th>提成点</th>
            <th>完成金额</th>
            <th>合计</th>
        </tr>
        <tr>
            <td>提成类 </td>
            <td><?=$itemList[0]['tc_name']?></td>
            <td><?=$itemList[0]['finishCash']?></td>
            <td><?=($itemList[0]['tc_name'] &&$itemList[0]['finishCash'])?$itemList[0]['tc_name']*$itemList[0]['finishCash']:''?></td>
        </tr>
    </table>

<?php }?>




