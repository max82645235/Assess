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
        var defaultUids = [<?=$_REQUEST['uids']?>];
        $(function(){
            $("#username").autocomplete({
                source: function( request, response ) {
                    var s = $("#username").val();
                    var pid = $("#bus_area_parent").val();
                    var cid = $("#bus_area_child").val();

                    if(pid==''|| cid==''){
                        $("#username").removeClass('ui-autocomplete-loading');
                        return false;
                    }

                    $("#username").addClass('ui-autocomplete-loading');

                    $.ajax({
                        type:'get',
                        url: '<?=P_SYSPATH."index.php?m=assessment&a=launchAssess&act=autoUserName"?>',
                        dataType: "json",
                        data:{
                            s:s,
                            pid:pid,
                            cid:cid
                        },
                        success:  function( data ) {
                            $("#username").removeClass('ui-autocomplete-loading');
                            response($.map( data, function( item ) {
                                var retList = {id:item.id,value:item.value,label:item.label};
                                return retList;
                            }));
                        }
                    });
                },
                minLength: 1,
                select:  function( event, ui ) {
                    $("#table_style tbody tr:gt(0)").each(function(){
                        if($(this).attr('userId')==ui.item.id){
                            $(this).find('td:eq(0) .table_item_checkbox').prop("checked",true);
                        }
                    });
                    $("#username").val('');
                }
            });

            $("#clearBtn").click(function(){
                $("#username").val('');
            });
            $(".saveBtn").click(function(){
               var newUids = [];
                var in_array = function(val,arr){
                    var ret = false;
                    for(var i=0;i<arr.length;i++){
                        if(val==arr[i]){
                            ret = true;
                        }
                    }
                    return ret;
                };
                var remainDefaultUids = [];
                var delDefaultUids = [];
                $("#table_style tbody tr:gt(0)").each(function(){
                    if($(this).find('td:eq(0) .table_item_checkbox').is(":checked")){
                        var tmp = {};
                        tmp.userId = $(this).attr('userId');
                        tmp.username = $(this).attr('username');
                        if(!in_array(tmp.userId,defaultUids)){
                            newUids.push(tmp);
                        }else{
                            remainDefaultUids.push(tmp.userId);
                        }
                    }
                });


                for(var i=0;i<defaultUids.length;i++){
                    if(!in_array(defaultUids[i],remainDefaultUids)){
                        delDefaultUids.push(defaultUids[i]);
                    }
                }
                var origin = artDialog.open.origin
                origin.window.AssessInstance.userListTrigger(newUids,delDefaultUids);
                art.dialog.close();
            });
        });
    </script>
</head>
<body>
<div class="bg">
    <div class="rtop">
        <p class="icon1">HR考核管理 > 创建考核计划 > 用户列表</p>
    </div>
    <div class="pad25">
        <div class="brdbt zykc" style="height: 50px;">
            <form name="frm" action="" method="get" class="clearfix" style="float: left;width: 90%;">
                <input type="hidden" name="bus_area_parent" id="bus_area_parent" value="<?=$pid?>">
                <input type="hidden" name="bus_area_child" id="bus_area_child" value="<?=$cid?>">
                <div class="sechk" style="margin-top: 5px;clear: both;float: left;">
                    考核人姓名：<input type="text" value="" name="username" id="username" class="width135" placeholder="请输入考核人"  >
                </div>
                <input type="button" value="清空" class="btn48"  style="float: left;margin-top: 5px;" id="clearBtn">
                <input type="button" value="保存" class="btn48 saveBtn"  style="float: left;margin-top: 5px;margin-left: 5px;">
            </form>
        </div>
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
                            <td class="left"><?=$data['username']?></td>
                            <td class="left"><?=$data['deptlist']?></td>
                        </tr>
                    <?php }?>
                <?php }?>

            </table>
            <input type="button" value="保存" class="btn48 saveBtn"  style="float: left;margin-top: 5px;">
        </div>
    </div>
</div>
</body>
</html>
