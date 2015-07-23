<?php if($plupFileList){?>
    <p class="tjtip">文件下载区</p>
    <table cellpadding="0" cellspacing="0" width="100%" class="jbtab" id="table_style">
        <tbody>
        <tr>
            <th width="60%">文件名</th>
            <th width="10%">上传日期</th>
            <th>操作</th>
        </tr>
        <?php foreach($plupFileList as $fileInfo){ ?>
            <tr>
                <td class="left"><?=$fileInfo['cName']?></td>
                <td><?=substr($fileInfo['createTime'],0,10)?></td>
                <td>
                    <a class="bjwrt" href="<?=$fileInfo['filePath']?>">下载</a>
                </td>
            </tr>
        <?php }?>
        </tbody>
    </table>
<?php }?>
