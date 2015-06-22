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
