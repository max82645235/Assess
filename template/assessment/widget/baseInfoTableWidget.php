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
                <?=$baseInfo['bus_area_parent_name']?> &nbsp;->&nbsp; <?=$baseInfo['bus_area_child_name']?>->&nbsp;<?=isset($baseInfo['bus_area_third_name'])?$baseInfo['bus_area_third_name']:'';?>
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
        <td align="right">考核提报员工填写时间：&nbsp;</td>
        <td class="jsline">
            <span style="float: left;">
                <?=$baseInfo['staff_sub_start_date']?>
            </span>
        </td>
    </tr>

