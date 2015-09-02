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
    <script src="<?=P_SYSPATH?>static/js/assess/launchAssess.js" type="text/javascript"></script>
    <style>
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
<script>

    $(function(){
        $("#zip_assess_btn").click(function(){
            Assess.prototype.tableBtnHandler($('#table_style'),
                function(jInput){
                    var status = jInput.parent().find('.table_item_status').val();
                    if(status>=6){
                        return true;
                    }else{
                        alert('��ȷ��ѡ�п����˶�Ϊ������״̬��');
                        return false;
                    }
                },
                function(selectedItem){
                    var baseId = $("#base_id").val();
                    Assess.prototype.zipDownload({
                        baseList:[baseId],
                        userList:selectedItem,
                        pos:'onHrViewStaffList'
                    },'hr');
                });
        });
    });

    $.extend({
        delItem:function(userId,baseId,username){
            art.dialog.confirm('��ȷ��ɾ�� <span style="color: darkblue;">'+username+'</span> �Ŀ���ô?��<span style="color: red;">���ܻ�ԭ�����������</span>��',function(){
                $.ajax({
                    type:'get',
                    url: '<?=P_SYSPATH."index.php?m=assessment&a=launchList&act=delUserAssess"?>',
                    dataType: "json",
                    data:{
                        userId:userId,
                        baseId:baseId
                    },
                    success:  function( data ) {
                       if(data.status=='success'){
                           art.dialog.tips('ɾ���ɹ�');
                       }
                        setTimeout(function(){
                            location.reload();
                        },1500);
                    }
                });
            });
        }
    });
</script>
<body>
<div class="bg">
    <div class="rtop">
        <p class="icon1">HR���˹��� > ���������б� > <?=$assessBaseRecord['base_name']?></p>
    </div>
    <div class="pad25">
        <div class="brdbt zykc" style="height: 30px;">

            <form name="frm" action="" method="get" class="clearfix" style="float: left;width: 90%;">
                <input type="hidden" name="m" value="assessment">
                <input type="hidden" name="a" value="launchList">
                <input type="hidden" name="act" value="hrViewStaffList">
                <input type="hidden" name="base_id" value="<?=$_REQUEST['base_id']?>" id="base_id">
                <div class="jssel" style="z-index:98">
                    &nbsp;&nbsp;&nbsp;����״̬��
                    <select name="user_assess_status" style="width: 150px;">
                        <option value=""    <?php if(isset($_REQUEST['user_assess_status']) && $_REQUEST['user_assess_status']===''){?> selected="selected"<?php }?>>��ѡ��</option>
                        <?php foreach(AssessFlowDao::$UserAssessStatusByHr as $k=>$val){?>
                            <option value="<?=$k?>"  <?php if(isset($_REQUEST['user_assess_status']) && $_REQUEST['user_assess_status']!=='' && $_REQUEST['user_assess_status']==$k){?> selected="selected"<?php }?>><?=$val?></option>
                        <?php }?>
                    </select>
                </div>
                <div  class="jssel" style="z-index:98">
                    &nbsp;&nbsp;&nbsp;�������ˣ�
                    <input type="text" value="<?=(isset($_REQUEST['username']))?$_REQUEST['username']:'';?>" name="username" id="username" class="width135" placeholder="���뱻����������"  style="margin-bottom: 3px;">
                </div>
                <div  class="jssel" style="z-index:98;margin-left: 20px;margin-bottom: 5px;">
                    <input type="submit" value="����" class="btn48"  >
                </div>

            </form>
        </div>

        <div class="mrtb10" >
            <table cellpadding="0" cellspacing="0" width="100%" class="jbtab" id="table_style" >
                <tr >
                    <th width="50" style="text-align: center;" >
                        <input type="checkbox" id="top_check_input"  onclick="Assess.prototype.tableTopChecked(this)">
                    </th>
                    <th class="left" width="150"  style="text-align: center;">��������</th>
                    <th style="text-align: center;">����</th>
                    <th width="150" style="text-align: center;">����״̬</th>
                    <th width="150" style="text-align: center;">��Ч����</th>
                    <th  width="100" style="text-align: center;">����</th>
                </tr>

                <?php if($tableData){?>
                    <?php foreach($tableData as $k=>$data){?>
                        <tr class="<?=($k%2)?'bgfff':'bgf0';?>">
                            <td>
                                <input type="checkbox" class="table_item_checkbox" tag="<?=$data['userId']?>">
                                <input type="hidden" class="table_item_status" value="<?=$data['user_assess_status']?>">
                            </td>
                            <td ><?=$data['username']."(".$data['card_no'].")"?></td>
                            <td class="left"><?=$data['deptlist']?></td>
                            <td><?=AssessFlowDao::$UserAssessStatusByHr[$data['user_assess_status']]?></td>
                            <td><?=($data['score'])?$data['score']:'';?></td>
                            <td class="left">
                                <a href="?m=assessment&a=launchList&act=hrViewStaffDetail&userId=<?=$data['userId']?>&base_id=<?=$data['base_id'].$pageConditionUrl?>" class="bjwrt">�鿴</a>
                                <a href="javascript:$.delItem(<?=$data['userId']?>,<?=$data['base_id']?>,'<?=$data['username']?>')" style="color: red;">ɾ��</a>
                            </td>
                        </tr>
                    <?php }?>
                <?php }?>

            </table>
            <p class="pagenum">
                <?=$page_nav?>
            </p>
            <div>
                <?php if($auth->setIsMy(true)->validIsAuth('hrZipAssessPackage')){?>
                    <input type="button" name="" value="���˵���" class="btn139" id="zip_assess_btn" style="cursor:pointer;">
                <?php }?>
            </div>
        </div>
    </div>
</div>
<div class="tck" style="display:none;"></div>
</body>
</html>
