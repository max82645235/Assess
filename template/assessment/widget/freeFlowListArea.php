<div class="freeFlowArea">
    <?php if($freeFlowList){?>
        <table cellpadding="0" cellspacing="0" width="100%" class="jbtab">
            <tbody>
                <tr>
                    <td>序号步骤</td>
                    <td>审核人</td>
                    <td>部门</td>
                    <td>时间</td>
                    <td>签办反馈意见</td>
                </tr>
                <?php foreach($freeFlowList as $key=>$data){?>
                    <tr>
                        <td>第<font style="color: red;"><?=($key+1)?></font>步</td>
                        <td><?=$data['username']."(".$data['card_no'].")"?></td>
                        <td><?=$data['deptlist']?></td>
                        <td>
                            开始于：<?=$data['create_time']?></br>
                            本步骤结束于：<?=($data['over_time']!='0000-00-00 00:00:00')?$data['over_time']:'审核中...';?>
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