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


            //׷�����Խڵ�
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
                                art.dialog.tips('����ɹ���');

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
                art.dialog.prompt('�����벵�����ɣ�',function(reject){
                    if(!reject){
                        alert('�������ɱ���');
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
                                art.dialog.tips('���سɹ�',2);
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
                        next:'��ȷ���˿������ͨ��ô?',
                        start:'��ȷ��ֱ�ӿ�ʼ�˿���ô?'
                    };

                    if($(this).attr('sp')==1){
                        confirmMsg.next = '��ȷ���˿�����Ա������ô��';
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
                                    art.dialog.tips('�����ɹ�',2);
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
        <p class="icon1">���ҿ��� > <?=AssessFlowDao::$UserAssessStatusByLeader[$record_info['relation']['user_assess_status']]?></p>
    </div>
    <div class="kctjcon">
        <p class="tjtip">���˻�����Ϣ</p>
        <form action="" method="post" id="sub_form" class="clearfix" >
            <input type="hidden" id="hidden_user_id" value="<?=$record_info['relation']['userId']?>"/>
            <input type="hidden" id="hidden_base_id" value="<?=$record_info['relation']['base_id']?>"/>
            <input type="hidden" id="hidden_user_assess_status" value="<?=$record_info['relation']['user_assess_status']?>"/>
            <div class="baseinfo">
                <table cellpadding="0" cellspacing="0" width="100%">
                    <tr>
                        <td width="188" align="right"> ������������&nbsp;</td>
                        <td>
                            <?=$record_info['relation']['username']?>
                        </td>
                    </tr>
                    <?=$assessAttrWidget->renderTableBaseInfo($record_info['relation']['base_id'],$record_info['relation']['userId'])?>
                    <?php if($record_info['relation']['rejectText']){?>
                        <tr>
                            <td align="right">�������ɣ�&nbsp;</td>
                            <td>
                                <span style="color: red;"><?=$record_info['relation']['rejectText']?></span>
                            </td>
                        </tr>
                    <?php }?>
                    <tr>
                        <td align="right">��������ѡ��&nbsp;</td>
                        <td id="attr_type_checkboxes_td">
                            <input <?=$mValid->getDisableValid('assess_attr_type')?> type="checkbox" name="assess_attr_type" value="1" <?=($record_info['relation']['assess_attr_type']==1)?"checked=\"checked\"":"";?>>����/ָ����&nbsp;
                            <input <?=$mValid->getDisableValid('assess_attr_type')?>  type="checkbox" name="assess_attr_type" value="2" <?=($record_info['relation']['assess_attr_type']==2)?"checked=\"checked\"":"";?>>�����&nbsp;
                            <input <?=$mValid->getDisableValid('assess_attr_type')?> type="checkbox" name="assess_attr_type" value="3" <?=($record_info['relation']['assess_attr_type']==3)?"checked=\"checked\"":"";?>>�����&nbsp;
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

                <!--�Աȼ�¼ģ��-->
                <?=$assessAttrWidget->compareHistory($record_info);?>

                <div class="attr_content">
                    <!--����/ָ����-->
                    <?=$assessAttrWidget->renderAttr($record_info['item'],1,$scoreList,$mValid)?>

                    <!--�����-->
                    <?=$assessAttrWidget->renderAttr($record_info['item'],2,$scoreList,$mValid)?>

                    <!--�����-->
                    <?=$assessAttrWidget->renderAttr($record_info['item'],3,$scoreList,$mValid)?>
                </div>
                <?php if($record_info['relation']['user_assess_status']==AssessFlowDao::AssessRealLeadView){?>
                    <?=$assessAttrWidget->rewardPunish($record_info['relation'])?>
                    <?=$assessAttrWidget->pluploadPlugin($record_info['plupFileList'])?>
                <?php }?>


            </div>
            <div class="kctjbot">
                <input type="button" name="saveBtn" class="bluebtn" value="����" id="saveBtn" tag="save" />
                <?php if($record_info['relation']['user_assess_status']==0){?>
                    <input name="nextBtn" type="button" class="bluebtn" value="��Ա������" id="nextBtn" tag="next" sp="1" />
                    <input name="startBtn"  type="button" class="bluebtn" value="��ʼ����" id="startBtn" tag="start" />
                <?php }?>

                <?php if(in_array($record_info['relation']['user_assess_status'],array(2,5))){?>
                    <input name="nextBtn"   type="button" class="bluebtn" value="���ͨ��" id="nextBtn" tag="next" />
                    <input  name="backBtn"  type="button" class="bluebtn" value="����" id="backBtn" tag="back" sp="3"/>
                <?php }?>
                <input type="button" name="back" class="btn67" value="����"  onclick="history.go(-1);"/>
            </div>
        </form>
    </div>
</div>
<div class="tck" style="display:none;"></div>
</body>
</html>
