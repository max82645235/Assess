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
            AssessInstance.triggerBusSelect(true); //刚进页面时触发一次部门二级联动ajax查询
            //业务部门父类选择
            $("#bus_area_parent").change(function(){
                AssessInstance.triggerBusSelect(true);
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
        <p class="icon1">待我考核 >员工列表</p>
    </div>
    <fieldset>
        <legend>考核名称：<span style="color:#8DDB75;"><?=$assessBaseRecord['base_name']?></span></legend>
        <div class="pad25">
            <div class="brdbt zykc" style="height: 30px;">

                <form name="frm" action="" method="get" class="clearfix" style="float: left;width: 90%;">
                    <input type="hidden" name="m" value="myassessment">
                    <input type="hidden" name="a" value="waitMeAssess">
                    <input type="hidden" name="act" value="myStaffList">
                    <input type="hidden" name="base_id" value="<?=$_REQUEST['base_id']?>">
                    <div class="jssel" style="z-index:98">
                        业务部门：
                        <select id="bus_area_parent" name="bus_area_parent" style="width: 150px;">
                            <option value="">请选择</option>
                            <?php foreach($bus_parent_list as $k=>$v){?>
                                <option value="<?=$k?>" <?php if(isset($_REQUEST['bus_area_parent']) && $_REQUEST['bus_area_parent']==$k){?> selected="selected"<?php }?>><?=$v?></option>
                            <?php }?>
                        </select>
                        <input type="hidden" name="bus_area_parent_hidden" id="bus_area_parent_hidden" value="<?=isset($_REQUEST['bus_area_parent'])?$_REQUEST['bus_area_parent']:'';?>">
                    </div>
                    <div class="jssel" style="z-index:49">
                        &nbsp;&nbsp;
                        <select id="bus_area_child" name="bus_area_child" style="width: 150px;">
                        </select>
                        <input type="hidden" name="bus_area_child_hidden" id="bus_area_child_hidden" value="<?=isset($_REQUEST['bus_area_child'])?$_REQUEST['bus_area_child']:'';?>">
                    </div>

                    <div class="jssel" style="z-index:98">
                        &nbsp;&nbsp;&nbsp;流程状态：
                        <select name="user_assess_status" style="width: 150px;">
                            <option value=""    <?php if(isset($_REQUEST['user_assess_status']) && $_REQUEST['user_assess_status']===''){?> selected="selected"<?php }?>>请选择</option>
                            <?php foreach(AssessFlowDao::$UserAssessStatusByLeader as $k=>$val){?>
                                <option value="<?=$k?>"  <?php if(isset($_REQUEST['user_assess_status']) && $_REQUEST['user_assess_status']!=='' && $_REQUEST['user_assess_status']==$k){?> selected="selected"<?php }?>><?=$val?></option>
                            <?php }?>
                        </select>
                    </div>
                    <div  class="jssel" style="z-index:98">
                        &nbsp;&nbsp;&nbsp;被考核人：
                        <input type="text" value="<?=(isset($_REQUEST['username']))?$_REQUEST['username']:'';?>" name="username" id="username" class="width135" placeholder="输入被考核人姓名"  style="margin-bottom: 3px;">

                    </div>
                    <div  class="jssel" style="z-index:98;margin-left: 20px;margin-bottom: 5px;">
                        <input type="submit" value="搜索" class="btn48"  >
                    </div>

                </form>
            </div>

            <div class="mrtb10" >
                <table cellpadding="0" cellspacing="0" width="100%" class="jbtab" id="table_style" style="color: #3186c8;">
                    <tr >
                        <th width="50" style="text-align: center;" >
                            <input type="checkbox" id="top_check_input"  onclick="Assess.prototype.tableTopChecked(this)">
                        </th>
                        <th class="left" width="200"  style="text-align: center;">被考核人</th>
                        <th style="text-align: center;">部门</th>
                        <th width="150" style="text-align: center;">考核状态</th>
                        <th  width="300" style="text-align: center;">操作</th>
                    </tr>
                    <?php
                        $btnArr = array(
                            '0'=>'创建',
                            '2'=>'初审',
                            '4'=>'终审',
                        );
                    ?>
                    <?php if($tableData){?>
                        <?php foreach($tableData as $k=>$data){?>
                            <tr class="<?=($k%2)?'bgfff':'bgf0';?>">
                                <td>
                                    <input type="checkbox" class="table_item_checkbox" tag="<?=$data['userId']?>">
                                </td>
                                <td class="left"><?=$data['username']?></td>
                                <td class="left"><?=$data['deptlist']?></td>
                                <td><?=AssessFlowDao::$UserAssessStatusByLeader[$data['user_assess_status']]?></td>
                                <td class="left">
                                    <a href="?m=myassessment&a=waitMeAssess&act=viewFlow&userId=<?=$data['userId']?>&base_id=<?=$data['base_id'].$pageConditionUrl?>" class="bjwrt">查看流程</a>
                                    <?php if(array_key_exists($data['user_assess_status'],$btnArr)){?>
                                        <span >
                                             <a href="?m=myassessment&a=waitMeAssess&act=leaderSetFlow&userId=<?=$data['userId']?>&base_id=<?=$data['base_id'].$pageConditionUrl?>" class="bjwrt" style="color: #ff3333"><?=$btnArr[$data['user_assess_status']]?></a>
                                        </span>

                                    <?php }?>
                                </td>
                            </tr>
                        <?php }?>
                    <?php }?>

                </table>
                <p class="pagenum">
                    <?=$page_nav?>
                </p>
                <div>
                    <input type="button" name="" value="自行设置" class="btn139" id="copy_assess_btn" style="cursor:pointer;">
                </div>
            </div>
        </div>
    </fieldset>
</div>
<div class="tck" style="display:none;"></div>
</body>
</html>
