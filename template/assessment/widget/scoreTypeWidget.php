<div class="attr_form_2" flag="3" style="<?php if(!$renderData){?>display: none;<?php }?>">
    <div class="rtop">
        <p class="icon1"><b class="sm_blue">�����</b></p>
    </div>
    <div class="kctjcon">
        <div class="sm_div mlr30"  style="padding:10px;">
            �������ã�<br /><br />
            1�� �� <input type="text" value="<?=@$renderData['cash']?>" name="attr3_cash"  class="width80 j-notnull" /> Ԫ
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
                            <?php foreach($itemDataList as $itemData){?>
                                <tr>
                                    <td width="75%">
                                        <div class="smfl">
                                            <span><em class="c-yel">*</em>����� </span>
                                            <input type="text" value="<?=$itemData['score_name']?>" name="score_name" class="width160 j-notnull" />
                                        </div>
                                    </td>
                                    <td width="17%" class="sm_xsmbadd_td1">
                                        <div class="smfl">
                                            <span><em class="c-yel">*</em> ָ����ֵ��</span>
                                            <input type="text" value="<?=$itemData['zbyz']?>" name="zbyz"  class="width40 j-notnull"/>
                                        </div>
                                    </td>
                                    <td width="15%" class="sm_xsmbadd_td2">
                                        <div class="del_td" onclick="Assess.prototype.delItemDom(this)">
                                            <input type="button" class="btn67" value="ɾ��">
                                        </div>
                                    </td>
                                </tr>
                            <?php }?>
                    <?php }?>
                <?php }?>
                <?php if(!isset($itemDataList) || empty($itemDataList)){?>
                    <tr>
                        <td width="75%">
                            <div class="smfl">
                                <span><em class="c-yel">*</em>����� </span>
                                <input type="text" value="" name="score_name" class="width160 j-notnull" />
                            </div>
                        </td>
                        <td width="17%" class="sm_xsmbadd_td1">
                            <div class="smfl">
                                <span><em class="c-yel">*</em> ָ����ֵ��</span>
                                <input type="text" value="" name="zbyz"  class="width40 j-notnull"/>
                            </div>
                        </td>
                        <td width="15%" class="sm_xsmbadd_td2">
                            <div class="del_td" onclick="Assess.prototype.delItemDom(this)">
                                <input type="button" class="btn67" value="ɾ��">
                            </div>
                        </td>
                    </tr>
                <?php }?>
            </table>
        </div>
    </div>
    <div class="sm_target"><a href="javascript:void(0);">�����Ŀ</a></div>
</div>