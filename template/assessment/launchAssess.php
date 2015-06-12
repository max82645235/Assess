<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=gbk" />
    <meta name="keywords" content="" />
    <meta name="description" content="" />
    <title>发起考核</title>
    <link href="<?=P_CSSPATH?>reset.css" rel="stylesheet" type="text/css" />
    <link href="<?=P_CSSPATH?>right.css" rel="stylesheet" type="text/css" />
    <link href="<?=P_CSSPATH?>calendar-new.css" rel="stylesheet" type="text/css" />
    <script src="<?=P_JSPATH?>jquery.1.11.1.js" type="text/javascript"></script>
    <script src="<?=P_JSPATH?>jquery.autocomplete.min.js" type="text/javascript"></script>
    <script src="<?=P_JSPATH?>calendar-new.js" type="text/javascript"></script>
    <script src="<?=P_JSPATH?>calendar-setup-new.js" type="text/javascript"></script>
    <script src="<?=P_JSPATH?>calendar-zh-new.js" type="text/javascript"></script>
    <script src="/static/js/assess/launchAssess.js" type="text/javascript"></script>
    <script>
        var AssessInstance =  new Assess();
        $(function(){
            $("#attr_type_checkboxes_td input").click(function(){
                var v = $(this).val();
                $("#attr_type_checkboxes_td input").each(function(){
                    if($(this).val()!=v){
                        $(this).attr("checked",false);
                    }
                });
                AssessInstance.selectAttrType();
            });

            $("#lead_direct_set_status").click(function(){
                AssessInstance.formSubHandle();
                return false;
                AssessInstance.selectLeadSetStatus();
            });

            $("#sub_form").submit(function(e){
                AssessInstance.formSubHandle();
                return false;
                e.preventDefault();
            });

            $(".sm_target").click(function(){
                var type = $(this).parent('').attr('flag');
                AssessInstance.addItem($(this),type);
            });
        });
    </script>
</head>
<?php
function dateHtml($data,$key){
    if(!isset($data['base_info'][$key])){
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
            <p class="icon1">考核管理 > 发起考核</p>
        </div>
        <div class="kctjcon">
            <p class="tjtip">注：*为必填项</p>

            <form action="" method="post" id="sub_form" class="clearfix" >
                <table cellpadding="0" cellspacing="0" width="100%">
                    <tr>
                        <td width="188" align="right"><em class="c-yel">*</em> 考核名称：&nbsp;</td>
                        <td>
                            <input type="text" name="title" id="base_name" value="<?=isset($record_info['base_info']['base_name'])?$record_info['base_info']['base_name']:'';?>" class="width190" />
                        </td>
                    </tr>
                    <tr>
                      <!--  @todo 业务部门二级分类-->
                        <td align="right" valign="top"><em class="c-yel">*</em> 业务单元：&nbsp;</td>
                        <td>
                            <div class="jssel" style="z-index:98">
                                <select id="bus_area_parent" name="bus_area_parent">
                                    <option value="1">房产事业部</option>
                                    <option value="2">房产事业部1</option>
                                    <option value="3">房产事业部2</option>
                                </select>
                            </div>

                            <div class="jssel" style="z-index:49">
                                <select id="bus_area_child" name="bus_area_child">
                                    <option value="1">南京大区1</option>
                                    <option value="2">南京大区2</option>
                                    <option value="3">南京大区3</option>
                                </select>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <!--  @todo 被考核人列表选择-->
                        <td align="right" valign="top"><em class="c-yel">*</em> 被考核人：&nbsp;</td>
                        <td>
                            <input type="text" value=""  placeholder="请输入" name="username" id="username" class="width190"  />
                            <input type="hidden" name="uids" id="uids" value="" />
                            <input type="button" class="btn48 adduser" value="添加" />
                            <input type="button" class="btn74 getuserlist" style="margin:0;" value="选择用户" />
                        </td>
                    </tr>
                    <tr>
                        <td align="right"><em class="c-yel">*</em> 考核周期：&nbsp;</td>
                        <td class="jsline">
                            <select name="assess_period_type" id="assess_period_type">
                                <option value="1">一个月</option>
                                <option value="2">一季度</option>
                                <option value="3">半年</option>
                                <option value="4">一年</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td align="right"><em class="c-yel">*</em> 考核开始时间：&nbsp;</td>
                        <td class="jsline">
                            <?=dateHtml($record_info,'base_start_date');?>
                        </td>
                    </tr>
                    <tr>
                        <td align="right"><em class="c-yel">*</em> 考核计划员工填写时间：&nbsp;</td>
                        <td class="jsline">
                            <?=dateHtml($record_info,'staff_plan_start_date');?>
                            <div class="data" style="margin-right:6px;_margin-right:8px;">―</div>
                            <?=dateHtml($record_info,'staff_plan_end_date');?>
                        </td>
                    </tr>
                    <tr>
                        <td align="right"><em class="c-yel">*</em> 考核计划直接领导审批时间：&nbsp;</td>
                        <td class="jsline">
                            <?=dateHtml($record_info,'lead_plan_start_date');?>
                            <div class="data" style="margin-right:6px;_margin-right:8px;">―</div>
                            <?=dateHtml($record_info,'lead_plan_end_date');?>
                        </td>
                    </tr>
                    <tr>
                        <td align="right"><em class="c-yel">*</em> 考核提报员工填写时间：&nbsp;</td>
                        <td class="jsline">
                            <?=dateHtml($record_info,'staff_sub_start_date');?>
                            <div class="data" style="margin-right:6px;_margin-right:8px;">―</div>
                            <?=dateHtml($record_info,'staff_sub_end_date');?>
                        </td>
                    </tr>
                    <tr>
                        <td align="right"><em class="c-yel">*</em> 考核提报直接领导审批时间：&nbsp;</td>
                        <td class="jsline">
                            <?=dateHtml($record_info,'lead_sub_start_date');?>
                            <div class="data" style="margin-right:6px;_margin-right:8px;">―</div>
                            <?=dateHtml($record_info,'lead_sub_end_date');?>
                        </td>
                    </tr>
                    <tr>
                        <td align="right">考核类型选择：&nbsp;</td>
                        <td id="attr_type_checkboxes_td">
                            <input type="checkbox" name="assess_attr_type" value="1">[任务/指标]类&nbsp;
                            <input type="checkbox" name="assess_attr_type" value="2">打分类&nbsp;
                            <input type="checkbox" name="assess_attr_type" value="3">提成类&nbsp;
                        </td>
                    </tr>
                    <tr>
                        <td align="right"><em class="c-yel">*</em> 由直接领导设置：&nbsp;</td>
                        <td>
                            <input type="checkbox" name="lead_direct_set_status"  value="1" id="lead_direct_set_status">
                        </td>
                    </tr>
                </table>

                <div class="attr_content">
                    <!--任务/指标类-->
                    <?=$assessAttrWidget->renderAttr($record_info,1)?>

                    <!--打分类-->
                    <?=$assessAttrWidget->renderAttr($record_info,2)?>

                    <!--提成类-->
                    <?=$assessAttrWidget->renderAttr($record_info,3)?>
                </div>


                <div class="kctjbot">
                    <input type="submit" class="bluebtn" value="确定" />
                    <input type="button" class="btn67" value="返回"  />
                </div>
            </form>
        </div>
    </div>
</body>
</html>
