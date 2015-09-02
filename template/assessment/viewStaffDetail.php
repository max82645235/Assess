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
    <script>
        $(function(){
            $("#hrRejectBtn").click(function(){
                var userId = $('#hidden_user_id').val();
                var base_id = $('#hidden_base_id').val();
                var formData = {
                    m:'assessment',
                    a:'launchList',
                    act:'hrAssessReject',
                    userId:userId,
                    base_id:base_id
                };

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
                                setTimeout('history.go(-1)',1000);
                            }
                        }
                    });
                });
            });

            $("#zip_assess_btn").click(function(){
                var role = $(this).attr('role');
                var baseId = $("#hidden_base_id").val();
                var userId = $("#hidden_user_id").val();
                var posMap = {hr:'onHrViewStaffList',lead:'onLeaderSetFlow',staff:'onMyAssessFlow'};
                Assess.prototype.zipDownload({
                    baseList:[baseId],
                    userList:[userId],
                    pos:posMap[role]
                },role);
            });
        });
    </script>
</head>
<body>
<div class="bg">
    <div class="rtop">
        <p class="icon1">�鿴�������� >  <?=$record_info['relation']['username']?></p>
    </div>
    <div class="kctjcon">
        <p class="tjtip">���˻�����Ϣ</p>
        <form action="" method="post" id="sub_form" class="clearfix" >
            <input type="hidden" id="hidden_user_id" value="<?=$record_info['relation']['userId']?>"/>
            <input type="hidden" id="hidden_base_id" value="<?=$record_info['relation']['base_id']?>"/>
            <div class="baseinfo">
                <table cellpadding="0" cellspacing="0" width="100%">
                    <tr>
                        <td width="188" align="right"> ��������������&nbsp;</td>
                        <td>
                            <?=$record_info['relation']['username']."(".$record_info['relation']['card_no'].")"?>
                        </td>
                    </tr>
                    <?=$assessAttrWidget->renderTableBaseInfo($record_info['relation']['base_id'],$record_info['relation']['userId'])?>
                    <?php if($record_info['relation']['assess_attr_type']){?>
                        <tr>
                            <td align="right">��������ѡ��&nbsp;</td>
                            <td id="attr_type_checkboxes_td">
                                <?php
                                    $assessType = array(
                                        '1'=>'����/ָ����',
                                        '2'=>'�����',
                                        '3'=>'�����'
                                    );
                                    $assessTypeInfo = @$assessType[$record_info['relation']['assess_attr_type']];
                                ?>
                                <span><?=$assessTypeInfo?></span>
                            </td>
                        </tr>
                    <?php }?>
                    <tr>
                        <td align="right">�������ɣ�&nbsp;</td>
                        <td id="attr_type_checkboxes_td">
                            <?php
                                $createOnMonth = ($record_info['base']['create_on_month_status']==1)?'��':'��';
                            ?>
                            <span><?=$createOnMonth?></span>
                        </td>
                    </tr>
                    <tr>
                        <td align="right">ֱ���쵼���ã�&nbsp;</td>
                        <td id="attr_type_checkboxes_td">
                            <?php
                                $leadSet = ($record_info['base']['lead_direct_set_status']==1)?'��':'��';
                            ?>
                            <span><?=$leadSet?></span>
                        </td>
                    </tr>
                    <tr>
                        <td align="right">����״̬��&nbsp;</td>
                        <td id="attr_type_checkboxes_td">
                            <?php
                                if($_REQUEST['act']=='hrViewStaffDetail'){
                                    $statusMap = AssessFlowDao::$UserAssessStatusByHr;
                                }elseif($_REQUEST['act']=='leadViewStaffDetail'){
                                    $statusMap = AssessFlowDao::$UserAssessStatusByLeader;
                                }elseif($_REQUEST['act']=='staffViewStaffDetail'){
                                    $statusMap = AssessFlowDao::$UserAssessStatusByStaff;
                                }
                            ?>
                                <span><?=$statusMap[$record_info['relation']['user_assess_status']]?></span>
                        </td>
                    </tr>
                    <tr>
                        <td align="right">�������֣�&nbsp;</td>
                        <td id="attr_type_checkboxes_td">

                            <span><?=($record_info['relation']['score'])?$record_info['relation']['score']:'';?></span>
                        </td>
                    </tr>
                    <?php if($record_info['relation']['rejectText']){?>
                        <tr>
                            <td align="right">�������ɣ�&nbsp;</td>
                            <td>
                                <span style="color: red;"><?=$record_info['relation']['rejectText']?></span>
                            </td>
                        </tr>
                    <?php }?>
                </table>
            </div>
            <div class="pad25">
                <?php
                     $compareHtml = $assessAttrWidget->compareHistory($record_info);
                     echo $compareHtml;
                ?>

                <div class="attr_content">
                    <!--�������Ա��-->
                    <?php
                        if($compareHtml==''){
                            $assessAttrWidget->renderItemTable($record_info);
                        }
                    ?>
                    <!--������-->
                    <?php if($record_info['plupFileList']){?>
                        <?=$assessAttrWidget->getDownloadArea($record_info['plupFileList'])?>
                    <?php }?>
                </div>
            </div>
            <div class="kctjbot">
                <?php if($record_info['relation']['user_assess_status']>=AssessFlowDao::AssessRealSuccess && isset($auth) && $auth->setIsMy(true)->validIsAuth('hrAssessReject')){?>
                    <input type="button" id="hrRejectBtn" class="bluebtn" value="��鲵��"  />
                <?php }?>

                <?php $roles = array('hr','lead','staff')?>
                <?php foreach($roles as $role){?>
                    <?php if($_REQUEST['act']==$role.'ViewStaffDetail' && $record_info['relation']['user_assess_status']>=AssessFlowDao::AssessRealSuccess){?>
                            <input type="button" id="zip_assess_btn" class="bluebtn" value="���˵���" role="<?=$role?>" />
                        <?php }?>
                <?php }?>
                <input type="button" class="btn67" value="����"  onclick="history.go(-1);"/>
            </div>
        </form>
    </div>
</div>
<div class="tck" style="display:none;"></div>
</body>
</html>
