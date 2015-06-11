<div class="attr_form_1" flag="1" style="<?php if(!$renderData){?>display: none;<?php }?>">
    <div class="rtop">
        <p class="icon1"><b class="sm_blue">量化指标类</b></p>
    </div>

    <div class="kctjcon">
        <div class="sm_div mlr30" style="padding:10px;">
            基本设置：<br /><br />
            整体权重：<input type="text" value="" name="attr1_widget"  class="width80 j-notnull widget" />
            &nbsp; <input type="checkbox" name="write_by_lead_status" /> 由直接领导设置
            &nbsp; <input type="checkbox" name="write_by_staff_status" /> 由员工设置
        </div>
    </div>
    <div class="kctjcon">
        <div class="sm_div mlr30">
            <table class="sm_xsmbadd" width="100%">
                <tr>
                    <td width="26%">
                        <em class="c-yel">*</em>
                        <select name="indicator_parent">
                            <option value="1">指标分类1</option>
                        </select>
                        <select name="indicator_child" >
                            <option value="1">指标1</option>
                        </select>
                    </td>
                    <td width="17%" class="sm_xsmbadd_td1">
                        <div class="smfl">
                            <span><em class="c-yel">*</em> 指标阈值：</span>
                            <input type="text" value="" name="attr1_zbyz"  class="width40 j-notnull"/>
                        </div>
                    </td>
                    <td width="17%" class="sm_xsmbadd_td2">
                        <div class="smfl">
                            <span><em class="c-yel">*</em> 计算公式：</span>
                            <input type="text" value="" name="attr1_gs"  class="width40 j-notnull" />
                        </div>
                    </td>
                    <td width="15%" class="sm_xsmbadd_td2">
                        <div class="smfl">
                            <span><em class="c-yel">*</em> 权重：</span>
                            <input type="text" value="" name="attr1_qz"  class="width40 j-notnull" />
                        </div>
                    </td>
                    <td align="right" class="j-del">
                        <input type="checkbox" name="attr1_lead_write_status" /> 由直接领导填写
                        <input type="checkbox" name="attr1_staff_write_status" /> 由员工填写
                    </td>
                </tr>
            </table>
        </div>
    </div>
    <div class="sm_target"><a href="javascript:void(0);">添加指标</a></div>
</div>