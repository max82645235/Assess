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
    <script type="text/javascript">
        var AssessInstance =  new Assess();
        $(function(){
            $("#assess_user_diy_set").click(function(){
                AssessInstance.tableBtnHandler($('#table_style'),
                    function(jInput){
                        var status = jInput.parents('tr').attr('assess_status');
                        if(status==0){
                            return true;
                        }else{
                            alert('请保证勾选项都为待我创建！');
                            return false;
                        }
                    },
                    function(selectedItem){
                        var base_id = $("#base_id").val();
                        $.ajax({
                            type:'get',
                            url:'/salary/index.php',
                            data:{m:'myassessment',a:'waitMeAssess',act:'singleAssessDiySet',diyItemList:selectedItem,base_id:base_id},
                            dataType:'json',
                            success:function(ret){
                                if(ret.status=='success'){
                                    alert('设置成功');
                                    location.reload();
                                }
                            }
                        });
                    }
                );
            });

            $("#zip_assess_btn").click(function(){
                AssessInstance.tableBtnHandler($('#table_style'),
                    function(jInput){
                        var status = jInput.parents('tr').attr('assess_status');
                        if(status>=6){
                            return true;
                        }else{
                            alert('请保证勾选项都已完成考核！');
                            return false;
                        }
                    },
                    function(selectedItem){
                        var base_id = $("#base_id").val();
                        AssessInstance.zipDownload({
                            baseList:[base_id],
                            userList:[selectedItem],
                            pos:'onMyStaffList'
                        },'lead');
                    }
                );
            });
        });
    </script>
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
        <p class="icon1">待我考核 > 员工列表 > <?=$assessBaseRecord['base_name']?></p>
    </div>
    <div class="pad25">
        <div class="brdbt zykc" style="height: 30px;">

            <form name="frm" action="" method="get" class="clearfix" style="float: left;width: 90%;">
                <input type="hidden" name="m" value="myassessment">
                <input type="hidden" name="a" value="waitMeAssess">
                <input type="hidden" name="act" value="myStaffList">
                <input type="hidden" name="base_id" value="<?=$_REQUEST['base_id']?>" id="base_id">
                <div class="jssel" style="z-index:98">
                    &nbsp;&nbsp;&nbsp;流程状态：
                    <select name="user_assess_status" style="width: 150px;">
                        <option value=""    <?php if(isset($_REQUEST['user_assess_status']) && $_REQUEST['user_assess_status']===''){?> selected="selected"<?php }?>>请选择</option>
                        <?php foreach(AssessFlowDao::$UserAssessStatusByLeader as $k=>$val){?>
                            <option value="<?=$k?>"  <?php if(isset($_REQUEST['user_assess_status']) && $_REQUEST['user_assess_status']!=='' && $_REQUEST['user_assess_status']==$k){?> selected="selected"<?php }?>><?=$val?></option>
                        <?php }?>
                    </select>
                </div>
                <div class="jssel" style="z-index:98">
                    &nbsp;&nbsp;&nbsp;下属状态：
                    <select name="status">
                        <option value="1" <?php if(isset($_REQUEST['status']) && $_REQUEST['status']==1){?> selected="selected"<?php }?>>直属</option>
                        <option value="2"<?php if(isset($_REQUEST['status']) && $_REQUEST['status']==2){?> selected="selected"<?php }?>>非直属</option>
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
                    <th  width="100"  style="text-align: center;">被考核人</th>
                    <th style="text-align: center;">部门</th>
                    <th width="200" style="text-align: center;">流程状态</th>
                    <th width="150" style="text-align: center;">绩效评分</th>
                    <th width="150" style="text-align: center;">奖惩</th>
                    <th  width="150" style="text-align: center;">操作</th>
                </tr>
                <?php
                $btnArr = array(
                    '0'=>'创建计划',
                    '2'=>'审核计划',
                    '5'=>'绩效评估',
                );
                ?>
                <?php if($tableData){?>
                    <?php foreach($tableData as $k=>$data){?>
                        <?php
                            $flowStatus = true;
                            //如果为终审 且 自由流 且 不是当前领导审核时，审核按钮不做渲染
                            if($data['user_assess_status']==AssessFlowDao::AssessRealLeadView && $data['freeFlowUserId'] && $data['freeFlowUserId']!=getUserId()){
                                $flowStatus = false;
                            }
                        ?>

                        <tr class="<?=($k%2)?'bgfff':'bgf0';?>" assess_status="<?=$data['user_assess_status']?>">
                            <td>
                                <input type="checkbox" class="table_item_checkbox" tag="<?=$data['userId']?>">
                            </td>
                            <td ><?=$data['username']."(".$data['card_no'].")"?></td>
                            <td class="left"><?=$data['deptlist']?></td>
                            <td>
                                <?=AssessFlowDao::$UserAssessStatusByLeader[$data['user_assess_status']]?>
                                <?=AssessFlowDao::rejectTableMarkForLead($data['rejectStatus']);?>
                                <?=!$flowStatus?"<span style='color: red'>[自由流]</span>":'';?>
                            </td>
                            <td><?=($data['score'])?$data['score']:'';?></td>
                            <td>
                                <?php $rpData = _unserialize($data['rpData']);?>
                                 <?php if($rpData){?>
                                     <?php if(isset($rpData['total'][1]['totalValue'])){?>
                                             <?=$rpData['total'][1]['totalValue']?>元</br>
                                     <?php }?>
                                     <?php if(isset($rpData['total'][2]['totalValue'])){?>
                                             <?=$rpData['total'][2]['totalValue']*100?>%</br>
                                     <?php }?>
                                 <?php }elseif($data['user_assess_status']==AssessFlowDao::AssessRealSuccess){echo "-";}?>
                            </td>
                            <td class="left">
                                <a href="?m=myassessment&a=waitMeAssess&act=leadViewStaffDetail&userId=<?=$data['userId']?>&base_id=<?=$data['base_id'].$pageConditionUrl?>" class="bjwrt">查看</a>
                                <?php if( $assessBaseRecord['lead_direct_set_status']==1){?>
                                    <a  class="bjwrt" onclick="Assess.prototype.copyUserAssess(<?=$data['base_id']?>,<?=$data['userId']?>)" >复制</a>
                                <?php }?>
                                <?php if(array_key_exists($data['user_assess_status'],$btnArr)){?>


                                    <?php if($flowStatus){?>
                                        <span>
                                             <a href="?m=myassessment&a=waitMeAssess&act=leaderSetFlow&userId=<?=$data['userId']?>&base_id=<?=$data['base_id'].$pageConditionUrl?>" class="bjwrt" style="color:<?=AssessFlowDao::$UserAssessFontColorMaps[$data['user_assess_status']]?>">
                                                 <?=$btnArr[$data['user_assess_status']]?>
                                             </a>
                                        </span>
                                    <?php }?>
                                <?php }?>

                                <?php if($data['user_assess_status']==AssessFlowDao::AssessChecking && $assessBaseRecord['lead_direct_set_status']==1){?>
                                 <a href="?m=myassessment&a=waitMeAssess&act=changeCheckingStatus&userId=<?=$data['userId']?>&base_id=<?=$data['base_id'].$pageConditionUrl?>" class="bjwrt" style="color: #ff3333">变更状态</a>
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
                <input type="hidden" id="syspath" value="<?=P_SYSPATH?>">
                <input type="button" name=""  value="由员工创建计划" class="btn139" id="assess_user_diy_set" style="cursor:pointer;cursor:pointer;background-position:0px -649px;width: 120px;">
                <input type="button" name="" value="考核导出" class="btn139" id="zip_assess_btn" style="cursor:pointer;">
            </div>
        </div>
    </div>
</div>
<div class="tck" style="display:none;"></div>
</body>
</html>
