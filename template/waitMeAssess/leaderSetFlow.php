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
    <script>
        var AssessInstance =  new Assess();
        $(function(){
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

            //表单提交sub
            $("#sub_form").submit(function(e){
                AssessInstance.formSubHandle();

                e.preventDefault();
            });

            //追加属性节点
            $(".sm_target").click(function(){
                var type = $(this).parent('').attr('flag');
                AssessInstance.addItem($(this),type);
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
        <p class="icon1">待我考核 > <?=AssessFlowDao::$UserAssessStatusByLeader[$record_info['relation']['user_assess_status']]?></p>
    </div>
    <fieldset>
        <legend>考核人名称：<span style="color:#8DDB75;"><?=$record_info['relation']['username']?></span></legend>
        <div class="kctjcon">
            <p class="tjtip">考核基本信息</p>
            <form action="" method="post" id="sub_form" class="clearfix" >
                <div class="baseinfo">
                    <?=$assessAttrWidget->renderTableBaseInfo($record_info['relation']['base_id'])?>

                </div>
                <div class="pad25">
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
                    <input type="submit" class="bluebtn" value="确定" />
                    <input type="button" class="btn67" value="返回"  onclick="history.go(-1);"/>
                </div>
            </form>
        </div>
    </fieldset>
</div>
<div class="tck" style="display:none;"></div>
</body>
</html>
