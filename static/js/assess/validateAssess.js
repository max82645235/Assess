/**
 * Created by Administrator on 15-6-24.
 */
$(function(){
    jQuery.extend(jQuery.validator.messages, {
        required: "�����ֶ�",
        remote: "���������ֶ�",
        email: "��������ȷ��ʽ�ĵ����ʼ�",
        url: "������Ϸ�����ַ",
        date: "������Ϸ�������",
        dateISO: "������Ϸ������� (ISO).",
        number: "������Ϸ�������",
        digits: "ֻ����������",
        creditcard: "������Ϸ������ÿ���",
        equalTo: "���ٴ�������ͬ��ֵ",
        accept: "������ӵ�кϷ���׺�����ַ���",
        maxlength: jQuery.validator.format("������һ����������� {0} ���ַ���"),
        minlength: jQuery.validator.format("������һ������������ {0} ���ַ���"),
        rangelength: jQuery.validator.format("������һ�����Ƚ��� {0} �� {1} ֮����ַ���"),
        range: jQuery.validator.format("������һ������ {0} �� {1} ֮���ֵ"),
        max: jQuery.validator.format("������һ�����Ϊ {0} ��ֵ"),
        min: jQuery.validator.format("������һ����СΪ {0} ��ֵ")
    });
    $.validator.setDefaults({
        showErrors: function(map, list) {
            // there's probably a way to simplify this
            var focussed = document.activeElement;
            if (focussed && $(focussed).is("input, textarea")) {
                $(this.currentForm).tooltip().tooltip("close", {
                    currentTarget: focussed
                }, true);
            }
            this.currentElements.removeAttr("title").removeClass("ui-state-error");
            $.each(list, function(index, error) {
                $(error.element).attr("title", error.message).addClass("ui-state-error");
            });

        }
    });

    $.validator.addMethod('userListRequire',function(value, element, arg){
        if($(".div_userlist .userlist span").length==0){
            return false;
        }
        return true;
    },'������дһ���������ˣ�');



    $.validator.addMethod('dateFormat',function(value, element, arg){
        var dateReg = /^((?:19|20)\d\d)-(0[1-9]|1[012])-(0[1-9]|[12][0-9]|3[01])$/;
        if(dateReg.test(value)){
            return true;
        }
        return false;
    },'����д��ȷ�����ڸ�ʽ��');

    $.validator.addMethod('compareDate',function(value,element,arg){
        var startDate = $(arg).val();
        var date1 = new Date(Date.parse(startDate.replace("-", "/")));
        var date2 = new Date(Date.parse(value.replace("-", "/")));
        return date1 <= date2;
    },'�������ڱ�����ڿ�ʼ����');

    $.validator.addMethod('percent',function(value,element,arg){
        var re = /^[0-9]+.?[0-9]*$/;
        return (value>=0 && value<=100 && re.test(value));
    },'�����0-100֮�ڵ�����');

    $.validator.addMethod('totalQz',function(value,element,arg){
        var cntQz = 0;
        var emptyStatus = false;
        $(".attr_form_1[flag=1] .sm_xsmbadd tr").each(function(){
            if($(this).find('td:eq(1) input[tagname=qz]').val()!=''){
                cntQz+=parseInt($(this).find('td:eq(1) input[tagname=qz]').val());
                emptyStatus = true;
            }
        });

        $(".attr_form_1[flag=2] .sm_xsmbadd tr").each(function(){
            if($(this).find('td:eq(1) input[tagname=job_qz]').val()!=''){
                cntQz+=parseInt($(this).find('td:eq(1) input[tagname=job_qz]').val());
                emptyStatus = true;
            }

        });
        if(cntQz==100 && emptyStatus){
            return true;
        }
        return false;
    },'�뱣֤������Ȩ��֮��Ϊ100');


    $.myValidate = $("#sub_form").validate({
        debug:true,
        meta:"validate",
        rules:{
            base_name:{
                required: true
            },
            username:{
                userListRequire:true
            },
            base_start_date:{
                dateFormat:true
            },
            lead_sub_start_date:{
                dateFormat:true
            },
            staff_sub_start_date:{
                dateFormat:true
            },
            //commission����
            attr1_weight:{
                required: true,
                percent:true
            },
            zbyz:{
                required: true,
                digits:true
            },
            qz:{
                required: true,
                percent:true,
                totalQz:true
            },

            //����
            selfScore:{
                required: true,
                percent:true
            },
            leadScore:{
                required: true,
                percent:true
            },

            //job����
            job_name:{
                required: true
            },
            job_qz:{
                required: true,
                percent:true,
                totalQz:true
            },
            attr2_weight:{
                required: true
            },
            //score����
            score_name:{
                required: true
            },
            attr3_cash:{
                required: true,
                number:true
            },

            //target����
            tc_name:{
                required: true,
                percent:true
            },
            finishCash:{
                required: true,
                number:true
            }
        }
    });
});