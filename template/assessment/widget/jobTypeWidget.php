<div class="attr_form_1" flag="2" style="<?php if(!$renderData){?>display: none;<?php }?>">
    <div class="rtop">
        <p class="icon1"><b class="sm_blue">工作任务类</b></p>
    </div>

    <div class="kctjcon">
        <div class="sm_div mlr30" style="padding:10px;">
            基本设置：<br /><br />
            整体权重：<input type="text" value="" name="attr2_widget"  class="width80 j-notnull" />
            &nbsp; <input type="checkbox" name="write_by_lead_status" /> 由直接领导设置
            &nbsp; <input type="checkbox" name="write_by_staff_status" /> 由员工设置
        </div>
    </div>
    <div class="kctjcon">
        <div class="sm_div mlr30">
            <table class="sm_xsmbadd" width="100%">
                <tr>
                    <td width="43%">
                        <div class="smfl">
                            <span><em class="c-yel">*</em>工作任务名称： </span>
                            <input type="text" value="工作任务1" name="attr2_name" class="width160 j-notnull" />
                        </div>
                    </td>
                    <td width="17%" class="sm_xsmbadd_td2">
                        <div class="smfl">
                            <span><em class="c-yel">*</em> 计算公式：</span>
                            <input type="text" value="" name="attr2_gs"  class="width40 j-notnull" />
                        </div>
                    </td>
                    <td width="15%" class="sm_xsmbadd_td2">
                        <div class="smfl">
                            <span><em class="c-yel">*</em> 权重：</span>
                            <input type="text" value="" name="attr2_qz"  class="width40 j-notnull" />
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
    <div class="sm_target"><a href="javascript:void(0);">添加任务</a></div>
</div>