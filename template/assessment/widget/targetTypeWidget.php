<div class="attr_form_3" flag="4" style="<?php if(!$renderData){?>display: none;<?php }?>">
    <div class="rtop">
        <p class="icon1"><b class="sm_blue">提成类</b></p>
    </div>

    <div class="kctjcon">
        <div class="sm_div mlr30"  style="padding:10px;">
            基本设置：<br /><br />
            1分 ＝ <input type="text" value="" name="attr3_cash"  class="width80 j-notnull" /> 元
        </div>
    </div>
    <div class="kctjcon">
        <div class="sm_div mlr30" id="sales_target_div">
            <table class="sm_xsmbadd" width="100%">
                <tr>
                    <td width="75%">
                        <div class="smfl">
                            <span><em class="c-yel">*</em>考核项： </span>
                            <input type="text" value="" name="score_name" class="width160 j-notnull" />
                        </div>
                    </td>
                    <td width="17%" class="sm_xsmbadd_td1">
                        <div class="smfl">
                            <span><em class="c-yel">*</em> 指标阈值：</span>
                            <input type="text" value="" name="zbyz"  class="width40 j-notnull"/>
                        </div>
                    </td>
                    <td width="15%" class="sm_xsmbadd_td2">
                        <div class="del_td" onclick="Assess.prototype.delItemDom(this)">
                            <input type="button" class="btn67" value="删除">
                        </div>
                    </td>
                </tr>
            </table>
        </div>
    </div>
    <div class="sm_target"><a href="javascript:void(0);">添加项目</a></div>
</div>