<table cellpadding="0" cellspacing="0" width="100%">
    <tr>
        <td width="188" align="right"> 考核名称：&nbsp;</td>
        <td>
          <?=$baseInfo['base_name']?>
        </td>
    </tr>
    <tr>
        <td align="right" valign="top">业务单元：&nbsp;</td>
        <td>
            <div class="jssel" style="z-index:98">
                <?=$baseInfo['bus_area_parent_name']?> &nbsp;->&nbsp; <?=$baseInfo['bus_area_child_name']?>
            </div>

        </td>
    </tr>

    <tr>
        <td align="right">考核时间：&nbsp;</td>
        <td class="jsline">
            <?=$baseInfo['base_start_date']?>
            &nbsp;-&nbsp;
            <?=$baseInfo['base_end_date']?>
            &nbsp;&nbsp;
            [<?=AssessDao::$AssessPeriodTypeMaps[$baseInfo['assess_period_type']]?>]
        </td>
    </tr>

    <tr>
        <td align="right">考核计划员工填写时间：&nbsp;</td>
        <td class="jsline">
            <?=$baseInfo['staff_plan_start_date']?>
            &nbsp;-&nbsp;
            <?=$baseInfo['staff_plan_end_date']?>
            &nbsp;&nbsp;
            [已完成]
        </td>
    </tr>

    <tr>
        <td align="right">考核计划直接领导审批时间：&nbsp;</td>
        <td class="jsline">
            <?=$baseInfo['lead_plan_start_date']?>
            &nbsp;-&nbsp;
            <?=$baseInfo['lead_plan_end_date']?>
            &nbsp;&nbsp;
            [已完成]
        </td>
    </tr>

    <tr>
        <td align="right">考核提报员工填写时间：&nbsp;</td>
        <td class="jsline">
            <?=$baseInfo['staff_sub_start_date']?>
            &nbsp;-&nbsp;
            <?=$baseInfo['staff_sub_end_date']?>
            &nbsp;&nbsp;
            [已完成]
        </td>
    </tr>

    <tr>
        <td align="right">考核提报直接领导审批时间：&nbsp;</td>
        <td class="jsline">
            <?=$baseInfo['lead_plan_start_date']?>
            &nbsp;-&nbsp;
            <?=$baseInfo['lead_plan_end_date']?>
            &nbsp;&nbsp;
            [已完成]
        </td>
    </tr>

    <tr>
        <td align="right">考核类型选择：&nbsp;</td>
        <td id="attr_type_checkboxes_td">
            <input type="checkbox" name="assess_attr_type" value="1" <?=($baseInfo['assess_attr_type']==1)?"checked=\"checked\"":"";?>>[任务/指标]类&nbsp;
            <input type="checkbox" name="assess_attr_type" value="2" <?=($baseInfo['assess_attr_type']==2)?"checked=\"checked\"":"";?>>打分类&nbsp;
            <input type="checkbox" name="assess_attr_type" value="3" <?=($baseInfo['assess_attr_type']==3)?"checked=\"checked\"":"";?>>提成类&nbsp;
        </td>
    </tr>
</table>