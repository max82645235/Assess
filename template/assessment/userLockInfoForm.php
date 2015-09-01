<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=gbk" />
    <meta name="keywords" content="" />
    <meta name="description" content="" />
    <title>创建考核计划</title>
    <link href="<?=P_CSSPATH?>reset.css" rel="stylesheet" type="text/css" />
    <link href="<?=P_CSSPATH?>right.css" rel="stylesheet" type="text/css" />
    <script src="<?=P_JSPATH?>jquery.1.11.1.js" type="text/javascript"></script>
    <link href="<?=P_SYSPATH?>static/js/jqueryui/jquery-ui.css" rel="stylesheet" type="text/css" />
    <script src="<?=P_SYSPATH?>static/js/jqueryui/jquery-ui.js" type="text/javascript"></script>
    <script src="<?=P_SYSPATH?>static/js/assess/launchAssess.js" type="text/javascript"></script>
    <link rel="stylesheet" href="<?=P_SYSPATH?>static/js/artDialog/skins/idialog.css">
    <script type="text/javascript" src="<?=P_SYSPATH?>static/js/artDialog/artDialog.js?skin=idialog"></script>
    <script type="text/javascript" src="<?=P_SYSPATH?>static/js/artDialog/plugins/iframeTools.js"></script>
    <script>
        var AssessInstance =  new Assess();
        $(function(){
            AssessInstance.initHide();
            AssessInstance.triggerBusSelect(1); //刚进页面时触发一次部门二级联动ajax查询
            //业务部门父类选择
            $("#bus_area_parent").change(function(){
                AssessInstance.triggerBusSelect(1);
            });

            //业务部门二级分类选择
            $("#bus_area_child").change(function(){
                AssessInstance.triggerBusThirdSelect(1);
            });

            $("#sub_form").submit(function(e){
                var data = {};
                data.userId = $("#userId").val();
                data.tixi = $("#bus_area_parent").val();
                data.comp_dept = $("#bus_area_child").val();
                data.did = ($("#bus_area_third").val())?$("#bus_area_third").val():0;
                data.lockStatus = ($("#lockStatus").is(":checked"))?1:0;
                data.m = "assessment";
                data.a = "launchAssess";
                data.act = "userLockInfoForm";
                data.ajax = 1;
                $.ajax({
                    type:'get',
                    url:'/salary/index.php',
                    data:data,
                    dataType:'json',
                    success:function(ret){
                        var jump = "<?=P_SYSPATH."index.php?m=auth&a=user"?>";
                        if(ret.status =='success'){
                            art.dialog.tips('保存成功！',1500);
                            Assess.prototype.jump(jump,1500);
                        }
                    }
                });
                e.preventDefault();
            });
        });
    </script>
</head>

<body>
<div class="bg baseInfo_content">
    <div class="rtop">
        <p class="icon1">用户管理 > 用户属性设置</p>
    </div>
    <div class="kctjcon">
        <p class="tjtip">注：*为必填项</p>
        <form action="" method="post" id="sub_form" class="clearfix" >
            <input type="hidden" name="userId" id="userId" value="<?=$record_info['userId'];?>">
            <table cellpadding="0" cellspacing="0" width="100%">
                <tr>
                    <td width="188" align="right"><em class="c-yel">*</em> 用户姓名：&nbsp;</td>
                    <td>
                        <?=$record_info['username']?>
                    </td>
                </tr>
                <tr>
                    <!--  @todo 业务部门二级分类-->
                    <td align="right" valign="top"><em class="c-yel">*</em> 业务单元：&nbsp;</td>
                    <td>
                        <div class="jssel" style="z-index:98">
                            <select id="bus_area_parent" name="bus_area_parent" style="width: 150px;">
                                <?php foreach($bus_parent_list as $k=>$v){?>
                                    <option value="<?=$k?>" <?php if( $record_info['tixi']==$k){?> selected="selected"<?php }?>><?=$v?></option>
                                <?php }?>
                            </select> &nbsp;&nbsp;
                            <input type="hidden" name="bus_area_parent_hidden" id="bus_area_parent_hidden" value="<?=$record_info['tixi'];?>">
                        </div>
                        <div class="jssel" style="z-index:49">
                            <select id="bus_area_child" name="bus_area_child" style="width: 150px;">
                            </select>
                            <input type="hidden" name="bus_area_child_hidden" id="bus_area_child_hidden" value="<?=$record_info['comp_dept'];?>">
                        </div>

                        <div class="jssel" style="z-index:49;margin-left: 20px;">
                            <select id="bus_area_third" name="bus_area_third" style="width: 150px;">
                            </select>
                            <input type="hidden" name="bus_area_third_hidden" id="bus_area_third_hidden" value="<?=$record_info['did'];?>">
                        </div>
                    </td>
                </tr>

                <tr>
                    <td align="right"> 锁定：&nbsp;</td>
                    <td class="jsline">
                        <div style="float: left;padding-top: 10px;">
                            <input type="checkbox" id="lockStatus" name="lockStatus" value="<?=$record_info['lockStatus'];?>"  <?php if($record_info['lockStatus']){?>checked="checked" <?php }?>>&nbsp;&nbsp;
                        </div>
                    </td>
                </tr>
            </table>
            <div class="kctjbot">
                <input type="submit" class="bluebtn" value="保存" />
                <input type="button" class="btn67" value="返回"  name="backBtn" onclick="history.go(-1);"/>
            </div>
        </form>
    </div>
</div>
</body>
</html>
