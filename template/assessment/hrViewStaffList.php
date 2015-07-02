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
<body>
<div class="bg">
    <div class="rtop">
        <p class="icon1">考核管理 > 被考核人列表 > <?=$assessBaseRecord['base_name']?></p>
    </div>
    <div class="pad25">
        <div class="brdbt zykc" style="height: 30px;">

            <form name="frm" action="" method="get" class="clearfix" style="float: left;width: 90%;">
                <input type="hidden" name="m" value="assessment">
                <input type="hidden" name="a" value="launchList">
                <input type="hidden" name="act" value="hrViewStaffList">
                <input type="hidden" name="base_id" value="<?=$_REQUEST['base_id']?>" id="base_id">
                <div class="jssel" style="z-index:98">
                    &nbsp;&nbsp;&nbsp;流程状态：
                    <select name="user_assess_status" style="width: 150px;">
                        <option value=""    <?php if(isset($_REQUEST['user_assess_status']) && $_REQUEST['user_assess_status']===''){?> selected="selected"<?php }?>>请选择</option>
                        <?php foreach(AssessFlowDao::$UserAssessStatusByHr as $k=>$val){?>
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
            <table cellpadding="0" cellspacing="0" width="100%" class="jbtab" id="table_style" >
                <tr >
                    <th width="50" style="text-align: center;" >
                        <input type="checkbox" id="top_check_input"  onclick="Assess.prototype.tableTopChecked(this)">
                    </th>
                    <th class="left" width="100"  style="text-align: center;">被考核人</th>
                    <th style="text-align: center;">部门</th>
                    <th width="150" style="text-align: center;">流程状态</th>
                    <th width="150" style="text-align: center;">得分</th>
                    <th  width="100" style="text-align: center;">操作</th>
                </tr>
                <?php if($tableData){?>
                    <?php foreach($tableData as $k=>$data){?>
                        <tr class="<?=($k%2)?'bgfff':'bgf0';?>">
                            <td>
                                <input type="checkbox" class="table_item_checkbox" tag="<?=$data['userId']?>">
                            </td>
                            <td ><?=$data['username']?></td>
                            <td class="left"><?=$data['deptlist']?></td>
                            <td><?=AssessFlowDao::$UserAssessStatusByHr[$data['user_assess_status']]?></td>
                            <td><?=($data['score'])?$data['score']:'';?></td>
                            <td class="left">
                                <a href="?m=assessment&a=launchList&act=hrViewStaffDetail&userId=<?=$data['userId']?>&base_id=<?=$data['base_id'].$pageConditionUrl?>" class="bjwrt">查看</a>
                            </td>
                        </tr>
                    <?php }?>
                <?php }?>

            </table>
            <p class="pagenum">
                <?=$page_nav?>
            </p>
        </div>
    </div>
</div>
<div class="tck" style="display:none;"></div>
</body>
</html>
