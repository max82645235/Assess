<div class="freeFlowArea">
    <?php if($freeFlowList){?>
        <table cellpadding="0" cellspacing="0" width="100%" class="jbtab">
            <tbody>
                <tr>
                    <td>��Ų���</td>
                    <td>�����</td>
                    <td>����</td>
                    <td>ʱ��</td>
                    <td>ǩ�췴�����</td>
                </tr>
                <?php foreach($freeFlowList as $key=>$data){?>
                    <tr>
                        <td>��<font style="color: red;"><?=($key+1)?></font>��</td>
                        <td><?=$data['username']."(".$data['card_no'].")"?></td>
                        <td><?=$data['deptlist']?></td>
                        <td>
                            ��ʼ�ڣ�<?=$data['create_time']?></br>
                            ����������ڣ�<?=($data['over_time']!='0000-00-00 00:00:00')?$data['over_time']:'�����...';?>
                        </td>
                        <td>
                            <span><?=($data['description'])?$data['description']:'-'?></span>
                        </td>
                    </tr>
                <?php }?>
            </tbody>
        </table>
    <?php }?>
</div>