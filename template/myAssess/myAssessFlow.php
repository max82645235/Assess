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
                            art.dialog.tips('����ɹ���');

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
                art.dialog.confirm('��ȷ���ύ�����������ô��',function(){
                    $.ajax({
                        type:'post',
                        url:'/salary/index.php',
                        data:formData,
                        dataType:'json',
                        success:function(retData){
                            if(retData.status=='success'){
                                art.dialog({lock:true});
                                art.dialog.tips('����ɹ�',2);
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
        <p class="icon1">�ҵĿ��� > <?=AssessFlowDao::$UserAssessStatusByLeader[$record_info['relation']['user_assess_status']]?></p>
    </div>
    <fieldset>
        <legend>������������<span style="color:#8DDB75;"><?=$record_info['relation']['username']?></span></legend>
        <div class="kctjcon">
            <p class="tjtip">���˻�����Ϣ</p>
            <form action="" method="post" id="sub_form" class="clearfix" >
                <input type="hidden" id="hidden_user_id" value="<?=$record_info['relation']['userId']?>"/>
                <input type="hidden" id="hidden_base_id" value="<?=$record_info['relation']['base_id']?>"/>
                <div class="baseinfo">
                    <table cellpadding="0" cellspacing="0" width="100%">
                        <?=$assessAttrWidget->renderTableBaseInfo($record_info['relation']['base_id'],$record_info['relation']['userId'])?>
                        <tr>
                            <td align="right">��������ѡ��&nbsp;</td>
                            <td id="attr_type_checkboxes_td">
                                <input type="checkbox" <?php if($record_info['relation']['user_assess_status']!=1){?> disabled="disabled" <?php }?> name="assess_attr_type" value="1" <?=($record_info['relation']['assess_attr_type']==1)?"checked=\"checked\"":"";?>>[����/ָ��]��&nbsp;
                                <input type="checkbox" <?php if($record_info['relation']['user_assess_status']!=1){?> disabled="disabled" <?php }?>  name="assess_attr_type" value="2" <?=($record_info['relation']['assess_attr_type']==2)?"checked=\"checked\"":"";?>>�����&nbsp;
                                <input type="checkbox" <?php if($record_info['relation']['user_assess_status']!=1){?> disabled="disabled" <?php }?> name="assess_attr_type" value="3" <?=($record_info['relation']['assess_attr_type']==3)?"checked=\"checked\"":"";?>>�����&nbsp;
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
                        <!--����/ָ����-->
                        <?=$assessAttrWidget->renderAttr($record_info['item'],1,$scoreList)?>

                        <!--�����-->
                        <?=$assessAttrWidget->renderAttr($record_info['item'],2,$scoreList)?>

                        <!--�����-->
                        <?=$assessAttrWidget->renderAttr($record_info['item'],3,$scoreList)?>
                    </div>
                </div>
                <div class="kctjbot">
                    <input type="button" class="bluebtn" value="����" id="saveBtn" />
                    <?php if(in_array($record_info['relation']['user_assess_status'],array(1,4))){?>
                        <input type="button" class="bluebtn" value="�ύ���" id="nextBtn" />
                    <?php }?>
                    <input type="button" class="btn67" value="����"  onclick="history.go(-1);"/>
                </div>
            </form>
        </div>
    </fieldset>
</div>
<div class="tck" style="display:none;"></div>
</body>
</html>
