<?php if($plupFileList){?>
    <p class="tjtip">�ļ�������</p>
    <table cellpadding="0" cellspacing="0" width="100%" class="jbtab" id="table_style">
        <tbody>
        <tr>
            <th width="60%">�ļ���</th>
            <th width="10%">�ϴ�����</th>
            <th>����</th>
        </tr>
        <?php foreach($plupFileList as $fileInfo){ ?>
            <tr>
                <td class="left"><?=$fileInfo['cName']?></td>
                <td><?=substr($fileInfo['createTime'],0,10)?></td>
                <td>
                    <a class="bjwrt" href="<?=$fileInfo['filePath']?>">����</a>
                </td>
            </tr>
        <?php }?>
        </tbody>
    </table>
<?php }?>
