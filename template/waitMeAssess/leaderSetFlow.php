<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=gb2312" />
    <meta name="keywords" content="" />
    <meta name="description" content="" />
    <link href="<?=P_CSSPATH?>reset.css" rel="stylesheet" type="text/css" />
    <link href="<?=P_CSSPATH?>right.css" rel="stylesheet" type="text/css" />
    <script src="<?=P_JSPATH?>jquery.1.11.1.js" type="text/javascript"></script>
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

            $('#saveBtn').click(function(){
                if(!AssessInstance.validAssessType()){
                    return false;
                }

                if(!AssessInstance.validTrEmpty()){
                    return false;
                }

                if($("#sub_form").valid()){
                    var formData = {
                        m:'myassessment',
                        a:'waitMeAssess',
                        act:'leaderSetFlow',
                        status:'save'
                    };
                    formData.attrData = AssessInstance.getAttrData();
                    formData.base_id = $("#hidden_base_id").val();
                    formData.userId = $("#hidden_user_id").val();
                    formData.rpItem = AssessInstance.getRpItems();
                    formData.plupFileList = AssessInstance.getPlugList();
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

            $("#backBtn").click(function(){
                var status = $(this).attr('tag');
                var formData = {
                    m:'myassessment',
                    a:'waitMeAssess',
                    act:'leaderSetFlow',
                    status:status
                };
                formData.attrData = AssessInstance.getAttrData();
                formData.base_id = $("#hidden_base_id").val();
                formData.userId = $("#hidden_user_id").val();
                formData.rpItem = AssessInstance.getRpItems();
                formData.plupFileList = AssessInstance.getPlugList();
                art.dialog.prompt('请输入驳回理由！',function(reject){
                    if(!reject){
                        alert('驳回理由必填');
                        return false;
                    }
                    formData.reject = reject;
                    $.ajax({
                        type:'post',
                        url:'/salary/index.php',
                        data:formData,
                        dataType:'json',
                        success:function(retData){
                            if(retData.status=='success'){
                                art.dialog({lock:true});
                                art.dialog.tips('驳回成功',2);
                                var url = "<?=P_SYSPATH."index.php?m=myassessment&a=waitMeAssess&act=myStaffList&".$conditionUrl?>";
                                AssessInstance.jump(url,2000);
                            }
                        }
                    });
                });
            });

            $("#nextBtn,#startBtn").click(function(){
                var valid = true;
                if($(this).attr('id')!='backBtn'){
                     valid = $("#sub_form").valid();
                }

                if(valid){
                    if($(this).attr('sp')!=1 && !AssessInstance.validAssessType()){
                        return false;
                    }

                    if(!AssessInstance.validTrEmpty()){
                        return false;
                    }

                    var status = $(this).attr('tag');
                    var formData = {
                        m:'myassessment',
                        a:'waitMeAssess',
                        act:'leaderSetFlow',
                        status:status
                    };
                    var confirmMsg = {
                        next:'您确定此考核审核通过么?',
                        start:'您确定直接开始此考核么?'
                    };

                    if($(this).attr('sp')==1){
                        confirmMsg.next = '您确定此考核由员工设置么？';
                    }



                    formData.attrData = AssessInstance.getAttrData();
                    formData.base_id = $("#hidden_base_id").val();
                    formData.userId = $("#hidden_user_id").val();
                    formData.rpItem = AssessInstance.getRpItems();
                    formData.plupFileList = AssessInstance.getPlugList();
                    var expectCalMsg = AssessInstance.addExpectCalMsg(formData.attrData.fromData);
                    if(expectCalMsg){
                        confirmMsg.next = expectCalMsg+confirmMsg.next;
                    }
                    art.dialog.confirm(confirmMsg[status],function(){
                        $.ajax({
                            type:'post',
                            url:'/salary/index.php',
                            data:formData,
                            dataType:'json',
                            success:function(retData){
                                if(retData.status=='success'){
                                    art.dialog({lock:true});
                                    art.dialog.tips('操作成功',2);
                                    var url = "<?=P_SYSPATH."index.php?m=myassessment&a=waitMeAssess&act=myStaffList&".$conditionUrl?>";
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
        <p class="icon1">待我考核 > <?=AssessFlowDao::$UserAssessStatusByLeader[$record_info['relation']['user_assess_status']]?></p>
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
                            <?=$record_info['relation']['username']?>
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
                            <input <?=$mValid->getDisableValid('assess_attr_type')?> type="checkbox" name="assess_attr_type" value="1" <?=($record_info['relation']['assess_attr_type']==1)?"checked=\"checked\"":"";?>>任务/指标类&nbsp;
                            <input <?=$mValid->getDisableValid('assess_attr_type')?>  type="checkbox" name="assess_attr_type" value="2" <?=($record_info['relation']['assess_attr_type']==2)?"checked=\"checked\"":"";?>>打分类&nbsp;
                            <input <?=$mValid->getDisableValid('assess_attr_type')?> type="checkbox" name="assess_attr_type" value="3" <?=($record_info['relation']['assess_attr_type']==3)?"checked=\"checked\"":"";?>>提成类&nbsp;
                        </td>
                    </tr>
                </table>
            </div>
            <div class="pad25">
                <?php
                $scoreList = array();
                if($record_info['relation']['user_assess_status']==5){
                    $scoreList['selfScore'] = true;
                    $scoreList['leadScore'] = true;
                }
                ?>

                <!--对比记录模块-->
                <?=$assessAttrWidget->compareHistory($record_info);?>

                <div class="attr_content">
                    <!--任务/指标类-->
                    <?=$assessAttrWidget->renderAttr($record_info['item'],1,$scoreList,$mValid)?>

                    <!--打分类-->
                    <?=$assessAttrWidget->renderAttr($record_info['item'],2,$scoreList,$mValid)?>

                    <!--提成类-->
                    <?=$assessAttrWidget->renderAttr($record_info['item'],3,$scoreList,$mValid)?>
                </div>
                <?php if($record_info['relation']['user_assess_status']==AssessFlowDao::AssessRealLeadView){?>
                    <?=$assessAttrWidget->rewardPunish($record_info['relation'])?>
                    <?=$assessAttrWidget->pluploadPlugin($record_info['plupFileList'])?>
                <?php }?>


            </div>
            <div class="kctjbot">
                <input type="button" name="saveBtn" class="bluebtn" value="保存" id="saveBtn" tag="save" />
                <?php if($record_info['relation']['user_assess_status']==0){?>
                    <input name="nextBtn" type="button" class="bluebtn" value="由员工创建" id="nextBtn" tag="next" sp="1" />
                    <input name="startBtn"  type="button" class="bluebtn" value="开始考核" id="startBtn" tag="start" />
                <?php }?>

                <?php if(in_array($record_info['relation']['user_assess_status'],array(2,5))){?>
                    <input name="nextBtn"   type="button" class="bluebtn" value="审核通过" id="nextBtn" tag="next" />
                    <input  name="backBtn"  type="button" class="bluebtn" value="驳回" id="backBtn" tag="back" sp="3"/>
                <?php }?>
                <input type="button" name="back" class="btn67" value="返回"  onclick="history.go(-1);"/>
            </div>
        </form>
    </div>
</div>
<div class="tck" style="display:none;"></div>
</body>
</html>
