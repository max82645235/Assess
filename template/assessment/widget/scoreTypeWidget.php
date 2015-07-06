<div class="attr_form_2" flag="3" style="<?php if(!$renderData){?>display: none;<?php }?>">
    <div class="rtop">
        <p class="icon1"><b class="sm_blue">打分类</b></p>
    </div>
    <div class="kctjcon1">
        <div class="sm_div mlr30"  style="padding:10px;">
            基本设置：<br /><br />
            1分 ＝ <input type="text" <?=$widget->disabled()?> value="<?=@$renderData['cash']?>" name="attr3_cash"  class="width80 j-notnull" /> 元
        </div>
    </div>
    <div class="kctjcon">
        <div class="sm_div mlr30" id="sales_target_div">
            <table class="sm_xsmbadd" width="100%">
                <?php
                if(isset($renderData['itemData']) && $renderData['itemData']){
                    $itemDataList = unserialize($renderData['itemData']);
                    ?>
                    <?php if($itemDataList){?>
                        <?php foreach($itemDataList as $key=>$itemData){?>
                            <tr>
                                <td width="40%">
                                    <div class="smfl">
                                        <span><em class="c-yel">*</em>考核项： </span>
                                        <input <?=$widget->disabled()?> type="text" value="<?=$itemData['score_name']?>" tagname="score_name" name="score_name_old_<?=$key?>" class="{validate:{ required:true}}" />
                                    </div>
                                </td>
                                <?php if(isset($scoreList['selfScore'])){?>
                                    <td width="15%" class="sm_xsmbadd_td2">
                                        <div class="smfl">
                                            <span><em class="c-yel">*</em> 自评分：</span>
                                            <input type="text" value="<?=$itemData['selfScore']?>"  tagname="selfScore"   name="selfScore_old_<?=$key?>"  class="width40 j-notnull required percent" />
                                        </div>
                                    </td>
                                <?php }?>
                                <?php if(isset($scoreList['leadScore'])){?>
                                    <td width="15%" class="sm_xsmbadd_td2">
                                        <div class="smfl">
                                            <span><em class="c-yel">*</em> 领导评分：</span>
                                            <input type="text" value="<?=$itemData['leadScore']?>"  tagname="leadScore"   name="leadScore_old_<?=$key?>"  class="width40 j-notnull required percent" />
                                        </div>
                                    </td>
                                <?php }?>
                            <?php if($widget->validElement()){?>
                                <td width="15%" class="sm_xsmbadd_td2">
                                    <div class="del_td" onclick="Assess.prototype.delItemDom(this,3)">
                                        <input type="button" class="btn67"  name="del" value="删除">
                                    </div>
                                </td>
                            <?php }?>
                            </tr>
                        <?php }?>
                    <?php }?>
                <?php }?>
                <?php if($widget->validElement() && (!isset($itemDataList) || empty($itemDataList))){?>
                    <tr style="display: none;">
                        <td width="40%">
                            <div class="smfl">
                                <span><em class="c-yel">*</em>考核项： </span>
                                <input type="text" value="" tagname="score_name" name="score_name_new_[@]" class="{validate:{ required:true}}" />
                            </div>
                        </td>
                        <?php if(isset($scoreList['selfScore'])){?>
                            <td width="15%" class="sm_xsmbadd_td2">
                                <div class="smfl">
                                    <span><em class="c-yel">*</em> 自评分：</span>
                                    <input type="text" value="" tagname="selfScore"  name="selfScore_new_[@]"  class="width40 j-notnull required percent" />
                                </div>
                            </td>
                        <?php }?>
                        <?php if(isset($scoreList['leadScore'])){?>
                            <td width="15%" class="sm_xsmbadd_td2">
                                <div class="smfl">
                                    <span><em class="c-yel">*</em> 领导评分：</span>
                                    <input type="text" value="" tagname="leadScore" name="leadScore_new_[@]"  class="width40 j-notnull required percent" />
                                </div>
                            </td>
                        <?php }?>
                        <td width="15%" class="sm_xsmbadd_td2">
                            <div class="del_td" onclick="Assess.prototype.delItemDom(this,3)">
                                <input type="button" class="btn67" name="del"  value="删除">
                            </div>
                        </td>
                    </tr>
                <?php }?>
            </table>
        </div>
    </div>
    <?php if($widget->validElement()){?>
    <div class="sm_target"><a href="javascript:void(0);">添加项目</a></div>
    <?php }?>
</div>