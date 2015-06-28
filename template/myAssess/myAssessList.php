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
</head>
<body>
<div class="bg">
    <div class="rtop">
        <p class="icon1">我的考核 >考核列表</p>
    </div>
    <div class="pad25">
        <div class="brdbt zykc" style="height: 30px;">
            <form name="frm" action="" method="get" class="clearfix" style="float: left;width: 90%;">
                <input type="hidden" name="m" value="myassessment">
                <input type="hidden" name="a" value="myAssess">
                <input type="hidden" name="act" value="myAssessList">
                <div class="jssel" style="z-index:98">
                    &nbsp;&nbsp;&nbsp;流程状态：
                    <select name="user_assess_status" style="width: 150px;">
                        <option value=""    <?php if(isset($_REQUEST['user_assess_status']) && $_REQUEST['user_assess_status']===''){?> selected="selected"<?php }?>>请选择</option>
                        <?php foreach(AssessFlowDao::$UserAssessStatusByStaff as $k=>$val){?>
                            <option value="<?=$k?>"  <?php if(isset($_REQUEST['user_assess_status']) && $_REQUEST['user_assess_status']!=='' && $_REQUEST['user_assess_status']==$k){?> selected="selected"<?php }?>><?=$val?></option>
                        <?php }?>
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
                <div class="jssel" style="z-index:98">
                    &nbsp;&nbsp;&nbsp;考核名称：
                    <input type="text" value="<?=(isset($_REQUEST['base_name']))?$_REQUEST['base_name']:'';?>" name="base_name" id="base_name" class="width135" placeholder="请输入考核名称"  style="margin-bottom: 3px;">
                </div>
                <div class="jssel" style="z-index:98;margin-left: 20px;margin-bottom: 5px;">
                    <input type="submit" value="搜索" class="btn48">
                </div>
            </form>
        </div>

        <div class="mrtb10" >
            <table cellpadding="0" cellspacing="0" width="100%" class="jbtab" id="table_style" >
                <tr >
                    <th width="50" style="text-align: center;" >
                        <input type="checkbox" id="top_check_input"  onclick="Assess.prototype.tableTopChecked(this)">
                    </th>
                    <th class="left" style="text-align: center;">绩效考核名称</th>
                    <th width="100" style="text-align: center;">考核频率</th>
                    <th width="200" style="text-align: center;">考核周期</th>
                    <th width="200" style="text-align: center;">流程状态</th>
                    <th width="100" style="text-align: center;">发布日期</th>
                    <th width="100" style="text-align: center;">得分</th>
                    <th width="200" style="text-align: center;">操作</th>
                </tr>
                <?php
                $btnArr = array(
                    '1'=>'填写考核计划',
                    '4'=>'填写考核提报'
                );
                ?>
                <?php if($tableData){?>
                    <?php foreach($tableData as $k=>$data){?>
                        <tr class="<?=($k%2)?'bgfff':'bgf0';?>">
                            <td>
                                <input type="checkbox" class="table_item_checkbox" tag="<?=$data['base_id']?>" >
                            </td>
                            <td class="left"><?=$data['base_name']?></td>
                            <td ><?=AssessDao::$AssessPeriodTypeMaps[$data['assess_period_type']]?></td>
                            <td>
                                <?=date('Y/m/d',strtotime($data['base_start_date']))?> -
                                <?=date('Y/m/d',strtotime($data['base_end_date']))?>
                            </td>
                            <td><?=AssessFlowDao::$UserAssessStatusByStaff[$data['user_assess_status']]?></td>
                            <td><?=($data['publish_date']!='0000-00-00')?$data['publish_date']:'';?></td>
                            <th width="100" style="text-align: center;"><?=($data['score'])?$data['score']:'';?></th>
                            <td class="left">
                                <a href="?m=myassessment&a=myAssess&act=staffViewStaffDetail&userId=<?=$data['user_Id']?>&base_id=<?=$data['base_id'].$pageConditionUrl?>" class="bjwrt">查看</a>
                                <?php if(array_key_exists($data['user_assess_status'],$btnArr)){?>
                                    <span >
                                             <a href="?m=myassessment&a=myAssess&act=myAssessFlow&base_id=<?=$data['base_id'].$pageConditionUrl?>" class="bjwrt" style="color: #ff3333"><?=$btnArr[$data['user_assess_status']]?></a>
                                        </span>
                                <?php }?>

                                <?php if($data['user_assess_status']==3){?>
                                <a href="?m=myassessment&a=myAssess&act=triggerStatusUpdate&userId=<?=$data['user_Id']?>&base_id=<?=$data['base_id'].$pageConditionUrl?>" class="bjwrt"  style="color: #ff3333">更改到提报时</a>
                                <?php }?>
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
