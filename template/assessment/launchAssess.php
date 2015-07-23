<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=gbk" />
    <meta name="keywords" content="" />
    <meta name="description" content="" />
    <title>创建考核计划</title>
    <link href="<?=P_CSSPATH?>reset.css" rel="stylesheet" type="text/css" />
    <link href="<?=P_CSSPATH?>right.css" rel="stylesheet" type="text/css" />
    <link href="<?=P_CSSPATH?>calendar-new.css" rel="stylesheet" type="text/css" />
    <script src="<?=P_JSPATH?>jquery.1.11.1.js" type="text/javascript"></script>
    <script src="<?=P_JSPATH?>jquery.autocomplete.min.js" type="text/javascript"></script>
    <script src="<?=P_JSPATH?>calendar-new.js" type="text/javascript"></script>
    <script src="<?=P_JSPATH?>calendar-setup-new.js" type="text/javascript"></script>
    <script src="<?=P_JSPATH?>calendar-zh-new.js" type="text/javascript"></script>

    <link href="<?=P_SYSPATH?>static/js/jqueryui/jquery-ui.css" rel="stylesheet" type="text/css" />
    <script src="<?=P_SYSPATH?>static/js/jqueryui/jquery-ui.js" type="text/javascript"></script>

    <link rel="stylesheet" href="<?=P_SYSPATH?>static/js/artDialog/skins/idialog.css">
    <script type="text/javascript" src="<?=P_SYSPATH?>static/js/artDialog/artDialog.js?skin=idialog"></script>
    <script type="text/javascript" src="<?=P_SYSPATH?>static/js/artDialog/plugins/iframeTools.js"></script>

    <link rel="stylesheet" href="<?=P_SYSPATH?>static/js/jqueryui/jquery-ui.css">
    <script src="<?=P_SYSPATH?>static/js/jqueryui/jquery-ui.js"></script>
    <script src="<?=P_SYSPATH?>static/js/jqueryui/jquery.validate.js"></script>
    <script src="<?=P_SYSPATH?>static/js/jqueryui/jquery-ui.min.js"></script>
    <script src="<?=P_SYSPATH?>static/js/jqueryui/jquery.metadata.js"></script>
    <script src="<?=P_SYSPATH?>static/js/assess/launchAssess.js" type="text/javascript"></script>
    <script src="<?=P_SYSPATH?>static/js/assess/validateAssess.js" type="text/javascript"></script>
    <script>
        var AssessInstance =  new Assess();
        $(function(){
            AssessInstance.initHide();
            AssessInstance.triggerBusSelect(0); //刚进页面时触发一次部门二级联动ajax查询
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

            //点击直接领导设置checkbox
            $("#lead_direct_set_status").click(function(){
                AssessInstance.selectLeadSetStatus();
            });

            //表单提交sub
            $("#sub_form").submit(function(e){
                if($.myValidate.errorList.length==0 && AssessInstance.submitSelectValid()){
                    var jumpUrl = '<?=P_SYSPATH."index.php?m=assessment&a=launchList&".$conditionUrl?>';
                    AssessInstance.formSubHandle(jumpUrl,1);
                }
                return false;
                e.preventDefault();
            });

            //发布
            $("#pubBtb").click(function(){
                $("#sub_form").valid();
                if($.myValidate.errorList.length==0 && AssessInstance.submitSelectValid()){
                    var jumpUrl = $("#pubBtb").attr('pubUrl');
                    AssessInstance.formSubHandle(jumpUrl,2);
                }
            });

            //追加属性节点
            $(".sm_target").click(function(){
                var type = $(this).parent('').attr('flag');
                AssessInstance.addItem($(this),type);
            });

            //业务部门父类选择
            $("#bus_area_parent").change(function(){
                AssessInstance.triggerBusSelect(0);
            });

            //考核人模糊搜索
            $("#username").autocomplete({
                source: function( request, response ) {
                    var s = $("#username").val();
                    var pid = $("#bus_area_parent").val();
                    var cid = $("#bus_area_child").val();

                    if(pid==''|| cid==''){
                        $("#username").removeClass('ui-autocomplete-loading');
                        $("#bus_area_child").addClass('redBorder');
                        $("#username").val('');
                        return false;
                    }
                    $("#bus_area_child").removeClass('redBorder');
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
                    $("#username").val(ui.item.value);
                    $("#username_userId").val(ui.item.id);
                }
            });

            //添加考核人
            $("#adduser").click(function(){
                var userId = $("#username_userId").val();
                if(userId){
                    var t = $("#username").val().split('_');
                    var index = t.length-1;
                    var username = t[index];
                    AssessInstance.adduser(userId,username);
                }
                $.myValidate.element('#username');
            });

            //考核人列表弹框添加
            $("#selectUserList").click(function(e){
                if($("#bus_area_child").val()==''){
                    $("#bus_area_child").addClass('redBorder');
                    return false;
                }

                var pid = $("#bus_area_parent").val();
                var cid = $("#bus_area_child").val();
                var uids = $("#uids").val();

                art.dialog.data('pid', pid);
                art.dialog.data('cid', cid);
                art.dialog.data('uids', uids);
                var openUrl  = '<?=P_SYSPATH."index.php?m=assessment&a=launchAssess&act=selectUserList"?>';
                openUrl+= "&pid="+pid+"&cid="+cid+"&uids="+uids;
                art.dialog.open(openUrl,{height:'500px',width:'700px',lock: true});
            });

            $("#bus_area_child,.commission_indicator_child").change(function(){
                AssessInstance.selectChildValid(this);
            });
        });
    </script>
    <style>
        .redBorder {border: 1px solid #cd0a0a}
    </style>
</head>
<?php
function dateHtml($data,$key,$disabled){
    if(!isset($data[$key])){
        $data[$key] = '';
    }
    print <<<EOF
<div class="data" style="margin-right:6px;">
                <input type="text" {$disabled} name="{$key}" id="{$key}" value="{$data[$key]}" class="width135" />
                <a href="javascript:void(0);" class="dataicon" id="f_trigger_{$key}"></a>
            </div>
            <script type="text/javascript">
        Calendar.setup({
                    inputField : "{$key}",
                    ifFormat : "%Y-%m-%d",
                    showsTime : false,
                    button : "f_trigger_{$key}",
                    singleClick : false,
                    step : 1
                });
            </script>
EOF;
}
?>
<body>
<div class="bg baseInfo_content">
    <div class="rtop">
        <p class="icon1">HR考核管理 > 创建考核计划</p>
    </div>
    <div class="kctjcon">
        <p class="tjtip">注：*为必填项</p>

        <form action="" method="post" id="sub_form" class="clearfix" >
            <input type="hidden" name="base_id" id="base_id" value="<?=(isset($record_info['base_info']['base_id']))?$record_info['base_info']['base_id']:'';?>">
            <table cellpadding="0" cellspacing="0" width="100%">
                <tr>
                    <td width="188" align="right"><em class="c-yel">*</em> 考核名称：&nbsp;</td>
                    <td>
                        <input <?=$mValid->getDisableValid('base_name');?> type="text" name="base_name" id="base_name" value="<?=(isset($record_info['base_info']['base_name']))?$record_info['base_info']['base_name']:'';?>" class="width190" />
                    </td>
                </tr>
                <tr>
                    <!--  @todo 业务部门二级分类-->
                    <td align="right" valign="top"><em class="c-yel">*</em> 业务单元：&nbsp;</td>
                    <td>
                        <div class="jssel" style="z-index:98">
                            <select id="bus_area_parent" name="bus_area_parent" style="width: 150px;" <?=$mValid->getDisableValid('bus_area_parent');?>>
                                <?php foreach($cfg['tixi'] as $k=>$v){?>
                                    <option value="<?=$k?>" <?php if(isset($record_info['base_info']['base_name']) && $record_info['base_info']['bus_area_parent']==$k){?> selected="selected"<?php }?>><?=$v['title']?></option>
                                <?php }?>
                            </select> &nbsp;&nbsp;
                            <input type="hidden" name="bus_area_parent_hidden" id="bus_area_parent_hidden" value="<?=isset($record_info['base_info']['bus_area_parent'])?$record_info['base_info']['bus_area_parent']:'';?>">
                        </div>

                        <div class="jssel" style="z-index:49">
                            <select id="bus_area_child" name="bus_area_child" style="width: 150px;" <?=$mValid->getDisableValid('bus_area_child');?>>
                            </select>
                            <input type="hidden" name="bus_area_child_hidden" id="bus_area_child_hidden" value="<?=isset($record_info['base_info']['bus_area_child'])?$record_info['base_info']['bus_area_child']:'';?>">
                        </div>
                    </td>
                </tr>
                <tr>
                    <!--  @todo 被考核人列表选择-->
                    <td align="right" valign="top"><em class="c-yel">*</em> 被考核人：&nbsp;</td>
                    <td>
                        <?php
                            if($relationUsers){
                                $uidsStr = '';
                                foreach($relationUsers as $k=>$user){
                                    $uidsStr.=$user['userId'].",";
                                }
                                $uidsStr = substr($uidsStr,0,-1);
                            }
                        ?>
                        <input <?=$mValid->getDisableValid('username');?>  type="text" value=""  placeholder="请输入" name="username" id="username" class="width190"  />
                        <input type="hidden" id="username_userId" value=""/>
                        <input <?=$mValid->getDisableValid('uids');?>  type="hidden" name="uids" id="uids" value="<?=$uidsStr?>" />
                        <input <?=$mValid->getDisableValid('adduser');?>  type="button" class="btn48 adduser" value="添加" id="adduser" name="adduser"/>
                        <input <?=$mValid->getDisableValid('selectUserList');?>  type="button" class="btn74 getuserlist"  id="selectUserList"  name="selectUserList" style="margin:0;" value="选择用户" />
                        <div class="shcon div_userlist" style="width: 500px;<?php if(!$relationUsers){?>display: none;<?php }?>">
                            <div class="tjxm userlist">
                                <?php if($relationUsers){?>
                                    <?php foreach($relationUsers as $k=>$user){?>
                                <span id="span_auto_<?=$user['userId']?>">
                                    <?=$user['username']?>
                                    <?php if($mValid->validElement('uids')){?>
                                        <a id="<?=$user['userId']?>,<?=$user['username']?>" href="javascript:void(0)" class="close deluser" onclick="Assess.prototype.delUserADom(this)"></a>
                                     <?php }?>
                                </span>
                                        <?php }?>
                                <?php }?>
                            </div>
                        </div>
                    </td>
                </tr>

                <tr>
                    <td align="right"><em class="c-yel">*</em> 考核周期开始时间：&nbsp;</td>
                    <td class="jsline">
                        <div style="float: left;">
                            <select name="assess_period_type" id="assess_period_type" <?=$mValid->getDisableValid('assess_period_type');?>>
                                <?php foreach(AssessDao::$AssessPeriodTypeMaps as $k=>$v){?>
                                    <option value="<?=$k?>"
                                        <?php if($record_info['base_info']['assess_period_type']==$k){?> selected="selected"<?php }?>>
                                        <?=$v?>
                                    </option>
                                <?php }?>
                            </select>&nbsp;&nbsp;
                        </div>
                        <?=dateHtml($record_info['base_info'],'base_start_date',$mValid->getDisableValid('base_start_date'));?>

                    </td>
                </tr>

                <tr>
                    <td align="right"><em class="c-yel">*</em> 员工自评开始时间：&nbsp;</td>
                    <td class="jsline">
                        <?=dateHtml($record_info['base_info'],'staff_sub_start_date',$mValid->getDisableValid('staff_sub_start_date'));?>
                    </td>
                </tr>

                <tr>
                    <td align="right">按月生成：&nbsp;</td>
                    <td>
                        <input <?=$mValid->getDisableValid('create_on_month_status');?>  type="checkbox" name="create_on_month_status"  value="1" id="create_on_month_status" <?php if(isset($record_info['base_info']['create_on_month_status']) && $record_info['base_info']['create_on_month_status']==1){?>checked="checked" <?php }?>>
                    </td>
                </tr>

                <tr>
                    <td align="right"> 由直接领导设置：&nbsp;</td>
                    <td>
                        <input type="checkbox" <?=$mValid->getDisableValid('lead_direct_set_status')?> name="lead_direct_set_status"  value="1" id="lead_direct_set_status" <?php if(isset($record_info['base_info']['lead_direct_set_status']) && $record_info['base_info']['lead_direct_set_status']==1){?>checked="checked" <?php }?>>
                    </td>
                </tr>

                <tr>
                    <td align="right">考核类型选择：&nbsp;</td>
                    <td id="attr_type_checkboxes_td">
                        <input type="checkbox" <?=$mValid->getDisableValid('assess_attr_type')?>   name="assess_attr_type" value="1" <?=($record_info['base_info']['assess_attr_type']==1)?"checked=\"checked\"":"";?>>任务/指标类&nbsp;
                        <input type="checkbox"  <?=$mValid->getDisableValid('assess_attr_type')?> name="assess_attr_type" value="2" <?=($record_info['base_info']['assess_attr_type']==2)?"checked=\"checked\"":"";?>>打分类&nbsp;
                        <input type="checkbox" <?=$mValid->getDisableValid('assess_attr_type')?>  name="assess_attr_type" value="3" <?=($record_info['base_info']['assess_attr_type']==3)?"checked=\"checked\"":"";?>>提成类&nbsp;
                    </td>
                </tr>
            </table>
            <div class="attr_content">
                <!--任务/指标类-->
                <?=$assessAttrWidget->renderAttr($record_info['attr_info'],1,array(),$mValid)?>

                <!--打分类-->
                <?=$assessAttrWidget->renderAttr($record_info['attr_info'],2,array(),$mValid)?>

                <!--提成类-->
                <?=$assessAttrWidget->renderAttr($record_info['attr_info'],3,array(),$mValid)?>
            </div>
            <div class="kctjbot">
                <?php if(!isset($record_info['base_info']) ||$record_info['base_info']!=AssessDao::HrAssessOver){?>
                    <input type="submit" class="bluebtn" value="确定" />
                <?php }?>

                <?php if(!isset($record_info['base_info']) || $record_info['base_info']['base_status']==AssessDao::HrAssessWait){?>
                    <input type="button" id="pubBtb" class="bluebtn" value="发布" pubUrl="<?=P_SYSPATH?>index.php?m=assessment&a=launchList&act=publishAssess&base_status=1&base_id=" />
                <?php }?>
                <input type="button" class="btn67" value="返回"  name="backBtn" onclick="history.go(-1);"/>
            </div>
        </form>
    </div>
</div>
</body>
</html>
