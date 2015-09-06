<?php
    $totalScore = 0;
    if(!function_exists('spanFullStr')){
        function spanFullStr($str){
            return "<span class='detailSpan' full=\"".$str."\" style=\"cursor:pointer;\">[�鿴]</span>";
        }
    }

?>
<script>
    $(function(){
        $(".detailSpan").click(function(){
            var fullTxt = $(this).attr('full');
            if(fullTxt){
                art.dialog.alert(fullTxt);
            }
        });
    });
</script>
<?php if($assessAttrType==1){?>
    <?php
        require_once BATH_PATH.'source/Dao/IndicatorDao.php';
        $ind_dao = new IndicatorDao();
    ?>
    <table cellpadding="0" cellspacing="0" width="100%" class="jbtab">
        <tr>
            <th width="8%">��������</th>
            <th width="10%">������</th>
            <th width="8%">��������</th>
            <th width="8%">���۱�׼</th>
            <th width="8%">���ʱ��</th>
            <th width="8%">Ȩ��</th>
            <th width="8%">������Դ</th>
            <th width="8%">������</th>
            <th width="8%">��������</th>
            <th width="8%">�쵼����</th>
            <th width="8%">�ϼ�����</th>
            <th width="8%">��������</th>
        </tr>
        <?php foreach($itemInfo as $item){?>
            <?php $itemList = _unserialize($item['itemData']);?>
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
                        <td <?=$widget->getDifferShow(1,array('index'=>$key,'attr'=>'detailTxt'));?>><?=($data['detailTxt'])?spanFullStr($data['detailTxt']):'';?></td>
                        <td <?=$widget->getDifferShow(1,array('index'=>$key,'attr'=>'assessStad'));?>><?=($data['assessStad'])?spanFullStr($data['assessStad']):'';?></td>
                        <td <?=$widget->getDifferShow(1,array('index'=>$key,'attr'=>'reachTime'));?>><?=($data['reachTime'])?$data['reachTime']:'';?></td>
                        <td <?=$widget->getDifferShow(1,array('index'=>$key,'attr'=>'qz'));?>><?=($data['qz'])?$data['qz'].'%':'';?></td>
                        <td <?=$widget->getDifferShow(1,array('index'=>$key,'attr'=>'sourceData'));?>><?=($data['sourceData'])?$data['sourceData']:'';?></td>

                        <td ><?=$data['selfScore']?></td>
                        <td class="left"><?=($data['selfAssess'])?spanFullStr($data['selfAssess']):'';?></td>
                        <td><?=($data['leadScore'])?$data['leadScore']:'';?></td>
                        <td class="left"><?=($data['leadAssess'])?spanFullStr($data['leadAssess']):'';?></td>
                        <td>
                            <?php
                                if($data['qz'] && $data['leadScore']){
                                    $t = $data['qz']*$data['leadScore']/100;
                                    $totalScore+=$t;
                                    echo ceil($t);
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
                        <td <?=$widget->getDifferShow(2,array('index'=>$key,'attr'=>'detailTxt'));?>><?=($data['detailTxt'])?spanFullStr($data['detailTxt']):'';?></td>
                        <td <?=$widget->getDifferShow(2,array('index'=>$key,'attr'=>'assessStad'));?>><?=($data['assessStad'])?spanFullStr($data['assessStad']):'';?></td>
                        <td <?=$widget->getDifferShow(2,array('index'=>$key,'attr'=>'reachTime'));?>><?=($data['reachTime'])?$data['reachTime']:'';?></td>
                        <td <?=$widget->getDifferShow(2,array('index'=>$key,'attr'=>'qz'));?>><?=($data['qz'])?$data['qz'].'%':'';?></td>
                        <td <?=$widget->getDifferShow(2,array('index'=>$key,'attr'=>'sourceData'));?>><?=($data['sourceData'])?$data['sourceData']:'';?></td>
                        <td ><?=$data['selfScore']?></td>
                        <td class="left"><?=($data['selfAssess'])?spanFullStr($data['selfAssess']):'';?></td>
                        <td><?=($data['leadScore'])?$data['leadScore']:'';?></td>
                        <td class="left"><?=($data['leadAssess'])?spanFullStr($data['leadAssess']):'';?></td>
                        <td>
                            <?php
                                 if($data['qz'] && $data['leadScore']){
                                    $t = $data['qz']*$data['leadScore']/100;
                                    $totalScore+=$t;
                                    echo ceil($t);
                                 }
                            ?></td>
                    </tr>
                <?php }?>
            <?php }?>
        <?php }?>
        <tr>
            <td colspan="11">�ϼƷ�</td>
            <td><?=(ceil($totalScore))?ceil($totalScore):'';?></td>
        </tr>
    </table>
<?php }elseif($assessAttrType==2){?>

    <?php $itemList = _unserialize($itemInfo[0]['itemData']);?>
    <table cellpadding="0" cellspacing="0" width="100%" class="jbtab a">
        <tr>
            <th width="15%">��������</th>
            <th width="15%">��ֵ���ת����(Ԫ/��)</th>
            <th width="15%">������</th>
            <th width="10%">������</th>
            <th width="15%">��������</th>
            <th width="10%">�쵼����</th>
            <th width="15%">��������</th>
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
                            echo ceil($t);
                        }
                    ?></td>
            </tr>
        <?php }?>
        <tr>
            <td colspan="6">�ϼƷ�</td>
            <td><?=(ceil($totalScore))?ceil($totalScore):'';?></td>
        </tr>
    </table>
<?php }elseif($assessAttrType==3){?>
    <?php $itemList = unserialize($itemInfo[0]['itemData']);?>
    <table cellpadding="0" cellspacing="0" width="100%" class="jbtab">
        <tr>
            <th width="20%">��������</th>
            <th width="10%">��ɵ�</th>
            <th width="20%">��ɽ��</th>
            <th width="30%">��������</th>
            <th width="20%">�ϼ�</th>
        </tr>
        <tr  <?=$widget->getDifferShow(4);?>>
            <td>����� </td>
            <td <?=$widget->getDifferShow(4,array('index'=>0,'attr'=>'tc_name'));?>><?=$itemList[0]['tc_name']?>%</td>
            <td ><?=$itemList[0]['finishCash']?></td>
            <td class="left"><?=($data['selfAssess'])?$data['selfAssess']:'';?></td>
            <td><?=($itemList[0]['tc_name'] &&$itemList[0]['finishCash'])?$itemList[0]['tc_name']*$itemList[0]['finishCash']/100:''?></td>
        </tr>
    </table>

<?php }?>

<?php $rpData = _unserialize($relation['rpData']);?>
<?php if($rpData['itemDataList']){?>
<p class="tjtip">����</p>
<table cellpadding="0" cellspacing="0" width="100%" class="jbtab">
    <tr>
        <th width="20%">��������</th>
        <th width="50%">˵��</th>
        <th width="10%">��ֵ</th>
        <th width="20%">���/�ٷֱ�</th>

    </tr>
    <?php foreach($rpData['itemDataList'] as $key=>$itemData){?>
        <tr>
            <td><?=($itemData['rpType']==1)?'����':'�ͷ�';?> </td>
            <td ><?=$itemData['rpIntro']?></td>
            <td ><?=($itemData['rpType']==1)?'<span style="color: red;">+</span>':'<span style="color: green;">-</span>';?><?=$itemData['rpUnitValue']?></td>
            <td><?=($itemData['unitType']==1)?'Ԫ':'%';?> </td>
        </tr>
    <?php }?>
    <tr>
        <td colspan="2">
            ���ϼƣ�
            <?php if($rpData['total'][1]['totalValue']){?>
                 <?=$rpData['total'][1]['totalValue']?>Ԫ
            <?php }?>
        </td>
        <td colspan="2">�ٷֱȺϼƣ�&nbsp;
            <?php if($rpData['total'][2]['totalValue']){?>
            <?=$rpData['total'][2]['totalValue']*100?>%</td>
             <?php }?>
    </tr>
</table>
<?php }?>


