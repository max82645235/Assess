<?php
    require_once BATH_PATH.'source/Dao/IndicatorDao.php';
    $ind_dao = new IndicatorDao();
    $indicatorList = $ind_dao->getAllTypeList();
?>

<div class="attr_form_1" flag="1" style="<?php if(!$renderData){?>display: none;<?php }?>">
    <div class="rtop">
        <p class="icon1"><b class="sm_blue">量化指标类</b></p>
    </div>
    <div class="kctjcon">
        <div class="sm_div mlr30" style="padding:10px;">
            基本设置：<br /><br />
            整体权重：<input <?=$widget->disabled()?> type="text" value="<?=@$renderData['weight']?>" name="attr1_weight"  class="width80 j-notnull widget" />&nbsp;%
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
                                    <select <?=$widget->disabled()?>  onchange="Assess.prototype.triggerIndicatorSelect($(this))"  name="indicator_parent" class="commission_indicator_parent">
                                        <?php if($indicatorList){?>
                                            <?php foreach($indicatorList as $k=>$data){?>
                                                <option value="<?=$data['id']?>"
                                                    <?php if($itemData['indicator_parent']==$data['id']){?>  selected="selected" <?php }?>><?=$data['name']?>
                                                </option>
                                            <?php }?>
                                        <?php }?>
                                    </select>
                                    <select  <?=$widget->disabled()?> name="indicator_child" class="commission_indicator_child" >
                                    </select>
                                    <input <?=$widget->disabled()?> type="hidden" name="indicator_child_hidden" class="indicator_parent_hidden" value="<?=$itemData['indicator_child']?>">
                                </td>

                                <td width="17%" class="sm_xsmbadd_td1">
                                    <div class="smfl">
                                        <span><em class="c-yel">*</em> 指标阈值：</span>
                                        <input <?=$widget->disabled()?> type="text" value="<?=$itemData['zbyz']?>" name="zbyz"  class="width40 j-notnull"/>
                                    </div>
                                </td>
                                <td width="15%" class="sm_xsmbadd_td2">
                                    <div class="smfl">
                                        <span><em class="c-yel">*</em> 权重：</span>
                                        <input <?=$widget->disabled()?> type="text" value="<?=$itemData['qz']?>" name="qz"  class="width40 j-notnull" />&nbsp;%
                                    </div>
                                </td>
                                <?php if(isset($scoreList['selfScore'])){?>
                                    <td width="15%" class="sm_xsmbadd_td2">
                                        <div class="smfl">
                                            <span><em class="c-yel">*</em> 自评分：</span>
                                            <input  type="text" value="<?=$itemData['selfScore']?>" name="selfScore"  class="width40 j-notnull" />
                                        </div>
                                    </td>
                                <?php }?>
                                <?php if(isset($scoreList['leadScore'])){?>
                                    <td width="15%" class="sm_xsmbadd_td2">
                                        <div class="smfl">
                                            <span><em class="c-yel">*</em> 领导打分：</span>
                                            <input  type="text" value="<?=$itemData['leadScore']?>" name="leadScore"  class="width40 j-notnull" />
                                        </div>
                                    </td>
                                <?php }?>
                                <td width="15%" class="sm_xsmbadd_td2">
                                    <div class="del_td" onclick="Assess.prototype.delItemDom(this,1)">
                                        <input <?=$widget->disabled()?>  type="button" class="btn67" name="del" value="删除">
                                    </div>
                                </td>
                            </tr>
                        <?php }?>
                    <?php }?>
                <?php }?>
                <?php if(!isset($itemDataList) || empty($itemDataList)){?>
                    <tr>
                        <td width="25%">
                            <em class="c-yel">*</em>
                            <select  <?=$widget->disabled()?> name="indicator_parent" class="commission_indicator_parent" onchange="Assess.prototype.triggerIndicatorSelect($(this))">
                                <?php if($indicatorList){?>
                                    <?php foreach($indicatorList as $k=>$data){?>
                                        <option value="<?=$data['id']?>"><?=$data['name']?></option>
                                    <?php }?>
                                <?php }?>
                            </select>
                            <select name="indicator_child" class="commission_indicator_child">
                                <option value="">请选择</option>
                            </select>
                        </td>

                        <td width="15%" class="sm_xsmbadd_td1">
                            <div class="smfl">
                                <span><em class="c-yel">*</em> 指标阈值：</span>
                                <input <?=$widget->disabled()?>  type="text" value="" name="zbyz"  class="width40 j-notnull required"/>
                            </div>
                        </td>
                        <td width="15%" class="sm_xsmbadd_td2">
                            <div class="smfl">
                                <span><em class="c-yel">*</em> 权重：</span>
                                <input <?=$widget->disabled()?>  type="text" value="" name="qz"  class="width40 j-notnull percent" />&nbsp;%
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
                            <div class="del_td" onclick="Assess.prototype.delItemDom(this,1)">
                                <input  <?=$widget->disabled()?> type="button" class="btn67" name="del" value="删除">
                            </div>
                        </td>
                    </tr>
                <?php }?>
            </table>
        </div>
    </div>
    <?php if($widget->validElement()){?>
     <div class="sm_target"><a href="javascript:void(0);">添加指标</a></div>
    <?php }?>
</div>