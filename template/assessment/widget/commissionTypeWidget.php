<div class="attr_form_1" flag="1" style="<?php if(!$renderData){?>display: none;<?php }?>">
    <div class="rtop">
        <p class="icon1"><b class="sm_blue">量化指标类</b></p>
    </div>
    <div class="kctjcon">
        <div class="sm_div mlr30" style="padding:10px;">
            基本设置：<br /><br />
            整体权重：<input type="text" value="<?=@$renderData['weight']?>" name="attr1_weight"  class="width80 j-notnull widget" />
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
                                    <em class="c-yel">*</em>
                                    <select name="indicator_parent">
                                        <option value="1">指标分类1</option>
                                        <option value="2">指标分类2</option>
                                        <option value="3">指标分类3</option>
                                        <option value="4">指标分类4</option>
                                    </select>
                                    <select name="indicator_child" >
                                        <option value="1">指标1</option>
                                        <option value="2">指标2</option>
                                        <option value="3">指标3</option>
                                        <option value="4">指标4</option>
                                    </select>
                                </td>

                                <td width="17%" class="sm_xsmbadd_td1">
                                    <div class="smfl">
                                        <span><em class="c-yel">*</em> 指标阈值：</span>
                                        <input type="text" value="<?=$itemData['zbyz']?>" name="zbyz"  class="width40 j-notnull"/>
                                    </div>
                                </td>
                                <td width="15%" class="sm_xsmbadd_td2">
                                    <div class="smfl">
                                        <span><em class="c-yel">*</em> 权重：</span>
                                        <input type="text" value="<?=$itemData['qz']?>" name="qz"  class="width40 j-notnull" />
                                    </div>
                                </td>
                                <td width="15%" class="sm_xsmbadd_td2">
                                    <div class="del_td" onclick="Assess.prototype.delItemDom(this,1)">
                                        <input type="button" class="btn67" value="删除">
                                    </div>
                                </td>
                            </tr>
                        <?php }?>
                    <?php }?>
                <?php }?>
                <?php if(!isset($itemDataList) || empty($itemDataList)){?>
                    <tr>
                        <td width="26%">
                            <em class="c-yel">*</em>
                            <select name="indicator_parent">
                                <option value="1">指标分类1</option>
                                <option value="2">指标分类2</option>
                                <option value="3">指标分类3</option>
                                <option value="4">指标分类4</option>
                            </select>
                            <select name="indicator_child" >
                                <option value="1">指标1</option>
                                <option value="2">指标2</option>
                                <option value="3">指标3</option>
                                <option value="4">指标4</option>
                            </select>
                        </td>

                        <td width="17%" class="sm_xsmbadd_td1">
                            <div class="smfl">
                                <span><em class="c-yel">*</em> 指标阈值：</span>
                                <input type="text" value="" name="zbyz"  class="width40 j-notnull"/>
                            </div>
                        </td>
                        <td width="15%" class="sm_xsmbadd_td2">
                            <div class="smfl">
                                <span><em class="c-yel">*</em> 权重：</span>
                                <input type="text" value="" name="qz"  class="width40 j-notnull" />
                            </div>
                        </td>
                        <td width="15%" class="sm_xsmbadd_td2">
                            <div class="del_td" onclick="Assess.prototype.delItemDom(this,1)">
                                <input type="button" class="btn67" value="删除">
                            </div>
                        </td>
                    </tr>
                <?php }?>
            </table>
        </div>
    </div>
    <div class="sm_target"><a href="javascript:void(0);">添加指标</a></div>
</div>