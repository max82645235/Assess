<div class="attr_form_3" flag="3" style="<?php if(!$renderData){?>display: none;<?php }?>">
    <div class="rtop">
        <p class="icon1"><b class="sm_blue">提成类</b></p>
    </div>

    <div class="kctjcon">
        <div class="sm_div mlr30"  style="padding:10px;">
            基本设置：<br /><br />
            1分 ＝ <input type="text" value="" name="attr3_money"  class="width80 j-notnull" /> 元
            &nbsp; <input type="checkbox" name="write_by_lead_status" /> 由直接领导设置
            &nbsp; <input type="checkbox" name="write_by_staff_status" /> 由员工设置
        </div>
    </div>
    <div class="kctjcon">
        <div class="sm_div mlr30" id="sales_target_div">
            <table class="sm_xsmbadd" width="100%">
                <tr>
                    <td width="75%">
                        <div class="smfl">
                            <span><em class="c-yel">*</em>考核项： </span>
                            <input type="text" value="考核项1" name="attr2_name" class="width160 j-notnull" />
                        </div>
                    </td>
                    <td align="right" class="j-del">
                        <input type="checkbox" name="attr2_lead_write_status" /> 由直接领导填写
                        <input type="checkbox" name="attr2_staff_write_status" /> 由员工填写
                    </td>
                </tr>
            </table>
        </div>
    </div>
    <div class="sm_target"><a href="javascript:void(0);">添加项目</a></div>
</div>