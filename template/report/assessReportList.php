<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=gb2312" />
    <meta name="keywords" content="" />
    <meta name="description" content="" />
    <link href="<?=P_CSSPATH?>reset.css" rel="stylesheet" type="text/css" />
    <link href="<?=P_CSSPATH?>right.css" rel="stylesheet" type="text/css" />
    <script src="<?=P_JSPATH?>jquery.1.11.1.js" type="text/javascript"></script>
    <script src="<?=P_SYSPATH?>static/js/assess/launchAssess.js" type="text/javascript"></script>'
    <script>
        $(function(){
            $("#zip_assess_btn").click(function(){
                Assess.prototype.tableBtnHandler($('#table_style'),
                    function(jInput){
                        var status = jInput.parent().find('.table_item_status').val();
                        if(status>=6){
                            return true;
                        }else{
                            alert('请确保选中项都为考核完状态！');
                            return false;
                        }
                    },
                    function(selectedItem){
                        var index = 0;
                        var baseList = [];
                        var userList = [];
                        $("#table_style tbody tr:gt(0)").each(function(k,v){
                            var rid = $(this).find('td:eq(0) input.table_item_checkbox').attr('tag');
                            if($.inArray(rid,selectedItem)>=0){
                                baseList[index]= $(this).find('td:eq(0) input.table_item_checkbox').attr('baseId');
                                userList[index] = $(this).find('td:eq(0) input.table_item_checkbox').attr('userId');
                                index++;
                            }

                        });

                        Assess.prototype.zipDownload({
                            baseList:baseList,
                            userList:userList,
                            pos:'onAssessReportList'
                        },'hr');
                    }
                );
            });
        });
    </script>
</head>
<body>
<div class="bg">
    <div class="rtop">
        <p class="icon1">报表统计 > 绩效报表</p>
    </div>
    <div class="pad25">
        <div class="brdbt zykc" style="height: 30px;">
            <form name="frm" action="" method="get" class="clearfix" style="float: left;width: 100%;">
                <input type="hidden" name="m" value="report">
                <input type="hidden" name="a" value="assessReport">
                <input type="hidden" name="act" value="assessReportList">
                <div class="jssel" style="z-index:98">
                    &nbsp;&nbsp;&nbsp;考核频率：
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
                    &nbsp;&nbsp;&nbsp;年份：
                    <select name="assess_year">
                        <option value="">请选择</option>
                        <?php for($i=2015;$i<=2025;$i++){?>
                            <option value="<?=$i?>"  <?php if(isset($_REQUEST['assess_year'])&&$_REQUEST['assess_year']==$i){?> selected="selected" <?php }?>><?=$i?>年</option>
                        <?php }?>
                    </select>
                </div>

                <div class="jssel" style="z-index:98">
                    &nbsp;&nbsp;&nbsp;月份：
                    <select name="assess_month">
                        <option value="">请选择</option>
                        <?php for($i=1;$i<=12;$i++){?>
                            <option value="<?=$i?>" <?php if(isset($_REQUEST['assess_month'])&&$_REQUEST['assess_month']==$i){?> selected="selected" <?php }?>><?=$i?>月</option>
                        <?php }?>
                    </select>
                </div>
                <div class="jssel" style="z-index:98">
                    &nbsp;&nbsp;&nbsp;流程状态：
                    <select name="user_assess_status" style="width: 150px;">
                        <option value=""    <?php if(isset($_REQUEST['user_assess_status']) && $_REQUEST['user_assess_status']===''){?> selected="selected"<?php }?>>请选择</option>
                        <?php foreach(AssessFlowDao::$UserAssessStatusByHr as $k=>$val){?>
                            <option value="<?=$k?>"  <?php if(isset($_REQUEST['user_assess_status']) && $_REQUEST['user_assess_status']!=='' && $_REQUEST['user_assess_status']==$k){?> selected="selected"<?php }?>><?=$val?></option>
                        <?php }?>
                    </select>
                </div>
                <div class="jssel" style="z-index:98;margin-bottom: 5px;"">
                    &nbsp;&nbsp;&nbsp;考核人姓名：
                    <input type="text" value="<?=(isset($_REQUEST['username']))?$_REQUEST['username']:'';?>" name="username" id="username" class="width135" placeholder="请输入考核人姓名"  >
                </div>
                <div class="jssel" style="z-index:98;margin-bottom: 3px;">
                    &nbsp;<input type="submit" value="搜索" class="btn48">
                </div>

                <div class="jssel" style="z-index:98;margin-bottom: 3px;">
                    &nbsp; <input type="button" name="" value="考核导出" class="btn139" id="zip_assess_btn" style="cursor:pointer;margin-left: 12px;">
                </div>

            </form>
        </div>

        <div class="mrtb10" >
            <table cellpadding="0" cellspacing="0" width="100%" class="jbtab" id="table_style" >
                <tr >
                    <th width="50" style="text-align: center;" >
                        <input type="checkbox" id="top_check_input"  onclick="Assess.prototype.tableTopChecked(this)">
                    </th>
                    <th width="100" style="text-align: center;">考核人姓名</th>
                    <th width="100" style="text-align: center;">考核频率</th>
                    <th width="200" style="text-align: center;">考核时间</th>
                    <th width="200" style="text-align: center;">考核类型</th>
                    <th width="200" style="text-align: center;">流程状态</th>
                    <th width="100" style="text-align: center;">绩效评分</th>
                    <th width="150" style="text-align: center;">奖惩</th>
                </tr>
                <?php if($tableData){?>
                    <?php foreach($tableData as $k=>$data){?>
                        <tr class="<?=($k%2)?'bgfff':'bgf0';?>">
                            <td>
                                <input type="checkbox" class="table_item_checkbox" tag="<?=$data['rid']?>" baseId="<?=$data['base_id']?>" userId="<?=$data['userId']?>">
                                <input type="hidden" class="table_item_status" value="<?=$data['user_assess_status']?>">
                            </td>
                            <td ><?=$data['username']?></td>
                            <td ><?=AssessDao::$AssessPeriodTypeMaps[$data['assess_period_type']]?></td>
                            <td>
                                <?=date('Y/m/d',strtotime($data['base_start_date']))?> -
                                <?=date('Y/m/d',strtotime($data['base_end_date']))?>
                            </td>
                            <td>
                                <?php if(in_array($data['assess_attr_type'],array(1,2))){?>
                                    量化指标/工作任务类
                                <?php }else{?>
                                    <?=AssessDao::$attrTypeMaps[$data['assess_attr_type']]?>
                                <?php }?>
                            </td>
                            <td><?=AssessFlowDao::$UserAssessStatusByHr[$data['user_assess_status']]?></td>
                            <td width="100" style="text-align: center;"><?=($data['score'])?$data['score']:'';?></td>
                            <td>
                                <?php $rpData = unserialize($data['rpData']);?>
                                <?php if($rpData){?>
                                    <?php if(isset($rpData['total'][1]['totalValue'])){?>
                                        <?=$rpData['total'][1]['totalValue']?>元</br>
                                    <?php }?>
                                    <?php if(isset($rpData['total'][2]['totalValue'])){?>
                                        <?=$rpData['total'][2]['totalValue']*100?>%</br>
                                    <?php }?>
                                <?php }elseif($data['user_assess_status']==AssessFlowDao::AssessRealSuccess){echo "-";}?>
                            </td>
                        </tr>
                    <?php }?>
                <?php }?>
            </table>
        </div>
    </div>
</div>
<div class="tck" style="display:none;"></div>
</body>
</html>
