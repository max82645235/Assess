<?php foreach($itemInfo as $item){?>
    <p class="tjtip"><?=AssessDao::$attrTypeMaps[$item['attr_type']]?></p>
    <?php $itemList = unserialize($item['itemData'])?>
    <?php if($item['attr_type']==1){
        require_once BATH_PATH.'source/Dao/IndicatorDao.php';
        $ind_dao = new IndicatorDao();
        ?>
        <table cellpadding="0" cellspacing="0" width="100%" class="jbtab">
            <tr>
                <th width="40%">����ָ�����</th>
                <th>Ȩ��(%)</th>
                <th>������</th>
                <th>ʵ�ʵ÷�</th>
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
                <th width="40%">������������</th>
                <th>Ȩ��(%)</th>
                <th>������</th>
                <th>ʵ�ʵ÷�</th>
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
                <th width="40%">��ֵ���ת����</th>
                <th>������</th>
                <th>������</th>
                <th>ʵ�ʵ÷�</th>
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
                <th>��ɵ�</th>
                <th>��ɽ��</th>
            </tr>
            <tr>
                <td><?=$itemList[0]['tc_name']?></td>
                <td><?=$itemList[0]['finishCash']?></td>
            </tr>
        </table>
    <?php }else{}?>
<?php }?>



