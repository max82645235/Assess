<div class="attr_form_1" flag="2" style="<?php if(!$renderData){?>display: none;<?php }?>">
    <div class="rtop">
        <p class="icon1"><b class="sm_blue">工作任务类</b></p>
    </div>

    <div class="kctjcon">
        <div class="sm_div mlr30" style="padding:10px;">
            基本设置：<br /><br />
            整体权重：<input type="text" value="<?=@$renderData['weight']?>" name="attr2_weight"  class="width80 j-notnull" />&nbsp;%
        </div>
    </div>
    <div class="kctjcon">
        <div class="sm_div mlr30">
            <table class="sm_xsmbadd" width="100%">
                <?php
                if(isset($renderData['itemData']) && $renderData['itemData']){
                    $itemDataList = unserialize($renderData['itemData']);
                    ?>
                    <?php if($itemDataList){?>
                        <?php foreach($itemDataList as $itemData){?>
                            <tr>
                                <td width="26%">
                                    <div class="smfl">
                                        <span><em class="c-yel">*</em>工作任务名称： </span>
                                        <input type="text" value="<?=$itemData['job_name']?>" name="job_name" class="width160 j-notnull" />
                                    </div>
                                </td>
                                <td width="15%" class="sm_xsmbadd_td2">
                                    <div class="smfl">
                                        <span><em class="c-yel">*</em> 权重：</span>
                                        <input type="text" value="<?=$itemData['qz']?>" name="qz"  class="width40 j-notnull" />&nbsp;%
                                    </div>
                                </td>
                                <?php if(isset($scoreList['selfScore'])){?>
                                    <td width="15%" class="sm_xsmbadd_td2">
                                        <div class="smfl">
                                            <span><em class="c-yel">*</em> 自评分：</span>
                                            <input type="text" value="<?=$itemData['selfScore']?>" name="selfScore"  class="width40 j-notnull" />
                                        </div>
                                    </td>
                                <?php }?>
                                <?php if(isset($scoreList['leadScore'])){?>
                                    <td width="15%" class="sm_xsmbadd_td2">
                                        <div class="smfl">
                                            <span><em class="c-yel">*</em> 领导打分：</span>
                                            <input type="text" value="<?=$itemData['leadScore']?>" name="leadScore"  class="width40 j-notnull" />
                                        </div>
                                    </td>
                                <?php }?>
                                <td width="15%" class="sm_xsmbadd_td2">
                                    <div class="del_td" onclick="Assess.prototype.delItemDom(this,2)">
                                        <input type="button" class="btn67"  name="del" value="删除">
                                    </div>
                                </td>
                            </tr>
                        <?php }?>
                    <?php }?>
                <?php }?>

                <?php if(!isset($itemDataList) || empty($itemDataList)){?>
                    <tr>
                        <td width="26%">
                            <div class="smfl">
                                <span><em class="c-yel">*</em>工作任务名称： </span>
                                <input type="text" value="" name="job_name" class="width160 j-notnull" />
                            </div>
                        </td>
                        <td width="15%" class="sm_xsmbadd_td2">
                            <div class="smfl">
                                <span><em class="c-yel">*</em> 权重：</span>
                                <input type="text" value="" name="job_qz"  class="width40 j-notnull" />&nbsp;%
                            </div>
                        </td>
                        <?php if(isset($scoreList['selfScore'])){?>
                            <td width="15%" class="sm_xsmbadd_td2">
                                <div class="smfl">
                                    <span><em class="c-yel">*</em> 自评分：</span>
                                    <input type="text" value="" name="selfScore"  class="width40 j-notnull" />
                                </div>
                            </td>
                        <?php }?>
                        <?php if(isset($scoreList['leadScore'])){?>
                            <td width="15%" class="sm_xsmbadd_td2">
                                <div class="smfl">
                                    <span><em class="c-yel">*</em> 领导打分：</span>
                                    <input type="text" value="" name="leadScore"  class="width40 j-notnull" />
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
    <div class="sm_target"><a href="javascript:void(0);">添加任务</a></div>
</div>