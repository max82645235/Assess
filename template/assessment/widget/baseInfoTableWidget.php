    <tr>
        <td width="188" align="right"> �������ƣ�&nbsp;</td>
        <td>
          <?=$baseInfo['base_name']?>
        </td>
    </tr>
    <tr>
        <td align="right" valign="top">ҵ��Ԫ��&nbsp;</td>
        <td>
            <div class="jssel" style="z-index:98">
                <?=$baseInfo['bus_area_parent_name']?> &nbsp;->&nbsp; <?=$baseInfo['bus_area_child_name']?>->&nbsp;<?=isset($baseInfo['bus_area_third_name'])?$baseInfo['bus_area_third_name']:'';?>
            </div>

        </td>
    </tr>

    <tr>
        <td align="right">����ʱ�䣺&nbsp;</td>
        <td class="jsline">
            <?=$baseInfo['base_start_date']?>
            &nbsp;-&nbsp;
            <?=$baseInfo['base_end_date']?>
            &nbsp;&nbsp;
            [<?=AssessDao::$AssessPeriodTypeMaps[$baseInfo['assess_period_type']]?>]
        </td>
    </tr>


    <tr>
        <td align="right">�����ᱨԱ����дʱ�䣺&nbsp;</td>
        <td class="jsline">
            <span style="float: left;">
                <?=$baseInfo['staff_sub_start_date']?>
            </span>
        </td>
    </tr>

