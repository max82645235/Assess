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
    <script type="text/javascript">
        var AssessInstance =  new Assess();
        $(function(){
            AssessInstance.triggerBusSelect(); //刚进页面时触发一次部门二级联动ajax查询
            //业务部门父类选择
            $("#bus_area_parent").change(function(){
                AssessInstance.triggerBusSelect();
            });
        });
    </script>
    <style>

    </style>
</head>
<body>
<div class="bg">
    <div class="rtop">
        <p class="icon1">考核管理 >管理列表</p>
    </div>
    <div class="pad25">
        <div class="brdbt zykc" style="height: 50px;">
            <form name="frm" action="" method="get" class="clearfix" style="float: left;width: 90%;">
                <input type="hidden" name="m" value="<?=getgpc('m')?>">
                <input type="hidden" name="a" value="<?=getgpc('a')?>">
                <div class="jssel" style="z-index:98">
                    业务部门：
                    <select id="bus_area_parent" name="bus_area_parent" style="width: 150px;">
                        <option value="">请选择</option>
                        <?php foreach($cfg['tixi'] as $k=>$v){?>
                            <option value="<?=$k?>" <?php if(isset($_REQUEST['bus_area_parent']) && $_REQUEST['bus_area_parent']==$k){?> selected="selected"<?php }?>><?=$v['title']?></option>
                        <?php }?>
                    </select>
                    <input type="hidden" name="bus_area_parent_hidden" id="bus_area_parent_hidden" value="<?=isset($_REQUEST['bus_area_parent'])?$_REQUEST['bus_area_parent']:'';?>">
                </div>
                <div class="jssel" style="z-index:49">
                    <select id="bus_area_child" name="bus_area_child" style="width: 150px;">
                    </select>
                    <input type="hidden" name="bus_area_child_hidden" id="bus_area_child_hidden" value="<?=isset($_REQUEST['bus_area_child'])?$_REQUEST['bus_area_child']:'';?>">
                </div>
                <div class="jssel" style="z-index:98">
                    &nbsp;&nbsp;&nbsp;考核状态：
                    <select name="base_status">
                        <option value=""    <?php if(isset($_REQUEST['base_status']) && $_REQUEST['base_status']===''){?> selected="selected"<?php }?>>请选择</option>
                        <?php foreach(AssessDao::$HrAssessBaseStatus as $k=>$val){?>
                            <option value="<?=$k?>"  <?php if(isset($_REQUEST['base_status']) &&$_REQUEST['base_status']!=='' &&$_REQUEST['base_status']==$k){?> selected="selected"<?php }?>><?=$val?></option>
                        <?php }?>
                    </select>
                </div>
                <div class="jssel" style="z-index:98">
                    &nbsp;&nbsp;&nbsp;发起人：
                    <select name="byme_status">
                        <option value="1" <?php if(isset($_REQUEST['byme_status']) && $_REQUEST['byme_status']==1){?> selected="selected"<?php }?>>由我发起</option>
                       <option value="2"<?php if(isset($_REQUEST['byme_status']) && $_REQUEST['byme_status']==2){?> selected="selected"<?php }?>>全部</option>
                    </select>
                </div>
                <div class="jssel" style="z-index:98">
                    &nbsp;&nbsp;&nbsp;考核周期：
                    <select name="assess_period_type">
                        <option value="">请选择</option>
                        <?php foreach(AssessDao::$AssessPeriodTypeMaps as $k=>$v){?>
                            <option value="<?=$k?>"
                                <?php if(isset($_REQUEST['assess_period_type']) && $_REQUEST['assess_period_type']==$k){?> selected="selected"<?php }?>>
                                <?=$v?>
                            </option>
                        <?php }?>
                    </select>
                </div>
                <div class="sechk" style="margin-top: 5px;clear: both;float: left;">
                    考核名称：
                    <input type="text" value="<?=(isset($_REQUEST['base_name']))?$_REQUEST['base_name']:'';?>" name="base_name" id="base_name" class="width135" placeholder="请输入考核名称"  style="margin-bottom: 3px;">
                </div>
                    <input type="submit" value="搜索" class="btn48"  style="float: left;margin-top: 5px;">


            </form>
            <?php if(true){?>
                <a class="addfl-t add" href="?m=assessment&a=launchAssess&<?=$pageConditionUrl?>" style="text-align: left;">发起考核</a>
            <?php }?>
        </div>

        <div class="mrtb10">
            <table cellpadding="0" cellspacing="0" width="100%" class="jbtab" id="table_style">
                <tr>
                    <th width="50"> <input type="checkbox" id="top_check_input"></th>
                    <th class="left">绩效考核名称</th>
                    <th width="100" class="left">考核频率</th>
                    <th width="200" class="left">考核周期</th>
                    <th width="100" class="left">状态</th>
                    <th width="100">发布日期</th>
                    <th width="128">操作</th>
                </tr>
                <?php if($tableData){?>
                    <?php foreach($tableData as $k=>$data){?>
                        <tr class="<?=($k%2)?'bgfff':'bgf0';?>">
                            <td> <input type="checkbox" class="table_item_checkbox" tag="<?=$data['base_id']?>"></td>
                            <td class="left"><?=$data['base_name']?></td>
                            <td ><?=AssessDao::$AssessPeriodTypeMaps[$data['assess_period_type']]?></td>
                            <td>
                                <?=date('Y/m/d',strtotime($data['base_start_date']))?> -
                                <?=date('Y/m/d',strtotime($data['base_end_date']))?>
                            </td>
                            <td><?=AssessDao::$HrAssessBaseStatus[$data['base_status']]?></td>
                            <td><?=($data['publish_date']!='0000-00-00')?$data['publish_date']:'';?></td>
                            <td>
                                <a href="?m=assessment&a=launchAssess&<?=$pageConditionUrl?>&base_id=<?=$data['base_id']?>" class="bjwrt">编辑</a>
                            </td>
                        </tr>
                    <?php }?>
                <?php }?>

            </table>
            <p class="pagenum">
                <?=$page_nav?>
            </p>
            <div>
                <input type="button" name="" value="考核停止" class="btn139">
                <input type="button" name="" value="启动考核" class="btn139">
                <input type="button" name="" value="考核复制" class="btn139">
                <input type="button" name="" value="考核发布" class="btn139">
            </div>
        </div>
    </div>
</div>
<div class="tck" style="display:none;"></div>
</body>
</html>
