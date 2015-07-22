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
        <p class="icon1">待我考核 > 查看考核</p>
    </div>
    <fieldset>
        <legend>考核人姓名：<span style="color:#8DDB75;"><?=$record_info['relation']['username']?></span></legend>
        <div class="kctjcon">
            <p class="tjtip">考核基本信息</p>
            <form action="" method="post" id="sub_form" class="clearfix" >
                <input type="hidden" id="hidden_user_id" value="<?=$record_info['relation']['userId']?>"/>
                <input type="hidden" id="hidden_base_id" value="<?=$record_info['relation']['base_id']?>"/>
                <div class="baseinfo">
                    <table cellpadding="0" cellspacing="0" width="100%">
                        <?=$assessAttrWidget->renderTableBaseInfo($record_info['relation']['base_id'],$record_info['relation']['userId'])?>
                        <tr>
                            <td align="right">考核类型选择：&nbsp;</td>
                            <td id="attr_type_checkboxes_td">
                                <input type="checkbox" disabled="disabled" name="assess_attr_type" value="1" <?=($record_info['relation']['assess_attr_type']==1)?"checked=\"checked\"":"";?>>[任务/指标]类&nbsp;
                                <input type="checkbox" disabled="disabled"  name="assess_attr_type" value="2" <?=($record_info['relation']['assess_attr_type']==2)?"checked=\"checked\"":"";?>>打分类&nbsp;
                                <input type="checkbox" disabled="disabled" name="assess_attr_type" value="3" <?=($record_info['relation']['assess_attr_type']==3)?"checked=\"checked\"":"";?>>提成类&nbsp;
                            </td>
                        </tr>
                    </table>
                </div>
                <div class="pad25">
                    <?=$assessAttrWidget->compareHistory($record_info);?>
                    <div class="attr_content">
                        <!--任务/指标类-->
                        <?=$assessAttrWidget->renderAttr($record_info['item'],1)?>

                        <!--打分类-->
                        <?=$assessAttrWidget->renderAttr($record_info['item'],2)?>

                        <!--提成类-->
                        <?=$assessAttrWidget->renderAttr($record_info['item'],3)?>
                    </div>
                </div>
                <div class="kctjbot">
                    <input type="button" class="btn67" value="返回"  onclick="history.go(-1);"/>
                </div>
            </form>
        </div>
    </fieldset>
</div>
<div class="tck" style="display:none;"></div>
</body>
</html>
