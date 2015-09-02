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
    <link href="<?=P_SYSPATH?>static/js/jqueryui/jquery-ui.css" rel="stylesheet" type="text/css" />
    <script src="<?=P_SYSPATH?>static/js/jqueryui/jquery-ui.js" type="text/javascript"></script>
    <link rel="stylesheet" href="http://newscms.house365.com/js/artDialog/skins/idialog.css">
    <script type="text/javascript" src="http://newscms.house365.com/js/artDialog/artDialog.js?skin=idialog"></script>
    <script type="text/javascript" src="http://newscms.house365.com/js/artDialog/plugins/iframeTools.js"></script>
    <script type="text/javascript">
        var AssessInstance =  new Assess();
        $(function(){
            $(".saveBtn").click(function(){
                var newUids = [];
                var userId = $("#userId").val();
                var base_id = $("#base_id").val();
                $("#table_style tbody tr:gt(0)").each(function(){
                    if($(this).find('td:eq(0) .table_item_checkbox').is(":checked")){
                        newUids.push($(this).attr('userId'));
                    }
                });
                if(newUids.length==0){
                    alert('请先选择考核人');
                    return false;
                }
                $.ajax({
                    type:'get',
                    url:'/salary/index.php',
                    data:{m:'myassessment',a:'waitMeAssess',act:'mulCopyCreateAssess',newUids:newUids,ajax:1,userId:userId,base_id:base_id},
                    dataType:'json',
                    success:function(ret){
                        if(ret.status=='success'){
                            alert('设置成功');
                            art.dialog.close();
                        }
                    }
                });
            });
        });
    </script>
</head>
<body>
<div class="bg">
    <div class="rtop">
        <p class="icon1">待我考核 > 员工列表 > 考核复制  <span style="color: red;">&nbsp;&nbsp;勾选操作后下会覆盖对应考核人的考核设置,不可恢复</span></p>

    </div>
    <div class="pad25">
        <div class="mrtb10" >
            <table cellpadding="0" cellspacing="0" width="100%" class="jbtab" id="table_style" >
                <tr >
                    <th width="50" style="text-align: center;" >
                        <input type="checkbox" id="top_check_input"  onclick="Assess.prototype.tableTopChecked(this)">
                    </th>
                    <th width="200" class="left" style="text-align: center;">考核人姓名</th>
                    <th  style="text-align: center;">部门</th>
                </tr>
                <?php if($userList){?>
                    <?php foreach($userList as $k=>$data){?>
                        <?php $selected = (in_array($data['userId'],$uids))?true:false; ?>
                        <tr class="<?=($k%2)?'bgfff':'bgf0';?>" userId="<?=$data['userId']?>" username="<?=$data['username']?>">
                            <td><input type="checkbox" <?php if($selected){?>checked="checked" <?php }?>  class="table_item_checkbox"></td>
                            <td class="left"><?=$data['username']."(".$data['card_no'].")"?></td>
                            <td class="left"><?=$data['deptlist']?></td>
                        </tr>
                    <?php }?>
                <?php }?>

            </table>
            <input type="hidden" name="userId" value="<?=$_REQUEST['userId']?>" id="userId">
            <input type="hidden" name="base_id" value="<?=$_REQUEST['base_id']?>" id="base_id">
            <input type="button" value="保存" class="btn48 saveBtn"  style="float: left;margin-top: 5px;">
        </div>
    </div>
</div>
</body>
</html>
