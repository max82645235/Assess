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
                                <td width="40%">
                                    <div class="smfl">
                                        <span><em class="c-yel">*</em>工作任务名称： </span>
                                        <input  <?=$widget->disabled()?> type="text" value="<?=$itemData['job_name']?>" tagname="job_name" name="job_name_old_<?=$key?>" class="width160 j-notnull {validate:{job_name:true}}" />
                                    </div>
                                </td>
                                <td width="30%" class="sm_xsmbadd_td2">
                                    <div class="smfl">
                                        <span><em class="c-yel">*</em> 权重：</span>
                                        <input   <?=$widget->disabled()?> type="text" value="<?=$itemData['qz']?>" tagname="job_qz" name="job_qz_old_<?=$key?>"  class="width40 j-notnull {validate:{totalQz:true}}" />&nbsp;%
                                    </div>
                                </td>
                                <?php if(isset($scoreList['selfScore'])){?>
                                    <td width="10%" class="sm_xsmbadd_td2">
                                        <div class="smfl">
                                            <span><em class="c-yel">*</em> 自评分：</span>
                                            <input  type="text" value="<?=$itemData['selfScore']?>" tagname="selfScore" name="job_selfScore_old_<?=$key?>"  class="width40 j-notnull  {validate:{required:true,percent:true}}" />
                                        </div>
                                    </td>
                                <?php }?>
                                <?php if(isset($scoreList['leadScore'])){?>
                                    <td width="10%" class="sm_xsmbadd_td2">
                                        <div class="smfl">
                                            <span><em class="c-yel">*</em> 领导评分：</span>
                                            <input type="text" value="<?=$itemData['leadScore']?>" tagname="leadScore" name="job_leadScore_old_<?=$key?>"  class="width40 j-notnull  {validate:{required:true,percent:true}}" />
                                        </div>
                                    </td>
                                <?php }?>

                                 <?php if($widget->validElement()){?>
                                    <td width="10%" class="sm_xsmbadd_td2">
                                        <div class="del_td" onclick="Assess.prototype.delItemDom(this,2)">
                                            <input type="button" class="btn67"  name="del" value="删除">
                                        </div>
                                    </td>
                                <?php }?>
                            </tr>
                            <tr class="ext_assess_tr">
                                <td colspan="4">
                                    <div class="extend_assess_info">
                                        <table style="width: 100%;" class="ext_commission_table jbtab">
                                            <tbody>
                                            <tr>
                                                <td colspan="4">
                                                    <p class="icon1"><b class="orange">工作具体项</b></p>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="w5"><em class="c-yel">*</em>达成时间</td>
                                                <td  class="w40">
                                                    <div class="data" style="margin-right:6px;">
                                                        <input readonly="readonly"  type="text"  name="time_old_<?=$key?>" id="time_old_j_<?=$key?>" value="<?=$itemData['reachTime']?>" class="width135 reachTime {validate:{required:true}}"  />
                                                    </div>
                                                    <script  type="text/javascript">
                                                        $(function(){
                                                            $( "#time_old_j_<?=$key?>" ).datepicker();
                                                        });
                                                    </script>
                                                <td class="w5">数据来源</td>
                                                <td  class="w40">
                                                    <input type="text" name="sourceData" class="input sourceData" value="<?=$itemData['sourceData']?>">
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="w5">具体任务</td>
                                                <td class="w40">
                                                    <textarea  class="textarea150 detailJobTextarea"><?=$itemData['detailTxt']?></textarea>
                                                </td>
                                                <td class="w5">评价标准</td>
                                                <td  class="w40">
                                                    <textarea  class="textarea150 assessStad" ><?=$itemData['assessStad']?></textarea>
                                                </td>
                                            </tr>
                                            <tr>
                                                <?php if(isset($scoreList['selfScore'])){?>
                                                    <td class="w5"><em class="c-yel">*</em>自我评价</td>
                                                    <td  class="w40">
                                                        <textarea  class="textarea150 selfAssess" ><?=$itemData['selfAssess']?></textarea>
                                                    </td>
                                                <?php }?>

                                                <?php if(isset($scoreList['leadScore'])){?>
                                                    <td class="w5"><em class="c-yel">*</em>领导评价</td>
                                                    <td  class="w40">
                                                        <textarea class="textarea150 leadAssess" ><?=$itemData['leadAssess']?></textarea>
                                                    </td>
                                                <?php }?>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </td>
                            </tr>
                        <?php }?>
                    <?php }?>
                <?php }?>

                <!--权重评分模板tr start-->
                <tr style="<?=$widget->getTrIsShow()?>" class="tpl_tr">
                    <td width="40%">
                        <div class="smfl">
                            <span><em class="c-yel">*</em>工作任务名称： </span>
                            <input type="text" value="" tagname="job_name" name="job_name_new_[@]" class="width160 j-notnull {validate:{job_name:true }}" />
                        </div>
                    </td>
                    <td width="30%" class="sm_xsmbadd_td2">
                        <div class="smfl">
                            <span><em class="c-yel">*</em> 权重：</span>
                            <input type="text"  style="margin-left: 32px;" value="" tagname="job_qz" name="job_qz_new_[@]"  class="width105 j-notnull {validate:{totalQz:true }}" />&nbsp;%
                        </div>
                    </td>
                    <?php if(isset($scoreList['selfScore'])){?>
                        <td width="10%" class="sm_xsmbadd_td2">
                            <div class="smfl">
                                <span><em class="c-yel">*</em> 自评分：</span>
                                <input type="text" value="" tagname="selfScore" name="job_selfScore_new_[@]"  class="width40 j-notnull required {validate:{required:true,percent:true}}" />
                            </div>
                        </td>
                    <?php }?>
                    <?php if(isset($scoreList['leadScore'])){?>
                        <td width="10%" class="sm_xsmbadd_td2">
                            <div class="smfl">
                                <span><em class="c-yel">*</em> 领导评分：</span>
                                <input type="text" value="" tagname="leadScore" name="job_leadScore_new_[@]"  class="width40 j-notnull  {validate:{required:true,percent:true}}" />
                            </div>
                        </td>
                    <?php }?>
                    <td width="10%" class="sm_xsmbadd_td2">
                        <div class="del_td" onclick="Assess.prototype.delItemDom(this,2)">
                            <input type="button" class="btn67" name="del" value="删除">
                        </div>
                    </td>
                </tr>
                <!--权重评分模板tr end-->

                <!--任务具体项模板tr start-->
                <tr class="ext_assess_tr_tpl ext_assess_tr" style="<?=$widget->getTrIsShow()?>">
                    <td colspan="4">
                        <div class="extend_assess_info">
                            <table style="width: 100%;" class="ext_commission_table jbtab">
                                <tbody>
                                <tr>
                                    <td colspan="4">
                                        <p class="icon1"><b class="orange">工作具体项</b></p>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="w5"><em class="c-yel">*</em>达成时间</td>
                                    <td  class="w40">
                                        <div class="data" style="margin-right:6px;">
                                            <input readonly="readonly"  type="text"  name="time_new-_-" id="time_new_2_-_-" value="" class="width135 reachTime {validate:{required:true}}"  />
                                        </div>
                                        <script  type="text/javascript">
                                            $(function(){
                                                $( ".reachTime" ).datepicker();
                                            });
                                        </script>
                                    <td class="w5">数据来源</td>
                                    <td  class="w40">
                                        <input type="text" name="sourceData" class="input sourceData">
                                    </td>
                                </tr>
                                <tr>
                                    <td class="w5">具体任务</td>
                                    <td class="w40">
                                        <textarea   class="textarea150 detailJobTextarea"></textarea>
                                    </td>
                                    <td class="w5">评价标准</td>
                                    <td  class="w40">
                                        <textarea  class="textarea150 assessStad"></textarea>
                                    </td>
                                </tr>
                                <tr>
                                    <?php if(isset($scoreList['selfScore'])){?>
                                        <td class="w5"><em class="c-yel">*</em>自我评价</td>
                                        <td  class="w40">
                                            <textarea  class="textarea150 selfAssess"></textarea>
                                        </td>
                                    <?php }?>

                                    <?php if(isset($scoreList['leadScore'])){?>
                                        <td class="w5"><em class="c-yel">*</em>领导评价</td>
                                        <td  class="w40">
                                            <textarea  class="textarea150 leadAssess"></textarea>
                                        </td>
                                    <?php }?>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </td>
                </tr>
                <!--任务具体项模板tr end-->

            </table>
        </div>
    </div>
    <?php if($widget->validElement()){?>
    <div class="sm_target"><a href="javascript:void(0);">添加任务</a></div>
    <?php }?>
</div>