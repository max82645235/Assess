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
                <?=$baseInfo['bus_area_parent_name']?> &nbsp;->&nbsp; <?=$baseInfo['bus_area_child_name']?>
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
        <td align="right">���˼ƻ�Ա����дʱ�䣺&nbsp;</td>
        <td class="jsline">
            <?=$baseInfo['staff_plan_start_date']?>
            &nbsp;-&nbsp;
            <?=$baseInfo['staff_plan_end_date']?>
            &nbsp;&nbsp;
            [�����]
        </td>
    </tr>

    <tr>
        <td align="right">���˼ƻ�ֱ���쵼����ʱ�䣺&nbsp;</td>
        <td class="jsline">
            <?=$baseInfo['lead_plan_start_date']?>
            &nbsp;-&nbsp;
            <?=$baseInfo['lead_plan_end_date']?>
            &nbsp;&nbsp;
            [�����]
        </td>
    </tr>

    <tr>
        <td align="right">�����ᱨԱ����дʱ�䣺&nbsp;</td>
        <td class="jsline">
            <?=$baseInfo['staff_sub_start_date']?>
            &nbsp;-&nbsp;
            <?=$baseInfo['staff_sub_end_date']?>
            &nbsp;&nbsp;
            [�����]
        </td>
    </tr>

    <tr>
        <td align="right">�����ᱨֱ���쵼����ʱ�䣺&nbsp;</td>
        <td class="jsline">
            <?=$baseInfo['lead_plan_start_date']?>
            &nbsp;-&nbsp;
            <?=$baseInfo['lead_plan_end_date']?>
            &nbsp;&nbsp;
            [�����]
        </td>
    </tr>
