<div class="attr_form_3" flag="4" style="<?php if(!$renderData){?>display: none;<?php }?>">
    <div class="rtop">
        <p class="icon1"><b class="sm_blue">提成类</b></p>
    </div>
    <div class="kctjcon">
        <div class="sm_div mlr30" id="sales_target_div">
            <table class="sm_xsmbadd" width="100%">
                <?php
                if(isset($renderData['itemData']) && $renderData['itemData']){
                    $itemDataList = unserialize($renderData['itemData']);?>
                    <?php if($itemDataList){?>
                        <?php foreach($itemDataList as $itemData){?>
                            <tr>
                                <td width="40%">
                                    <div class="smfl">
                                        <span><em class="c-yel">*</em>提成点： </span>
                                        <input <?=$widget->disabled()?>  type="text" value="<?=$itemData['tc_name']?>" tagname="tc_name" name="tc_name" class="width40 j-notnull {validate:{required:true,percent:true}}" />&nbsp;%
                                    </div>
                                </td>
                                <?php if(isset($scoreList['selfScore'])){?>
                                    <td width="15%" class="sm_xsmbadd_td2">
                                        <div class="smfl">
                                            <span><em class="c-yel">*</em> 完成金额：</span>
                                            <input  style="width: 100px;" type="text" value="<?=$itemData['finishCash']?>" tagname="finishCash" name="finishCash"  class="width40 j-notnull  {validate:{required:true,cash:true}}" />
                                        </div>
                                    </td>
                                    <td width="30%" class="sm_xsmbadd_td2">
                                        <div class="smfl">
                                            <span> 自我评价：</span>
                                            <input  type="text" value="<?=$itemData['selfAssess']?>" tagname="selfAssess" name="selfAssess_old_<?=$key?>"  class="width160 j-notnull" />
                                        </div>
                                    </td>
                                <?php }?>
                            </tr>
                        <?php }?>
                    <?php }?>
                <?php }?>
                <?php if($widget->validElement() && (!isset($itemDataList) || empty($itemDataList))){?>
                    <tr >
                        <td width="40%">
                            <div class="smfl">
                                <span><em class="c-yel">*</em>提成点： </span>
                                <input type="text" value="" tagname="tc_name" class="width40 j-notnull {validate:{ required:true,percent:true}}" />&nbsp;%
                            </div>
                        </td>
                        <?php if(isset($scoreList['selfScore'])){?>
                            <td width="15%" class="sm_xsmbadd_td2">
                                <div class="smfl">
                                    <span><em class="c-yel">*</em> 完成金额：</span>
                                    <input type="text" value="<?=$itemData['finishCash']?>" tagname="finishCash"  class="width40 j-notnull {validate:{required:true,cash:true}}" style="width: 100px;" />
                                </div>
                            </td>
                            <td width="30%" class="sm_xsmbadd_td2">
                                <div class="smfl">
                                    <span> 自我评价：</span>
                                    <input  type="text" value="" tagname="selfAssess" name="selfAssess_old_<?=$key?>"  class="width160 j-notnull" />
                                </div>
                            </td>
                        <?php }?>
                    </tr>
                <?php }?>
            </table>
        </div>
    </div>
</div>