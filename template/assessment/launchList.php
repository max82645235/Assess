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
            AssessInstance.triggerBusSelect(1); //刚进页面时触发一次部门二级联动ajax查询
            //业务部门父类选择
            $("#bus_area_parent").change(function(){
                AssessInstance.triggerBusSelect(1);
            });

            $("#copy_assess_btn").click(function(){
                AssessInstance.tableBtnHandler($('#table_style'),
                    true,
                    function(selectedItem){
                        $.ajax({
                            type:'get',
                            url:'/salary/index.php',
                            data:{m:'assessment',a:'launchList',act:'cloneAssess',copyItemList:selectedItem},
                            dataType:'json',
                            success:function(ret){
                                if(ret.status=='success'){
                                    alert('考核复制成功');
                                    location.reload();
                                }
                            }
                        });
                    }
                );
            });

            $("#publish_assess_btn").click(function(){
                AssessInstance.tableBtnHandler($('#table_style'),
                    function(jInput){
                        var status = jInput.parent().find('.table_item_status').val();
                        if(status==0){
                            return true;
                        }else{
                            alert('请确保选中项都为待发布状态！');
                            return false;
                        }
                    },
                    function(selectedItem){
                        $.ajax({
                            type:'get',
                            url:'/salary/index.php',
                            data:{m:'assessment',a:'launchList',act:'publishAssess',selectedItemList:selectedItem},
                            dataType:'json',
                            success:function(ret){
                                if(ret.status=='success'){
                                    alert('考核发布成功');
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
                        var status = jInput.parent().find('.table_item_status').val();
                        if(status>=4){
                            return true;
                        }else{
                            alert('请确保选中项都为考核完状态！');
                            return false;
                        }
                    },
                    function(selectedItem){
                        AssessInstance.zipDownload({
                            baseList:selectedItem,
                            userList:[],
                            pos:'onLaunchList'
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
        <p class="icon1">HR考核管理 > 考核计划列表</p>
    </div>
    <div class="pad25">
        <div class="brdbt zykc" style="height: 50px;">
            <form name="frm" action="" method="get" class="clearfix" style="float: left;width: 90%;">
                <input type="hidden" name="m" value="<?=getgpc('m')?>">
                <input type="hidden" name="a" value="<?=getgpc('a')?>">
                <div class="jssel" style="z-index:98">
                    业务单元：
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
                        <option value="">请选择</option>
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
                <div class="sechk jssel" style="margin-top: 5px;float: left;width: 235px;">
                    考核名称：
                    <input type="text" value="<?=(isset($_REQUEST['base_name']))?$_REQUEST['base_name']:'';?>" name="base_name" id="base_name" class="width135" placeholder="请输入考核名称"  style="margin-bottom: 3px;">
                </div>
                <div class="jssel" style="z-index:98;margin-left: 5px;margin-top: 5px;">
                    <input type="submit" value="搜索" class="btn48" >
                </div>
            </form>
            <?php if($auth->setIsMy(true)->validIsAuth('launchAssess')){?>
                <a class="addfl-t add" href="?m=assessment&a=launchAssess&<?=$pageConditionUrl?>" style="text-align: left;">创建考核</a>
            <?php }?>
        </div>
        <div style="clear:both;background: none;border:none;"></div>
        <div class="mrtb10" >
            <table cellpadding="0" cellspacing="0" width="100%" class="jbtab" id="table_style">
                <tr >
                    <th width="50" style="text-align: center;" >
                        <input type="checkbox" id="top_check_input"  onclick="Assess.prototype.tableTopChecked(this)">
                    </th>
                    <th class="left" style="text-align: center;">绩效考核名称</th>
                    <th width="100" style="text-align: center;">考核频率</th>
                    <th width="200" style="text-align: center;">考核时间</th>
                    <th width="100" style="text-align: center;">状态</th>
                    <th width="100" style="text-align: center;">发布日期</th>
                    <th width="128" style="text-align: center;">操作</th>
                </tr>
                <?php if($tableData){?>
                    <?php foreach($tableData as $k=>$data){?>
                        <tr class="<?=($k%2)?'bgfff':'bgf0';?>">
                            <td>
                                <?php
                                        //克隆 || 发布 至少有一个权限类验证通过时，checkbox选项才能开启
                                        $canEdit = true;
                                        $isMy =  $data['userId']==$userId;
                                        $canEdit = $auth->setIsMy($isMy)->validIsAuth('cloneAssess');
                                        $canEdit = $canEdit && $auth->setIsMy($isMy)->validIsAuth('publishAssess');
                                ?>
                                    <input type="checkbox" class="table_item_checkbox" tag="<?=$data['base_id']?>"
                                           <?php if(!$canEdit){?>disabled="disabled" <?php }?>>
                                    <input type="hidden" class="table_item_status" value="<?=$data['base_status']?>">
                            </td>
                            <td class="left"><?=$data['base_name']?></td>
                            <td ><?=AssessDao::$AssessPeriodTypeMaps[$data['assess_period_type']]?></td>
                            <td>
                                <?=date('Y/m/d',strtotime($data['base_start_date']))?> -
                                <?=date('Y/m/d',strtotime($data['base_end_date']))?>
                            </td>
                            <td><?=AssessDao::$HrAssessBaseStatus[$data['base_status']]?></td>
                            <td><?=($data['publish_date']!='0000-00-00')?$data['publish_date']:'';?></td>
                            <td>

                                <?php if($auth->setIsMy($isMy)->validIsAuth('launchAssess')){?>
                                        <a href="?m=assessment&a=launchAssess&base_id=<?=$data['base_id'].$pageConditionUrl?>" class="bjwrt">编辑</a>
                                <?php }?>

                                <?php if($auth->setIsMy($isMy)->validIsAuth('cloneAssess')){?>
                                        <a href="?m=assessment&a=launchList&act=cloneAssess&base_id=<?=$data['base_id'].$pageConditionUrl?>" class="bjwrt">复制</a>
                                <?php }?>

                                <?php if($auth->setIsMy($isMy)->validIsAuth('publishAssess') && $data['base_status']==AssessDao::HrAssessWait){?>
                                        <a href="?m=assessment&a=launchList&act=publishAssess&base_id=<?=$data['base_id'].$pageConditionUrl?>" class="bjwrt">发布</a>
                                <?php }?>
                                <?php if($auth->setIsMy($isMy)->validIsAuth('hrViewPublish')){?>
                                         <a href="?m=assessment&a=launchList&act=hrViewStaffList&base_id=<?=$data['base_id'].$pageConditionUrl?>" class="bjwrt">查看</a>
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
                <?php if($auth->setIsMy(true)->validIsAuth('cloneAssess')){?>
                     <input type="button" name="" value="考核复制" class="btn139" id="copy_assess_btn" style="cursor:pointer;">
                <?php }?>&nbsp;&nbsp;

                <?php if($auth->setIsMy(true)->validIsAuth('publishAssess')){?>
                     <input type="button" name="" value="考核发布" class="btn139" id="publish_assess_btn" style="cursor:pointer;">
                <?php }?>

                <?php if($auth->setIsMy(true)->validIsAuth('hrZipAssessPackage')){?>
                    <input type="button" name="" value="考核导出" class="btn139" id="zip_assess_btn" style="cursor:pointer;">
                <?php }?>
            </div>
        </div>
    </div>
</div>
<div class="tck" style="display:none;"></div>
</body>
</html>
