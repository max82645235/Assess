<?php
$diffData = unserialize($record_info['relation']['diffData']);
$historyData = unserialize($diffData['history']);
$record_info['relation']['diffData'] = $diffData;
?>
<?php if(!empty($record_info['relation']['diffData']) && $diffData['same']==0){?>
    <p class="tjtip">�ᱨǰ</p>
    <?=$assessAttrWidget->renderItemTable($historyData)?>
    <p class="tjtip">�ᱨ��</p>
    <?=$assessAttrWidget->renderItemTable($record_info,true)?>
<?php }?>