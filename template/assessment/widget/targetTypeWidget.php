<div class="attr_form_3" flag="4" style="<?php if(!$renderData){?>display: none;<?php }?>">
    <div class="rtop">
        <p class="icon1"><b class="sm_blue">提成类</b></p>
    </div>

    <div class="kctjcon">
        <div class="sm_div mlr30" id="sales_target_div">
            <table class="sm_xsmbadd" width="100%">
                <?php
                if(isset($renderData['itemData']) && $renderData['itemData']){
                    $itemData = unserialize($renderData['itemData']);?>
                    <?php if($itemData){?>
                        <?php foreach($itemDataList as $itemData){?>
                            <tr>
                                <td width="40%">
                                    <div class="smfl">
                                        <span><em class="c-yel">*</em>提成点： </span>
                                        <input type="text" value="<?=$itemData['tc_name']?>" name="tc_name" class="width160 j-notnull" />&nbsp;%
                                    </div>
                                </td>
                                <?php if(isset($scoreList['selfScore'])){?>
                                    <td width="15%" class="sm_xsmbadd_td2">
                                        <div class="smfl">
                                            <span><em class="c-yel">*</em> 完成金额：</span>
                                            <input type="text" value="<?=$itemData['finishCash']?>" name="finishCash"  class="width40 j-notnull" />
                                        </div>
                                    </td>
                                <?php }?>
                            </tr>
                        <?php }?>
                    <?php }?>
                <?php }?>
                <?php if(!isset($itemDataList) || empty($itemDataList)){?>
                    <tr>
                        <td width="40%">
                            <div class="smfl">
                                <span><em class="c-yel">*</em>提成点： </span>
                                <input type="text" value="" name="tc_name" class="width60 j-notnull" />&nbsp;%
                            </div>
                        </td>
                        <?php if(isset($scoreList['selfScore'])){?>
                            <td width="15%" class="sm_xsmbadd_td2">
                                <div class="smfl">
                                    <span><em class="c-yel">*</em> 完成金额：</span>
                                    <input type="text" value="<?=$itemData['finishCash']?>" name="finishCash"  class="width40 j-notnull" />
                                </div>
                            </td>
                        <?php }?>
                    </tr>
                <?php }?>
            </table>
        </div>
    </div>
</div>