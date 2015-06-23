<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=gbk" />
    <meta name="keywords" content="" />
    <meta name="description" content="" />
    <title>���𿼺�</title>
    <link href="<?=P_CSSPATH?>reset.css" rel="stylesheet" type="text/css" />
    <link href="<?=P_CSSPATH?>right.css" rel="stylesheet" type="text/css" />
    <link href="<?=P_CSSPATH?>calendar-new.css" rel="stylesheet" type="text/css" />
    <script src="<?=P_JSPATH?>jquery.1.11.1.js" type="text/javascript"></script>
    <script src="<?=P_JSPATH?>jquery.autocomplete.min.js" type="text/javascript"></script>
    <script src="<?=P_JSPATH?>calendar-new.js" type="text/javascript"></script>
    <script src="<?=P_JSPATH?>calendar-setup-new.js" type="text/javascript"></script>
    <script src="<?=P_JSPATH?>calendar-zh-new.js" type="text/javascript"></script>
    <script src="<?=P_SYSPATH?>static/js/assess/launchAssess.js" type="text/javascript"></script>
    <script>
        var AssessInstance =  new Assess();
        $(function(){
            AssessInstance.triggerBusSelect(false); //�ս�ҳ��ʱ����һ�β��Ŷ�������ajax��ѯ
            $(".commission_indicator_parent").each(function(){
                AssessInstance.triggerIndicatorSelect($(this));//�ս�ҳ��ʱ����һ��ָ������������ajax��ѯ
            });

            //�����������checkbox
            $("#attr_type_checkboxes_td input").click(function(){
                var v = $(this).val();
                $("#attr_type_checkboxes_td input").each(function(){
                    if($(this).val()!=v){
                        $(this).attr("checked",false);
                    }
                });
                AssessInstance.selectAttrType();
            });

            //���ֱ���쵼����checkbox
            $("#lead_direct_set_status").click(function(){
                AssessInstance.selectLeadSetStatus();
            });

            //���ύsub
            $("#sub_form").submit(function(e){
                AssessInstance.formSubHandle();
                location.href = '<?=P_SYSPATH."index.php?m=assessment&a=launchList&".$conditionUrl?>';
                e.preventDefault();
            });

            //׷�����Խڵ�
            $(".sm_target").click(function(){
                var type = $(this).parent('').attr('flag');
                AssessInstance.addItem($(this),type);
            });

            //ҵ���Ÿ���ѡ��
            $("#bus_area_parent").change(function(){
                AssessInstance.triggerBusSelect(false);
            });
        });
    </script>
</head>
<?php
function dateHtml($data,$key){
    if(!isset($data[$key])){
        $data[$key] = '';
    }
    print <<<EOF
<div class="data" style="margin-right:6px;">
                <input type="text" name="{$key}" id="{$key}" value="{$data[$key]}" class="width135" />
                <a href="javascript:void(0);" class="dataicon" id="f_trigger_{$key}"></a>
            </div>
            <script type="text/javascript">
        Calendar.setup({
                    inputField : "{$key}",
                    ifFormat : "%Y-%m-%d",
                    showsTime : false,
                    button : "f_trigger_{$key}",
                    singleClick : false,
                    step : 1
                });
            </script>
EOF;
}
?>
<body>
<div class="bg baseInfo_content">
    <div class="rtop">
        <p class="icon1">���˹��� > ���𿼺�</p>
    </div>
    <div class="kctjcon">
        <p class="tjtip">ע��*Ϊ������</p>

        <form action="" method="post" id="sub_form" class="clearfix" >
            <input type="hidden" name="base_id" id="base_id" value="<?=(isset($record_info['base_info']['base_id']))?$record_info['base_info']['base_id']:'';?>">
            <table cellpadding="0" cellspacing="0" width="100%">
                <tr>
                    <td width="188" align="right"><em class="c-yel">*</em> �������ƣ�&nbsp;</td>
                    <td>
                        <input type="text" name="title" id="base_name" value="<?=(isset($record_info['base_info']['base_name']))?$record_info['base_info']['base_name']:'';?>" class="width190" />
                    </td>
                </tr>
                <tr>
                    <!--  @todo ҵ���Ŷ�������-->
                    <td align="right" valign="top"><em class="c-yel">*</em> ҵ��Ԫ��&nbsp;</td>
                    <td>
                        <div class="jssel" style="z-index:98">
                            <select id="bus_area_parent" name="bus_area_parent" style="width: 150px;">
                                <?php foreach($cfg['tixi'] as $k=>$v){?>
                                    <option value="<?=$k?>" <?php if(isset($record_info['base_info']['base_name']) && $record_info['base_info']['bus_area_parent']==$k){?> selected="selected"<?php }?>><?=$v['title']?></option>
                                <?php }?>
                            </select> &nbsp;&nbsp;
                            <input type="hidden" name="bus_area_parent_hidden" id="bus_area_parent_hidden" value="<?=isset($record_info['base_info']['bus_area_parent'])?$record_info['base_info']['bus_area_parent']:'';?>">
                        </div>

                        <div class="jssel" style="z-index:49">
                            <select id="bus_area_child" name="bus_area_child" style="width: 150px;">
                            </select>
                            <input type="hidden" name="bus_area_child_hidden" id="bus_area_child_hidden" value="<?=isset($record_info['base_info']['bus_area_child'])?$record_info['base_info']['bus_area_child']:'';?>">
                        </div>
                    </td>
                </tr>
                <tr>
                    <!--  @todo ���������б�ѡ��-->
                    <td align="right" valign="top"><em class="c-yel">*</em> �������ˣ�&nbsp;</td>
                    <td>
                        <input type="text" value=""  placeholder="������" name="username" id="username" class="width190"  />
                        <input type="hidden" name="uids" id="uids" value="1,2,876,877" />
                        <input type="button" class="btn48 adduser" value="���" />
                        <input type="button" class="btn74 getuserlist" style="margin:0;" value="ѡ���û�" />
                    </td>
                </tr>
                <tr>
                    <td align="right"><em class="c-yel">*</em> �������ڣ�&nbsp;</td>
                    <td class="jsline">
                        <select name="assess_period_type" id="assess_period_type">
                            <?php foreach(AssessDao::$AssessPeriodTypeMaps as $k=>$v){?>
                                <option value="<?=$k?>"
                                    <?php if($record_info['base_info']['assess_period_type']==$k){?> selected="selected"<?php }?>>
                                    <?=$v?>
                                </option>
                            <?php }?>
                        </select>
                    </td>
                </tr>

                <tr>
                    <td align="right"><em class="c-yel">*</em> �������ɣ�&nbsp;</td>
                    <td>
                        <input type="checkbox" name="create_on_month_status"  value="1" id="create_on_month_status" <?php if(isset($record_info['base_info']['create_on_month_status']) && $record_info['base_info']['create_on_month_status']==1){?>checked="checked" <?php }?>>
                    </td>
                </tr>

                <tr>
                    <td align="right"><em class="c-yel">*</em> ���˿�ʼʱ�䣺&nbsp;</td>
                    <td class="jsline">
                        <?=dateHtml($record_info['base_info'],'base_start_date');?>
                    </td>
                </tr>
                <tr>
                    <td align="right"><em class="c-yel">*</em> ���˼ƻ�Ա����дʱ�䣺&nbsp;</td>
                    <td class="jsline">
                        <?=dateHtml($record_info['base_info'],'staff_plan_start_date');?>
                        <div class="data" style="margin-right:6px;_margin-right:8px;">�D</div>
                        <?=dateHtml($record_info['base_info'],'staff_plan_end_date');?>
                    </td>
                </tr>
                <tr>
                    <td align="right"><em class="c-yel">*</em> ���˼ƻ�ֱ���쵼����ʱ�䣺&nbsp;</td>
                    <td class="jsline">
                        <?=dateHtml($record_info['base_info'],'lead_plan_start_date');?>
                        <div class="data" style="margin-right:6px;_margin-right:8px;">�D</div>
                        <?=dateHtml($record_info['base_info'],'lead_plan_end_date');?>
                    </td>
                </tr>
                <tr>
                    <td align="right"><em class="c-yel">*</em> �����ᱨԱ����дʱ�䣺&nbsp;</td>
                    <td class="jsline">
                        <?=dateHtml($record_info['base_info'],'staff_sub_start_date');?>
                        <div class="data" style="margin-right:6px;_margin-right:8px;">�D</div>
                        <?=dateHtml($record_info['base_info'],'staff_sub_end_date');?>
                    </td>
                </tr>
                <tr>
                    <td align="right"><em class="c-yel">*</em> �����ᱨֱ���쵼����ʱ�䣺&nbsp;</td>
                    <td class="jsline">
                        <?=dateHtml($record_info['base_info'],'lead_sub_start_date');?>
                        <div class="data" style="margin-right:6px;_margin-right:8px;">�D</div>
                        <?=dateHtml($record_info['base_info'],'lead_sub_end_date');?>
                    </td>
                </tr>
                <tr>
                    <td align="right"><em class="c-yel">*</em> ��ֱ���쵼���ã�&nbsp;</td>
                    <td>
                        <input type="checkbox" name="lead_direct_set_status"  value="1" id="lead_direct_set_status" <?php if(isset($record_info['base_info']['lead_direct_set_status']) && $record_info['base_info']['lead_direct_set_status']==1){?>checked="checked" <?php }?>>
                    </td>
                </tr>

                <tr>
                    <td align="right">��������ѡ��&nbsp;</td>
                    <td id="attr_type_checkboxes_td">
                        <input type="checkbox" name="assess_attr_type" value="1" <?=($record_info['base_info']['assess_attr_type']==1)?"checked=\"checked\"":"";?>>[����/ָ��]��&nbsp;
                        <input type="checkbox" name="assess_attr_type" value="2" <?=($record_info['base_info']['assess_attr_type']==2)?"checked=\"checked\"":"";?>>�����&nbsp;
                        <input type="checkbox" name="assess_attr_type" value="3" <?=($record_info['base_info']['assess_attr_type']==3)?"checked=\"checked\"":"";?>>�����&nbsp;
                    </td>
                </tr>
            </table>
            <div class="attr_content">
                <!--����/ָ����-->
                <?=$assessAttrWidget->renderAttr($record_info['attr_info'],1)?>

                <!--�����-->
                <?=$assessAttrWidget->renderAttr($record_info['attr_info'],2)?>

                <!--�����-->
                <?=$assessAttrWidget->renderAttr($record_info['attr_info'],3)?>
            </div>


            <div class="kctjbot">
                <input type="submit" class="bluebtn" value="ȷ��" />
                <input type="button" class="btn67" value="����"  onclick="history.go(-1);"/>
            </div>
        </form>
    </div>
</div>
</body>
</html>
