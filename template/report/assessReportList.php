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
        <p class="icon1">����ͳ�� > ��Ч����</p>
    </div>
    <div class="pad25">
        <div class="brdbt zykc" style="height: 30px;">
            <form name="frm" action="" method="get" class="clearfix" style="float: left;width: 90%;">
                <input type="hidden" name="m" value="report">
                <input type="hidden" name="a" value="assessReport">
                <input type="hidden" name="act" value="assessReportList">
                <div class="jssel" style="z-index:98">
                    &nbsp;&nbsp;&nbsp;�������ڣ�
                    <select name="assess_period_type">
                        <option value="">��ѡ��</option>
                        <?php foreach(AssessDao::$AssessPeriodTypeMaps as $k=>$v){?>
                            <option value="<?=$k?>"
                                <?php if(isset($_REQUEST['assess_period_type']) && $_REQUEST['assess_period_type']==$k){?> selected="selected"<?php }?>>
                                <?=$v?>
                            </option>
                        <?php }?>
                    </select>
                </div>
                <div class="jssel" style="z-index:98">
                    &nbsp;&nbsp;&nbsp;��ݣ�
                    <select name="assess_year">
                        <option value="">��ѡ��</option>
                        <?php for($i=2015;$i<=2025;$i++){?>
                            <option value="<?=$i?>"  <?php if(isset($_REQUEST['assess_year'])&&$_REQUEST['assess_year']==$i){?> selected="selected" <?php }?>><?=$i?>��</option>
                        <?php }?>
                    </select>
                </div>

                <div class="jssel" style="z-index:98">
                    &nbsp;&nbsp;&nbsp;�·ݣ�
                    <select name="assess_month">
                        <option value="">��ѡ��</option>
                        <?php for($i=1;$i<=12;$i++){?>
                            <option value="<?=$i?>" <?php if(isset($_REQUEST['assess_month'])&&$_REQUEST['assess_month']==$i){?> selected="selected" <?php }?>><?=$i?>��</option>
                        <?php }?>
                    </select>
                </div>
                <div class="jssel" style="z-index:98">
                    &nbsp;&nbsp;&nbsp;����״̬��
                    <select name="user_assess_status" style="width: 150px;">
                        <option value=""    <?php if(isset($_REQUEST['user_assess_status']) && $_REQUEST['user_assess_status']===''){?> selected="selected"<?php }?>>��ѡ��</option>
                        <?php foreach(AssessFlowDao::$UserAssessStatusByHr as $k=>$val){?>
                            <option value="<?=$k?>"  <?php if(isset($_REQUEST['user_assess_status']) && $_REQUEST['user_assess_status']!=='' && $_REQUEST['user_assess_status']==$k){?> selected="selected"<?php }?>><?=$val?></option>
                        <?php }?>
                    </select>
                </div>
                <div class="jssel" style="z-index:98">
                    &nbsp;&nbsp;&nbsp;������������
                    <input type="text" value="<?=(isset($_REQUEST['username']))?$_REQUEST['username']:'';?>" name="username" id="username" class="width135" placeholder="�����뿼��������"  style="margin-bottom: 3px;">
                </div>
                <div class="jssel" style="z-index:98;margin-left: 20px;margin-bottom: 5px;">
                    <input type="submit" value="����" class="btn48">
                </div>
            </form>
        </div>

        <div class="mrtb10" >
            <table cellpadding="0" cellspacing="0" width="100%" class="jbtab" id="table_style" >
                <tr >
                    <th width="100" style="text-align: center;">����������</th>
                    <th width="100" style="text-align: center;">��������</th>
                    <th width="200" style="text-align: center;">����ʱ��</th>
                    <th width="200" style="text-align: center;">��������</th>
                    <th width="200" style="text-align: center;">����״̬</th>
                    <th width="100" style="text-align: center;">�÷�</th>
                </tr>
                <?php if($tableData){?>
                    <?php foreach($tableData as $k=>$data){?>
                        <tr class="<?=($k%2)?'bgfff':'bgf0';?>">
                            <td ><?=$data['username']?></td>
                            <td ><?=AssessDao::$AssessPeriodTypeMaps[$data['assess_period_type']]?></td>
                            <td>
                                <?=date('Y/m/d',strtotime($data['base_start_date']))?> -
                                <?=date('Y/m/d',strtotime($data['base_end_date']))?>
                            </td>
                            <td><?=AssessDao::$attrTypeMaps[$data['assess_attr_type']]?></td>
                            <td><?=AssessFlowDao::$UserAssessStatusByHr[$data['user_assess_status']]?></td>
                            <td width="100" style="text-align: center;"><?=($data['score'])?$data['score']:'';?></td>
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
