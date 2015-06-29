<?php foreach($itemInfo as $item){?>
    <p class="tjtip"><?=AssessDao::$attrTypeMaps[$item['attr_type']]?></p>
    <?php $itemList = unserialize($item['itemData'])?>
    <?php if($item['attr_type']==1){
        require_once BATH_PATH.'source/Dao/IndicatorDao.php';
        $ind_dao = new IndicatorDao();
        ?>
        <table cellpadding="0" cellspacing="0" width="100%" class="jbtab">
            <tr>
                <th width="40%">量化指标分类</th>
                <th>权重(%)</th>
                <th>自评分</th>
                <th>实际得分</th>
            </tr>
            <?php foreach($itemList as $data){?>
                <tr>
                    <td>
                        <?php
                        $pInfo = $ind_dao->getSingleType($data['indicator_parent']);
                        $cInfo = $ind_dao->getSingleIndicatorChild($data['indicator_child']);
                        $indicatorInfo = $pInfo['type']."-".$cInfo['title'];
                        echo $indicatorInfo;
                        ?>
                    </td>
                    <td><?=$data['qz']?></td>
                    <td><?=$data['selfScore']?></td>
                    <td><?=$data['leadScore']?></td>
                </tr>
            <?php }?>
        </table>
    <?php }elseif($item['attr_type']==2){?>
        <table cellpadding="0" cellspacing="0" width="100%" class="jbtab">
            <tr>
                <th width="40%">工作任务名称</th>
                <th>权重(%)</th>
                <th>自评分</th>
                <th>实际得分</th>
            </tr>
            <?php foreach($itemList as $data){?>
                <tr>
                    <td><?=$data['job_name']?></td>
                    <td><?=$data['qz']?></td>
                    <td><?=$data['selfScore']?></td>
                    <td><?=$data['leadScore']?></td>
                </tr>
            <?php }?>
        </table>
    <?php }elseif($item['attr_type']==3){?>
        <table cellpadding="0" cellspacing="0" width="100%" class="jbtab">
            <tr>
                <th width="40%">分值金额转化率</th>
                <th>考核项</th>
                <th>自评分</th>
                <th>实际得分</th>
            </tr>
            <?php foreach($itemList as $data){?>
                <tr>
                    <td width="40%"><?=$item['cash']?></td>
                    <td><?=$data['score_name']?></td>
                    <td><?=$data['selfScore']?></td>
                    <td><?=$data['leadScore']?></td>
                </tr>
            <?php }?>
        </table>
    <?php }elseif($item['attr_type']==4){?>
        <table cellpadding="0" cellspacing="0" width="100%" class="jbtab">
            <tr>
                <th>提成点</th>
                <th>完成金额</th>
            </tr>
            <tr>
                <td><?=$itemList[0]['tc_name']?></td>
                <td><?=$itemList[0]['finishCash']?></td>
            </tr>
        </table>
    <?php }else{}?>
<?php }?>



