<div class="attr_form_1" flag="2" style="<?php if(!$renderData){?>display: none;<?php }?>">
    <div class="rtop">
        <p class="icon1"><b class="sm_blue">工作任务类</b></p>
    </div>
    <div class="kctjcon">
        <div class="sm_div mlr30">
            <table class="sm_xsmbadd" width="100%">
                <?php
                if(isset($renderData['itemData']) && $renderData['itemData']){
                    $itemDataList = unserialize($renderData['itemData']);
                    ?>
                    <?php if($itemDataList){?>
                        <?php foreach($itemDataList as $key=>$itemData){?>
                            <tr>
                                <td width="26%">
                                    <div class="smfl">
                                        <span><em class="c-yel">*</em>工作任务名称： </span>
                                        <input  <?=$widget->disabled()?> type="text" value="<?=$itemData['job_name']?>" tagname="job_name" name="job_name_old_<?=$key?>" class="width160 j-notnull required" />
                                    </div>
                                </td>
                                <td width="15%" class="sm_xsmbadd_td2">
                                    <div class="smfl">
                                        <span><em class="c-yel">*</em> 权重：</span>
                                        <input   <?=$widget->disabled()?> type="text" value="<?=$itemData['qz']?>" tagname="job_qz" name="job_qz_old_<?=$key?>"  class="width40 j-notnull {validate:{ required:true,percent:true,totalQz:true }}" />&nbsp;%
                                    </div>
                                </td>
                                <?php if(isset($scoreList['selfScore'])){?>
                                    <td width="15%" class="sm_xsmbadd_td2">
                                        <div class="smfl">
                                            <span><em class="c-yel">*</em> 自评分：</span>
                                            <input  type="text" value="<?=$itemData['selfScore']?>" tagname="selfScore" name="selfScore_old_<?=$key?>"  class="width40 j-notnull required percent" />
                                        </div>
                                    </td>
                                <?php }?>
                                <?php if(isset($scoreList['leadScore'])){?>
                                    <td width="15%" class="sm_xsmbadd_td2">
                                        <div class="smfl">
                                            <span><em class="c-yel">*</em> 领导评分：</span>
                                            <input type="text" value="<?=$itemData['leadScore']?>" tagname="leadScore" name="leadScore_old_<?=$key?>"  class="width40 j-notnull required percent" />%
                                        </div>
                                    </td>
                                <?php }?>

                                 <?php if($widget->validElement()){?>
                                    <td width="15%" class="sm_xsmbadd_td2">
                                        <div class="del_td" onclick="Assess.prototype.delItemDom(this,2)">
                                            <input type="button" class="btn67"  name="del" value="删除">
                                        </div>
                                    </td>
                                <?php }?>
                            </tr>
                        <?php }?>
                    <?php }?>
                <?php }?>

                <?php if($widget->validElement() && (!isset($itemDataList) || empty($itemDataList))){?>
                    <tr>
                        <td width="26%">
                            <div class="smfl">
                                <span><em class="c-yel">*</em>工作任务名称： </span>
                                <input type="text" value="" tagname="job_name" name="job_name_new_[@]" class="width160 j-notnull required" />
                            </div>
                        </td>
                        <td width="15%" class="sm_xsmbadd_td2">
                            <div class="smfl">
                                <span><em class="c-yel">*</em> 权重：</span>
                                <input type="text" value="" tagname="job_qz" name="job_qz_new_[@]"  class="width40 j-notnull {validate:{ required:true,percent:true,totalQz:true }}" />&nbsp;%
                            </div>
                        </td>
                        <?php if(isset($scoreList['selfScore'])){?>
                            <td width="15%" class="sm_xsmbadd_td2">
                                <div class="smfl">
                                    <span><em class="c-yel">*</em> 自评分：</span>
                                    <input type="text" value="" tagname="selfScore" name="selfScore_new_[@]"  class="width40 j-notnull required percent" />
                                </div>
                            </td>
                        <?php }?>
                        <?php if(isset($scoreList['leadScore'])){?>
                            <td width="15%" class="sm_xsmbadd_td2">
                                <div class="smfl">
                                    <span><em class="c-yel">*</em> 领导打分：</span>
                                    <input type="text" value="" tagname="leadScore" name="leadScore_new_[@]"  class="width40 j-notnull required percent" />%
                                </div>
                            </td>
                        <?php }?>
                        <td width="15%" class="sm_xsmbadd_td2">
                            <div class="del_td" onclick="Assess.prototype.delItemDom(this,2)">
                                <input type="button" class="btn67" name="del" value="删除">
                            </div>
                        </td>
                    </tr>
                <?php }?>
            </table>
        </div>
    </div>
    <?php if($widget->validElement()){?>
    <div class="sm_target"><a href="javascript:void(0);">添加任务</a></div>
    <?php }?>
</div>