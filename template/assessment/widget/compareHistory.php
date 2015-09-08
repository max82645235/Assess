<?php
$diffData = _unserialize($record_info['relation']['diffData']);
$historyData = _unserialize($diffData['history']);
$record_info['relation']['diffData'] = $diffData;
?>
<?php if(!empty($record_info['relation']['diffData']) && $diffData['same']==0){?>
    <p class="tjtip">提报前</p>
    <?=$widget->renderItemTable($historyData)?>
    <p class="tjtip">提报后</p>
    <?=$widget->renderItemTable($record_info,true)?>
<?php }?>