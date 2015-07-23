<?php
    require_once BATH_PATH.'source/Dao/IndicatorDao.php';
    $ind_dao = new IndicatorDao();
    $indicatorList = $ind_dao->getAllTypeList();
?>

<div class="attr_form_1" flag="1" >
    <div class="rtop">
        <p class="icon1"><b class="sm_blue">量化指标类</b></p>
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
                                <td width="20%">
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

                                <td width="10%" class="sm_xsmbadd_td2">
                                    <div class="smfl">
                                        <span><em class="c-yel">*</em> 权重：</span>
                                        <input <?=$widget->disabled()?> type="text" value="<?=$itemData['qz']?>" tagname="qz" name="qz_old_<?=$key?>"  class="width40 j-notnull {validate:{totalQz:true }}" />&nbsp;%
                                    </div>
                                </td>
                                <?php if(isset($scoreList['selfScore'])){?>
                                    <td width="10%" class="sm_xsmbadd_td2">
                                        <div class="smfl">
                                            <span><em class="c-yel">*</em> 自评分：</span>
                                            <input  type="text" value="<?=$itemData['selfScore']?>" tagname="selfScore" name="selfScore_old_<?=$key?>"  class="width40 j-notnull {validate:{required:true,percent:true }}" />
                                        </div>
                                    </td>
                                    <td width="30%" class="sm_xsmbadd_td2">
                                        <div class="smfl">
                                            <span> 自我评价：</span>
                                            <input  type="text" value="<?=$itemData['selfAssess']?>" tagname="selfAssess" name="selfAssess_old_<?=$key?>"  class="width160 j-notnull" />
                                        </div>
                                    </td>
                                <?php }?>
                                <?php if(isset($scoreList['leadScore'])){?>
                                    <td width="10%" class="sm_xsmbadd_td2">
                                        <div class="smfl">
                                            <span><em class="c-yel">*</em> 领导评分：</span>
                                            <input  type="text" value="<?=$itemData['leadScore']?>" tagname="leadScore" name="leadScore_old_<?=$key?>"  class="width40 j-notnull {validate:{required:true,percent:true }}" />
                                        </div>
                                    </td>
                                <?php }?>
                                <?php if($widget->validElement()){?>
                                    <td width="15%" class="sm_xsmbadd_td2">
                                        <div class="del_td" onclick="Assess.prototype.delItemDom(this,1)">
                                            <input <?=$widget->disabled()?>  type="button" class="btn67" name="del" value="删除">
                                        </div>
                                    </td>
                                 <?php }?>
                            </tr>

                        <?php }?>
                    <?php }?>
                <?php }?>
                <tr style="<?=$widget->getTrIsShow()?>" class="tpl_tr">
                    <td width="20%">
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

                    <td width="10%" class="sm_xsmbadd_td2">
                        <div class="smfl">
                            <span><em class="c-yel">*</em> 权重：</span>
                            <input <?=$widget->disabled()?>  type="text" value="" tagname="qz" name="qz_new_[@]"  class="width40 j-notnull {validate:{totalQz:true}}" />&nbsp;%
                        </div>
                    </td>
                    <?php if(isset($scoreList['selfScore'])){?>
                        <td width="10%" class="sm_xsmbadd_td2">
                            <div class="smfl">
                                <span><em class="c-yel">*</em> 自评分：</span>
                                <input type="text" value=""  tagname="selfScore" name="selfScore_new_[@]"  class="width40 j-notnull {validate:{required:true,percent:true }}" />
                            </div>
                        </td>
                        <td width="30%" class="sm_xsmbadd_td2">
                            <div class="smfl">
                                <span> 自我评价：</span>
                                <input  type="text" value="" tagname="selfAssess" name="selfAssess_new_[@]"  class="width160 j-notnull" />
                            </div>
                        </td>
                    <?php }?>
                    <?php if(isset($scoreList['leadScore'])){?>
                        <td width="10%" class="sm_xsmbadd_td2">
                            <div class="smfl">
                                <span><em class="c-yel">*</em> 领导评分：</span>
                                <input type="text" value="" tagname="leadScore" name="leadScore_new_[@]"  class="width40 j-notnull {validate:{required:true,percent:true}}" />
                            </div>
                        </td>
                    <?php }?>
                    <td width="15%" class="sm_xsmbadd_td2">
                        <div class="del_td" onclick="Assess.prototype.delItemDom(this,1)">
                            <input  <?=$widget->disabled()?> type="button" class="btn67" name="del" value="删除">
                        </div>
                    </td>
                </tr>
            </table>
        </div>
    </div>
    <?php if($widget->validElement()){?>
     <div class="sm_target"><a href="javascript:void(0);">添加指标</a></div>
    <?php }?>
</div>