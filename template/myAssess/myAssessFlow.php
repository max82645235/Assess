<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=gb2312" />
    <meta name="keywords" content="" />
    <meta name="description" content="" />
    <link href="<?=P_CSSPATH?>reset.css" rel="stylesheet" type="text/css" />
    <link href="<?=P_CSSPATH?>right.css" rel="stylesheet" type="text/css" />
    <script src="<?=P_JSPATH?>jquery.1.11.1.js" type="text/javascript"></script>
    <script src="<?=P_SYSPATH?>static/js/assess/launchAssess.js" type="text/javascript"></script>
    <link rel="stylesheet" href="http://newscms.house365.com/js/artDialog/skins/idialog.css">
    <script type="text/javascript" src="http://newscms.house365.com/js/artDialog/artDialog.js?skin=idialog"></script>
    <script type="text/javascript" src="http://newscms.house365.com/js/artDialog/plugins/iframeTools.js"></script>

    <script>
        var AssessInstance =  new Assess();
        $(function(){
            $(".commission_indicator_parent").each(function(){
                AssessInstance.triggerIndicatorSelect($(this));//刚进页面时触发一次指标分类二级联动ajax查询
            });


            //追加属性节点
            $(".sm_target").click(function(){
                var type = $(this).parent('').attr('flag');
                AssessInstance.addItem($(this),type);
            });

            $('#saveBtn').click(function(){
                var formData = {
                    m:'myassessment',
                    a:'myAssess',
                    act:'myAssessFlow',
                    status:'save'
                };
                formData.attrData = AssessInstance.getAttrData();
                formData.base_id = $("#hidden_base_id").val();
                formData.userId = $("#hidden_user_id").val();
                $.ajax({
                    type:'post',
                    url:'/salary/index.php',
                    data:formData,
                    dataType:'json',
                    success:function(retData){
                        if(retData.status=='success'){
                            art.dialog.tips('保存成功！');

                        }
                    }
                });
            });

            $("#nextBtn").click(function(){
                var formData = {
                    m:'myassessment',
                    a:'myAssess',
                    act:'myAssessFlow',
                    status:'next'
                };
                formData.attrData = AssessInstance.getAttrData();
                formData.base_id = $("#hidden_base_id").val();
                formData.userId = $("#hidden_user_id").val();
                art.dialog.confirm('您确定进入下一步？',function(){
                    $.ajax({
                        type:'post',
                        url:'/salary/index.php',
                        data:formData,
                        dataType:'json',
                        success:function(retData){
                            if(retData.status=='success'){
                                art.dialog({lock:true});
                                art.dialog.tips('保存成功',2);
                                var url = "<?=P_SYSPATH."index.php?m=myassessment&a=myAssess&act=myAssessList&".$conditionUrl?>";
                                AssessInstance.jump(url,2000);
                            }
                        }
                    });
                });
            });
        });

    </script>
    <style>
        .jbtab tr th{color: #3186c8;font-weight:600;}
        .jbtab tr td{color: #3186c8;}
        fieldset{
            padding: 10px;
            margin: 10px;
            width: 100%;

        }
        legend{  display: block;
            width: 100%;
            padding: 0;
            margin-bottom: 20px;
            font-size: 21px;
            line-height: 40px;
            color: #333;
            border: 0;
            border-bottom: 1px solid #e5e5e5;
            font-weight: 800;
            background: #fff;
        }
    </style>
</head>
<body>
<div class="bg">
    <div class="rtop">
        <p class="icon1">我的考核 > <?=AssessFlowDao::$UserAssessStatusByLeader[$record_info['relation']['user_assess_status']]?></p>
    </div>
    <fieldset>
        <legend>考核人姓名：<span style="color:#8DDB75;"><?=$record_info['relation']['username']?></span></legend>
        <div class="kctjcon">
            <p class="tjtip">考核基本信息</p>
            <form action="" method="post" id="sub_form" class="clearfix" >
                <input type="hidden" id="hidden_user_id" value="<?=$record_info['relation']['userId']?>"/>
                <input type="hidden" id="hidden_base_id" value="<?=$record_info['relation']['base_id']?>"/>
                <div class="baseinfo">
                    <table cellpadding="0" cellspacing="0" width="100%">
                        <?=$assessAttrWidget->renderTableBaseInfo($record_info['relation']['base_id'])?>
                        <tr>
                            <td align="right">考核类型选择：&nbsp;</td>
                            <td id="attr_type_checkboxes_td">
                                <input type="checkbox" disabled="disabled" name="assess_attr_type" value="1" <?=($record_info['relation']['assess_attr_type']==1)?"checked=\"checked\"":"";?>>[任务/指标]类&nbsp;
                                <input type="checkbox" disabled="disabled"  name="assess_attr_type" value="2" <?=($record_info['relation']['assess_attr_type']==2)?"checked=\"checked\"":"";?>>打分类&nbsp;
                                <input type="checkbox" disabled="disabled" name="assess_attr_type" value="3" <?=($record_info['relation']['assess_attr_type']==3)?"checked=\"checked\"":"";?>>提成类&nbsp;
                            </td>
                        </tr>
                    </table>
                </div>
                <div class="pad25">
                    <div class="attr_content">
                        <!--任务/指标类-->
                        <?=$assessAttrWidget->renderAttr($record_info['item'],1)?>

                        <!--打分类-->
                        <?=$assessAttrWidget->renderAttr($record_info['item'],2)?>

                        <!--提成类-->
                        <?=$assessAttrWidget->renderAttr($record_info['item'],3)?>
                    </div>
                </div>
                <div class="kctjbot">
                    <input type="button" class="bluebtn" value="保存" id="saveBtn" />
                    <input type="button" class="bluebtn" value="下一步" id="nextBtn" />
                    <input type="button" class="btn67" value="返回"  onclick="history.go(-1);"/>
                </div>
            </form>
        </div>
    </fieldset>
</div>
<div class="tck" style="display:none;"></div>
</body>
</html>
