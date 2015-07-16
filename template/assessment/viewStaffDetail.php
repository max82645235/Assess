<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=gb2312" />
    <meta name="keywords" content="" />
    <meta name="description" content="" />
    <link href="<?=P_CSSPATH?>reset.css" rel="stylesheet" type="text/css" />
    <link href="<?=P_CSSPATH?>right.css" rel="stylesheet" type="text/css" />
    <script src="<?=P_JSPATH?>jquery.1.11.1.js" type="text/javascript"></script>

    <style>
        .jbtab tr th{font-weight:600;}
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
        <p class="icon1">查看被考核人 >  <?=$record_info['relation']['username']?></p>
    </div>
    <div class="kctjcon">
        <p class="tjtip">考核基本信息</p>
        <form action="" method="post" id="sub_form" class="clearfix" >
            <input type="hidden" id="hidden_user_id" value="<?=$record_info['relation']['userId']?>"/>
            <input type="hidden" id="hidden_base_id" value="<?=$record_info['relation']['base_id']?>"/>
            <div class="baseinfo">
                <table cellpadding="0" cellspacing="0" width="100%">
                    <tr>
                        <td width="188" align="right"> 被考核人姓名：&nbsp;</td>
                        <td>
                            <?=$record_info['relation']['username']?>
                        </td>
                    </tr>
                    <?=$assessAttrWidget->renderTableBaseInfo($record_info['relation']['base_id'],$record_info['relation']['userId'])?>
                    <?php if($record_info['relation']['assess_attr_type']){?>
                        <tr>
                            <td align="right">考核类型选择：&nbsp;</td>
                            <td id="attr_type_checkboxes_td">
                                <?php
                                    $assessType = array(
                                        '1'=>'任务/指标类',
                                        '2'=>'打分类',
                                        '3'=>'提成类'
                                    );
                                    $assessTypeInfo = @$assessType[$record_info['relation']['assess_attr_type']];
                                ?>
                                <span><?=$assessTypeInfo?></span>
                            </td>
                        </tr>
                    <?php }?>
                    <tr>
                        <td align="right">按月生成：&nbsp;</td>
                        <td id="attr_type_checkboxes_td">
                            <?php
                                $createOnMonth = ($record_info['base']['create_on_month_status']==1)?'是':'否';
                            ?>
                            <span><?=$createOnMonth?></span>
                        </td>
                    </tr>
                    <tr>
                        <td align="right">直接领导设置：&nbsp;</td>
                        <td id="attr_type_checkboxes_td">
                            <?php
                                $leadSet = ($record_info['base']['lead_direct_set_status']==1)?'是':'否';
                            ?>
                            <span><?=$leadSet?></span>
                        </td>
                    </tr>
                    <tr>
                        <td align="right">流程状态：&nbsp;</td>
                        <td id="attr_type_checkboxes_td">
                            <?php
                                if($_REQUEST['act']=='hrViewStaffDetail'){
                                    $statusMap = AssessFlowDao::$UserAssessStatusByHr;
                                }elseif($_REQUEST['act']=='leadViewStaffDetail'){
                                    $statusMap = AssessFlowDao::$UserAssessStatusByLeader;
                                }elseif($_REQUEST['act']=='staffViewStaffDetail'){
                                    $statusMap = AssessFlowDao::$UserAssessStatusByStaff;
                                }
                            ?>
                                <span><?=$statusMap[$record_info['relation']['user_assess_status']]?></span>
                        </td>
                    </tr>
                    <tr>
                        <td align="right">最终得分：&nbsp;</td>
                        <td id="attr_type_checkboxes_td">

                            <span><?=($record_info['relation']['score'])?$record_info['relation']['score']:'';?></span>
                        </td>
                    </tr>
                    <?php if($record_info['relation']['rejectText']){?>
                        <tr>
                            <td align="right">驳回理由：&nbsp;</td>
                            <td>
                                <span style="color: red;"><?=$record_info['relation']['rejectText']?></span>
                            </td>
                        </tr>
                    <?php }?>
                </table>
            </div>
            <div class="pad25">
                <div class="attr_content">
                    <!--考核属性表格-->
                    <?=$assessAttrWidget->renderItemTable($record_info)?>
                </div>
            </div>
            <div class="kctjbot">
                <input type="button" class="btn67" value="返回"  onclick="history.go(-1);"/>
            </div>
        </form>
    </div>
</div>
<div class="tck" style="display:none;"></div>
</body>
</html>
