<?php $totalScore = 0;?>
<?php if($assessAttrType==1){?>
    <?php
        require_once BATH_PATH.'source/Dao/IndicatorDao.php';
        $ind_dao = new IndicatorDao();
    ?>
    <table cellpadding="0" cellspacing="0" width="100%" class="jbtab">
        <tr>
            <th width="20%">��������</th>
            <th width="30%">������</th>
            <th width="10%">Ȩ��</th>
            <th width="15%">������</th>
            <th width="15%">ʵ�ʵ÷�</th>
            <th width="20%">���ܵ÷�</th>
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
                        <td <?=$widget->getDifferShow(1,array('index'=>$key,'attr'=>'indicator_parent'));?>>
                            <?php
                            $pInfo = $ind_dao->getSingleType($data['indicator_parent']);
                            $cInfo = $ind_dao->getSingleIndicatorChild($data['indicator_child']);
                            $indicatorInfo = $pInfo['type']."-".$cInfo['title'];
                            echo $indicatorInfo;
                            ?>
                        </td>
                        <td <?=$widget->getDifferShow(1,array('index'=>$key,'attr'=>'qz'));?>><?=($data['qz'])?$data['qz'].'%':'';?></td>
                        <td ><?=$data['selfScore']?></td>
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
            <td colspan="5">�ϼƷ�</td>
            <td><?=(intval($totalScore))?intval($totalScore):'';?></td>
        </tr>
    </table>
<?php }elseif($assessAttrType==2){?>

    <?php $itemList = unserialize($itemInfo[0]['itemData']);?>
    <table cellpadding="0" cellspacing="0" width="100%" class="jbtab">
        <tr>
            <th width="15%">��������</th>
            <th width="15%">��ֵ���ת����(Ԫ/��)</th>
            <th width="20%">������</th>
            <th width="15%">������</th>
            <th width="15%">ʵ�ʵ÷�</th>
            <th width="20%">���ܵ÷�</th>
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
            <td colspan="5">�ϼƷ�</td>
            <td><?=(intval($totalScore))?intval($totalScore):'';?></td>
        </tr>
    </table>
<?php }elseif($assessAttrType==3){?>
    <?php $itemList = unserialize($itemInfo[0]['itemData']);?>
    <table cellpadding="0" cellspacing="0" width="100%" class="jbtab">
        <tr>
            <th width="20%">��������</th>
            <th width="10%">��ɵ�</th>
            <th width="30%">��ɽ��</th>
            <th width="40%">�ϼ�</th>
        </tr>
        <tr  <?=$widget->getDifferShow(4);?>>
            <td>����� </td>
            <td <?=$widget->getDifferShow(4,array('index'=>0,'attr'=>'tc_name'));?>><?=$itemList[0]['tc_name']?></td>
            <td ><?=$itemList[0]['finishCash']?></td>
            <td><?=($itemList[0]['tc_name'] &&$itemList[0]['finishCash'])?$itemList[0]['tc_name']*$itemList[0]['finishCash']:''?></td>
        </tr>
    </table>

<?php }?>




