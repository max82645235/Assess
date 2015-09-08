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
    <link rel="stylesheet" href="<?=P_SYSPATH?>static/js/artDialog/skins/idialog.css">
    <script type="text/javascript" src="<?=P_SYSPATH?>static/js/artDialog/artDialog.js?skin=idialog"></script>
    <script type="text/javascript" src="<?=P_SYSPATH?>static/js/artDialog/plugins/iframeTools.js"></script>
    <link rel="stylesheet" href="<?=P_SYSPATH?>static/js/jqueryui/jquery-ui.css">
    <script src="<?=P_SYSPATH?>static/js/jqueryui/jquery-ui.js"></script>
    <script src="<?=P_SYSPATH?>static/js/jqueryui/jquery.validate.js"></script>
    <script src="<?=P_SYSPATH?>static/js/jqueryui/jquery-ui.min.js"></script>
    <script src="<?=P_SYSPATH?>static/js/jqueryui/jquery.metadata.js"></script>
    <script src="<?=P_SYSPATH?>static/js/assess/validateAssess.js" type="text/javascript"></script>
    <script src="<?=P_SYSPATH?>static/js/assess/launchAssess.js" type="text/javascript"></script>

    <script>
        var AssessInstance =  new Assess();
        $(function(){
            AssessInstance.initHide();
            $(".commission_indicator_parent").each(function(){
                AssessInstance.triggerIndicatorSelect($(this));//刚进页面时触发一次指标分类二级联动ajax查询
            });

            //点击考核类型checkbox
            $("#attr_type_checkboxes_td input").click(function(){
                var v = $(this).val();
                $("#attr_type_checkboxes_td input").each(function(){
                    if($(this).val()!=v){
                        $(this).attr("checked",false);
                    }
                });
                AssessInstance.selectAttrType();
            });

            //追加属性节点
            $(".sm_target").click(function(){
                var type = $(this).parent('').attr('flag');
                AssessInstance.addItem($(this),type);
            });

            $(".add_reward").click(function(){
                AssessInstance.addRpItem();
            });

            <?php if($record_info['relation']['user_assess_status']!=4){?>
                $('#saveBtn').click(function(){
                    if($("#sub_form").valid() && AssessInstance.submitSelectValid()){
                        if(!AssessInstance.validAssessType()){
                            return false;
                        }

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
                    }else{
                        AssessInstance.scrollToErrorElement();
                    }
                });
            <?php }?>
            $("#nextBtn").click(function(){
                if($("#sub_form").valid() && AssessInstance.submitSelectValid()){
                    var confirmMsg = '您确定提交考核审核申请么';
                    if(!AssessInstance.validAssessType()){
                        return false;
                    }


                    var formData = {
                        m:'myassessment',
                        a:'myAssess',
                        act:'myAssessFlow',
                        status:'next'
                    };
                    formData.attrData = AssessInstance.getAttrData();
                    formData.base_id = $("#hidden_base_id").val();
                    formData.userId = $("#hidden_user_id").val();
                    formData.rpItem = AssessInstance.getRpItems();
                    formData.plupFileList = AssessInstance.getPlugList();
                    var expectCalMsg = AssessInstance.addExpectCalMsg(formData.attrData.fromData);
                    if(expectCalMsg){
                        confirmMsg = expectCalMsg+confirmMsg;
                    }

                    art.dialog.confirm(confirmMsg,function(){
                        $.ajax({
                            type:'post',
                            url:'/salary/index.php',
                            data:formData,
                            dataType:'json',
                            success:function(retData){
                                if(retData.status=='success'){
                                    art.dialog({lock:true});
                                    art.dialog.tips('提交成功',2);
                                    var url = "<?=P_SYSPATH."index.php?m=myassessment&a=myAssess&act=myAssessList&".$conditionUrl?>";
                                    AssessInstance.jump(url,2000);
                                }
                            }
                        });
                    });
                }else{
                    AssessInstance.scrollToErrorElement();
                }
            });
        });

    </script>
    <style>
        .jbtab tr th{font-weight:600;}
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
        <p class="icon1">我的考核 > <?=AssessFlowDao::$UserAssessStatusByStaff[$record_info['relation']['user_assess_status']]?></p>
    </div>
    <div class="kctjcon">
        <p class="tjtip">考核基本信息</p>
        <form action="" method="post" id="sub_form" class="clearfix" >
            <input type="hidden" id="hidden_user_id" value="<?=$record_info['relation']['userId']?>"/>
            <input type="hidden" id="hidden_base_id" value="<?=$record_info['relation']['base_id']?>"/>
            <input type="hidden" id="hidden_user_assess_status" value="<?=$record_info['relation']['user_assess_status']?>"/>
            <div class="baseinfo">
                <table cellpadding="0" cellspacing="0" width="100%">
                    <tr>
                        <td width="188" align="right"> 考核人姓名：&nbsp;</td>
                        <td>
                            <?=$record_info['relation']['username']."(".$record_info['relation']['card_no'].")";?>
                        </td>
                    </tr>
                    <?=$assessAttrWidget->renderTableBaseInfo($record_info['relation']['base_id'],$record_info['relation']['userId'])?>
                    <?php if($record_info['relation']['rejectText']){?>
                        <tr>
                            <td align="right">驳回理由：&nbsp;</td>
                            <td>
                                <span style="color: red;"><?=$record_info['relation']['rejectText']?></span>
                            </td>
                        </tr>
                    <?php }?>
                    <tr>
                        <td align="right">考核类型选择：&nbsp;</td>
                        <td id="attr_type_checkboxes_td">
                            <input type="checkbox" <?php if($record_info['relation']['user_assess_status']!=1){?> disabled="disabled" <?php }?> name="assess_attr_type" value="1" <?=($record_info['relation']['assess_attr_type']==1)?"checked=\"checked\"":"";?>>任务/指标类&nbsp;
                            <input type="checkbox" <?php if($record_info['relation']['user_assess_status']!=1){?> disabled="disabled" <?php }?>  name="assess_attr_type" value="2" <?=($record_info['relation']['assess_attr_type']==2)?"checked=\"checked\"":"";?>>打分类&nbsp;
                            <input type="checkbox" <?php if($record_info['relation']['user_assess_status']!=1){?> disabled="disabled" <?php }?> name="assess_attr_type" value="3" <?=($record_info['relation']['assess_attr_type']==3)?"checked=\"checked\"":"";?>>提成类&nbsp;
                        </td>
                    </tr>

                </table>
            </div>
            <div class="pad25">
                <?php
                $scoreList = array();
                if($record_info['relation']['user_assess_status']==4){
                    $scoreList['selfScore'] = true;
                }
                ?>
                <div class="attr_content">
                    <!--任务/指标类-->
                    <?=$assessAttrWidget->renderAttr($record_info['item'],1,$scoreList,$mValid)?>

                    <!--打分类-->
                    <?=$assessAttrWidget->renderAttr($record_info['item'],2,$scoreList,$mValid)?>

                    <!--提成类-->
                    <?=$assessAttrWidget->renderAttr($record_info['item'],3,$scoreList,$mValid)?>
                </div>
                <?php if($record_info['relation']['user_assess_status']==AssessFlowDao::AssessPreReport){?>
                    <?=$assessAttrWidget->rewardPunish($record_info['relation'])?>
                    <?=$assessAttrWidget->pluploadPlugin($record_info['plupFileList'])?>
                <?php }?>
            </div>
            <div class="kctjbot">
                <?php if($record_info['relation']['user_assess_status']!=4){?>
                     <input type="button" class="bluebtn" value="保存" name="saveBtn"  id="saveBtn" />
                <?php }?>
                <input type="button" class="bluebtn" value="提交审核" name="nextBtn" id="nextBtn" />
                <input type="button" class="btn67" value="返回" name="backBtn" onclick="history.go(-1);"/>
            </div>
        </form>
    </div>

</div>
<div class="tck" style="display:none;"></div>
</body>
</html>
