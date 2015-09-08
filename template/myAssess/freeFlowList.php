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
        <p class="icon1">������ > �������б�</p>
    </div>
    <div class="pad25">
        <div class="brdbt zykc" style="height: 30px;">
            <form name="frm" action="" method="get" class="clearfix" style="float: left;width: 90%;">
                <input type="hidden" name="m" value="myassessment">
                <input type="hidden" name="a" value="freeFlow">
                <input type="hidden" name="act" value="freeFlowList">
                <div class="jssel" style="z-index:98">
                    &nbsp;&nbsp;&nbsp;�������ƣ�
                    <input type="text" value="<?=(isset($_REQUEST['base_name']))?$_REQUEST['base_name']:'';?>" name="base_name" id="base_name" class="width135" placeholder="�����뿼������"  style="margin-bottom: 3px;">
                </div>
                <div class="jssel" style="z-index:98">
                    &nbsp;&nbsp;&nbsp;״̬��
                    <select name="isNew">
                        <option value=""  <?php if(!$_REQUEST['isNew']){?> selected="selected" <?php }?>>ȫ��</option>
                        <option value="1" <?php if($_REQUEST['isNew']==1){?> selected="selected" <?php }?>>δ���</option>
                        <option value="2" <?php if($_REQUEST['isNew']==2){?> selected="selected" <?php }?>>�����</option>
                    </select>
                </div>
                <div class="jssel" style="z-index:98;margin-left: 20px;margin-bottom: 5px;">
                    <input type="submit" value="����" class="btn48">
                </div>
            </form>
        </div>

        <div class="mrtb10" >
            <table cellpadding="0" cellspacing="0" width="100%" class="jbtab" id="table_style" >
                <tr >
                    <th width="180" class="left" style="text-align: center;">��Ч��������</th>
                    <th width="120"  class="left" style="text-align: center;">����������</th>
                    <th class="left" style="text-align: center;">����</th>
                    <th width="100" style="text-align: center;">����Ƶ��</th>
                    <th width="150" style="text-align: center;">��������</th>
                    <th width="150" style="text-align: center;">����״̬</th>
                    <th width="150" style="text-align: center;">����</th>
                </tr>
                <?php if($tableData){?>
                    <?php foreach($tableData as $k=>$data){?>
                        <tr class="<?=($k%2)?'bgfff':'bgf0';?>" assess_status="<?=$data['user_assess_status']?>">
                            <td class="left"><?=$data['base_name']?></td>
                            <td class="left"><?=$data['username']."(".$data['card_no'].")"?></td>
                            <td class="left"><?=$data['deptlist']?></td>
                            <td ><?=AssessDao::$AssessPeriodTypeMaps[$data['assess_period_type']]?></td>
                            <td>
                                <?=date('Y/m/d',strtotime($data['base_start_date']))?> -
                                <?=date('Y/m/d',strtotime($data['base_end_date']))?>
                            </td>
                            <td>
                                <?=($data['isNew'] && $data['user_assess_status']==5)?'<span style="color: #AB82FF;">�����</span>':'�����';?>
                            </td>
                            <td class="left">
                                <a href="?m=myassessment&a=waitMeAssess&act=leadViewStaffDetail&userId=<?=$data['userId']?>&base_id=<?=$data['base_id']?>" class="bjwrt">�鿴</a>
                                <?php if($data['isNew'] && $data['user_assess_status']==5){?>
                                    <a href="?m=myassessment&a=waitMeAssess&act=leaderSetFlow&userId=<?=$data['userId']?>&base_id=<?=$data['base_id']?>" class="bjwrt" style="color: #AB82FF;">
                                        ���
                                    </a>
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
