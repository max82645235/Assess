<?php
    require_once BATH_PATH.'source/Dao/IndicatorDao.php';
    $ind_dao = new IndicatorDao();
    $indicatorList = $ind_dao->getAllTypeList();
?>
<style>
    .ext_commission_table{width: 100%;}
    .ext_commission_table .w5{width: 10%;}
    .ext_commission_table .w40{width: 40%;}
    .ext_commission_table .textarea150{width: 100%;height: 150px;}
    .ext_commission_table .input{width: 95%;padding:0 5px;height:24px;line-height:24px;vertical-align:middle;}
    .padding0{padding:0 0;}
    .attr_form_1 .commission .yellow{background-color: #FFDEAD;}
    .attr_form_1 .commission .sm_div{
        background: #fff;
        border: 1px solid #9a9a9a;
        border-bottom: 0;
    }
    .ext_assess_tr{border-bottom:1px solid #B3EE3A;}
</style>
<script>
    $(function(){
        $.datepicker.regional['zh-CN'] = {
            clearText: '���',
            clearStatus: '�����ѡ����',
            closeText: '�ر�',
            closeStatus: '���ı䵱ǰѡ��',
            prevText: '<����',
            prevStatus: '��ʾ����',
            prevBigText: '<<',
            prevBigStatus: '��ʾ��һ��',
            nextText: '����>',
            nextStatus: '��ʾ����',
            nextBigText: '>>',
            nextBigStatus: '��ʾ��һ��',
            currentText: '����',
            currentStatus: '��ʾ����',
            monthNames: ['һ��','����','����','����','����','����', '����','����','����','ʮ��','ʮһ��','ʮ����'],
            monthNamesShort: ['һ','��','��','��','��','��', '��','��','��','ʮ','ʮһ','ʮ��'],
            monthStatus: 'ѡ���·�',
            yearStatus: 'ѡ�����',
            weekHeader: '��',
            weekStatus: '�����ܴ�',
            dayNames: ['������','����һ','���ڶ�','������','������','������','������'],
            dayNamesShort: ['����','��һ','�ܶ�','����','����','����','����'],
            dayNamesMin: ['��','һ','��','��','��','��','��'],
            dayStatus: '���� DD Ϊһ����ʼ',
            dateStatus: 'ѡ�� m�� d��, DD',
            dateFormat: 'yy-mm-dd',
            firstDay: 1,
            initStatus: '��ѡ������',
            isRTL: false};
        $.datepicker.setDefaults($.datepicker.regional['zh-CN']);
    });
</script>
<div class="attr_form_1" flag="1" >
    <div class="rtop">
        <p class="icon1"><b class="sm_blue">����ָ����</b></p>
    </div>
    <div class="kctjcon commission">
        <div class="sm_div mlr30 padding0">
            <table class="sm_xsmbadd commission" width="100%">
                <?php
                if(isset($renderData['itemData']) && $renderData['itemData']){
                    $itemDataList = _unserialize($renderData['itemData']);
                    ?>
                    <?php if($itemDataList){?>
                        <?php foreach($itemDataList as $key=>$itemData){?>
                            <tr class="yellow">
                                <td >
                                    <em class="c-yel">*</em>
                                    <select <?=$widget->disabled()?>  onchange="Assess.prototype.triggerIndicatorSelect($(this))"  name="indicator_parent" class="commission_indicator commission_indicator_parent">
                                        <?php if($indicatorList){?>
                                            <?php foreach($indicatorList as $k=>$data){?>
                                                <option value="<?=$data['id']?>"
                                                    <?php if($itemData['indicator_parent']==$data['id']){?>  selected="selected" <?php }?>><?=$data['name']?>
                                                </option>
                                            <?php }?>
                                        <?php }?>
                                    </select>
                                    <select  <?=$widget->disabled()?> name="indicator_child" class="commission_indicator commission_indicator_child" >
                                    </select>
                                    <input <?=$widget->disabled()?> type="hidden" name="indicator_child_hidden" class="commission_indicator indicator_parent_hidden" value="<?=$itemData['indicator_child']?>">
                                </td>

                                <td  class="sm_xsmbadd_td2">
                                    <div class="smfl">
                                        <span><em class="c-yel">*</em> Ȩ�أ�</span>
                                        <input  style="margin-left: 32px;" <?=$widget->disabled()?> type="text" value="<?=$itemData['qz']?>" tagname="qz" name="qz_old_<?=$key?>"  class="width105 j-notnull {validate:{totalQz:true,required:true}}" />&nbsp;%
                                    </div>
                                </td>
                                <?php if(isset($scoreList['selfScore'])){?>
                                    <td width="15%" class="sm_xsmbadd_td2">
                                        <div class="smfl">
                                            <span><em class="c-yel">*</em> �����֣�</span>
                                            <input  type="text" value="<?=$itemData['selfScore']?>" tagname="selfScore" name="selfScore_old_<?=$key?>"  class="width40 j-notnull {validate:{required:true,percent:true }}" />
                                        </div>
                                    </td>
                                <?php }?>
                                <?php if(isset($scoreList['leadScore'])){?>
                                    <td width="15%" class="sm_xsmbadd_td2">
                                        <div class="smfl">
                                            <span><em class="c-yel">*</em> �쵼���֣�</span>
                                            <input  type="text" value="<?=$itemData['leadScore']?>" tagname="leadScore" name="leadScore_old_<?=$key?>"  class="width40 j-notnull {validate:{required:true,percent:true }}" />
                                        </div>
                                    </td>
                                <?php }?>
                                <?php if($widget->validElement()){?>
                                    <td width="10%" class="sm_xsmbadd_td2">
                                        <div class="del_td" onclick="Assess.prototype.delItemDom(this,1)">
                                            <input <?=$widget->disabled()?>  type="button" class="btn67" name="del" value="ɾ��">
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
                                                    <p class="icon1"><b class="orange">ָ�������</b></p>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="w5"><em class="c-yel">*</em>���ʱ��</td>
                                                <td  class="w40">
                                                    <div class="data" style="margin-right:6px;">
                                                        <input  <?=$widget->disabled()?> type="text"  name="c_time_old_<?=$key?>" id="time_old_c_<?=$key?>" value="<?=$itemData['reachTime']?>" class="width135 {validate:{dateFormat:true}}"  />
                                                    </div>
                                                    <script  type="text/javascript">
                                                        $(function(){
                                                            $( "#time_old_c_<?=$key?>" ).datepicker();
                                                        });
                                                    </script>
                                                <td class="w5"><em class="c-yel">*</em>������Դ</td>
                                                <td  class="w40">
                                                    <input  <?=$widget->disabled()?> type="text" name="c_sourceData_old_<?=$key?>" class="input sourceData {validate:{required:true}}" value="<?=$itemData['sourceData']?>">
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="w5"><em class="c-yel">*</em>��������</td>
                                                <td class="w40">
                                                    <textarea   <?=$widget->disabled()?> name="c_detailTxt_<?=$key?>" class="textarea150 detailCommisonTextarea {validate:{required:true}}" ><?=$itemData['detailTxt']?></textarea>
                                                </td>
                                                <td class="w5"><em class="c-yel">*</em>���۱�׼</td>
                                                <td  class="w40">
                                                    <textarea  <?=$widget->disabled()?> name="c_assessStad_<?=$key?>" class="textarea150 assessStad {validate:{required:true}}" ><?=$itemData['assessStad']?></textarea>
                                                </td>
                                            </tr>
                                            <tr>
                                                <?php if(isset($scoreList['selfScore'])){?>
                                                    <td class="w5"><em class="c-yel">*</em>��������</td>
                                                    <td  class="w40">
                                                        <textarea  name="c_selfScore_<?=$key?>" class="textarea150 selfAssess {validate:{required:true}}" ><?=$itemData['selfAssess']?></textarea>
                                                    </td>
                                                <?php }?>

                                                <?php if(isset($scoreList['leadScore'])){?>
                                                    <td class="w5"><em class="c-yel">*</em>�쵼����</td>
                                                    <td  class="w40">
                                                        <textarea  name="c_leadAssess_<?=$key?>"   class="textarea150 leadAssess {validate:{required:true}}"><?=$itemData['leadAssess']?></textarea>
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

                <!--Ȩ������trģ�� start-->
                <tr style="<?=$widget->getTrIsShow()?>" class="tpl_tr yellow">
                    <td >
                        <em class="c-yel">*</em>
                        <select  <?=$widget->disabled()?> name="indicator_parent" class="commission_indicator commission_indicator_parent" onchange="Assess.prototype.triggerIndicatorSelect($(this))">
                            <?php if($indicatorList){?>
                                <?php foreach($indicatorList as $k=>$data){?>
                                    <option value="<?=$data['id']?>"><?=$data['name']?></option>
                                <?php }?>
                            <?php }?>
                        </select>
                        <select name="indicator_child" class="commission_indicator commission_indicator_child">
                            <option value="">��ѡ��</option>
                        </select>
                    </td>

                    <td  class="sm_xsmbadd_td2">
                        <div class="smfl">
                            <span><em class="c-yel">*</em> Ȩ�أ�</span>
                            <input style="margin-left: 32px;" <?=$widget->disabled()?>  type="text" value="" tagname="qz" name="qz_new_[@]"  class="width105 j-notnull {validate:{totalQz:true,required:true}}" />&nbsp;%
                        </div>
                    </td>
                    <?php if(isset($scoreList['selfScore'])){?>
                        <td width="15%" class="sm_xsmbadd_td2">
                            <div class="smfl">
                                <span><em class="c-yel">*</em> �����֣�</span>
                                <input type="text" value=""  tagname="selfScore" name="selfScore_new_[@]"  class="width40 j-notnull {validate:{required:true,percent:true }}" />
                            </div>
                        </td>
                    <?php }?>
                    <?php if(isset($scoreList['leadScore'])){?>
                        <td width="15%" class="sm_xsmbadd_td2">
                            <div class="smfl">
                                <span><em class="c-yel">*</em> �쵼���֣�</span>
                                <input type="text" value="" tagname="leadScore" name="leadScore_new_[@]"  class="width40 j-notnull {validate:{required:true,percent:true}}" />
                            </div>
                        </td>
                    <?php }?>
                    <td width="10%" class="sm_xsmbadd_td2">
                        <div class="del_td" onclick="Assess.prototype.delItemDom(this,1)">
                            <input  <?=$widget->disabled()?> type="button" class="btn67" name="del" value="ɾ��">
                        </div>
                    </td>
                </tr>
                <!--Ȩ������trģ�� end-->

                <!--  ָ�������trģ�� start-->
                <tr class="ext_assess_tr_tpl ext_assess_tr" style="<?=$widget->getTrIsShow()?>">
                    <td colspan="4">
                        <div class="extend_assess_info">
                            <table style="width: 100%;" class="ext_commission_table jbtab">
                                <tbody>
                                <tr>
                                    <td colspan="4">
                                        <p class="icon1"><b class="orange">ָ�������</b></p>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="w5"><em class="c-yel">*</em>���ʱ��</td>
                                    <td  class="w40">
                                        <div class="data" style="margin-right:6px;">
                                            <input   type="text"  name="c_time_new-_-" id="time_new_1_-_-" value="" class="width135 reachTime {validate:{dateFormat:true}}"  />
                                        </div>
                                        <script  type="text/javascript">
                                            $(function(){
                                                $( ".reachTime" ).datepicker();
                                            });
                                        </script>
                                    <td class="w5"><em class="c-yel">*</em>������Դ</td>
                                    <td  class="w40">
                                        <input type="text" name="c_sourceData_new_-_-" class="input sourceData {validate:{required:true}}">
                                    </td>
                                </tr>
                                <tr>
                                    <td class="w5"><em class="c-yel">*</em>��������</td>
                                    <td class="w40">
                                        <textarea  name="c_detailTxt_new_-_-"  class="textarea150 detailCommisonTextarea {validate:{required:true}}"></textarea>
                                    </td>
                                    <td class="w5"><em class="c-yel">*</em>���۱�׼</td>
                                    <td  class="w40">
                                        <textarea   name="c_assessStad_new_-_-"   class="textarea150 assessStad {validate:{required:true}}"></textarea>
                                    </td>
                                </tr>
                                <tr>
                                    <?php if(isset($scoreList['selfScore'])){?>
                                        <td class="w5"><em class="c-yel">*</em>��������</td>
                                        <td  class="w40">
                                            <textarea name="c_selfScore_new_-_-"  class="textarea150 selfAssess {validate:{required:true}}"></textarea>
                                        </td>
                                    <?php }?>

                                    <?php if(isset($scoreList['leadScore'])){?>
                                        <td class="w5"><em class="c-yel">*</em>�쵼����</td>
                                        <td  class="w40">
                                            <textarea name="c_leadScore_-_-"   class="textarea150 leadAssess {validate:{required:true}}"></textarea>
                                        </td>
                                    <?php }?>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </td>
                </tr>
                <!--  ָ�������trģ�� end-->

            </table>
        </div>
    </div>
    <?php if($widget->validElement()){?>
     <div class="sm_target"><a href="javascript:void(0);">���ָ��</a></div>
    <?php }?>
</div>