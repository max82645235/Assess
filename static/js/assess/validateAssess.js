/**
 * Created by Administrator on 15-6-24.
 */
$(function(){
    jQuery.extend(jQuery.validator.messages, {
        required: "必选字段",
        remote: "请修正该字段",
        email: "请输入正确格式的电子邮件",
        url: "请输入合法的网址",
        date: "请输入合法的日期",
        dateISO: "请输入合法的日期 (ISO).",
        number: "请输入合法的数字",
        digits: "只能输入整数",
        creditcard: "请输入合法的信用卡号",
        equalTo: "请再次输入相同的值",
        accept: "请输入拥有合法后缀名的字符串",
        maxlength: jQuery.validator.format("请输入一个长度最多是 {0} 的字符串"),
        minlength: jQuery.validator.format("请输入一个长度最少是 {0} 的字符串"),
        rangelength: jQuery.validator.format("请输入一个长度介于 {0} 和 {1} 之间的字符串"),
        range: jQuery.validator.format("请输入一个介于 {0} 和 {1} 之间的值"),
        max: jQuery.validator.format("请输入一个最大为 {0} 的值"),
        min: jQuery.validator.format("请输入一个最小为 {0} 的值")
    });
    $.validator.setDefaults({
        showErrors: function(map, list) {
            // there's probably a way to simplify this
            var focussed = document.activeElement;
            if (focussed && $(focussed).is("input, textarea,select")) {
                $(this.currentForm).tooltip().tooltip("close", {
                    currentTarget: focussed
                }, true);
            }
            this.currentElements.removeAttr("title").removeClass("ui-state-highlight");
            $.each(list, function(index, error) {
                $(error.element).attr("title", error.message).addClass("ui-state-highlight");
            });

        }
    });

    $.validator.addMethod('userListRequire',function(value, element, arg){
        if($(".div_userlist .userlist span").length==0){
            return false;
        }
        return true;
    },'最少填写一个被考核人！');

    $.validator.addMethod('busChildRequire',function(value, element, arg){
        if($(element).val()==''){
            return false;
        }
        return true;
    },'二级分类必填！');



    $.validator.addMethod('dateFormat',function(value, element, arg){
        var dateReg = /^((?:19|20)\d\d)-(0[1-9]|1[012])-(0[1-9]|[12][0-9]|3[01])$/;
        if(dateReg.test(value)){
            return true;
        }
        return false;
    },'请填写正确的日期格式！');

    $.validator.addMethod('compareDate',function(value,element,arg){
        var startDate = $(arg).val();
        var date1 = new Date(Date.parse(startDate.replace("-", "/")));
        var date2 = new Date(Date.parse(value.replace("-", "/")));
        return date1 <= date2;
    },'结束日期必须大于开始日期');

    $.validator.addMethod('percent',function(value,element,arg){
        var re = /^[0-9]+.?[0-9]*$/;
        return (value>=0 && value<=100 && re.test(value));
    },'请输出0-100之内的整数');

    $.myValidate = $("#sub_form").validate({
        debug:true,
        rules:{
            base_name:{
                required: true
            },
            bus_area_child:{
                busChildRequire:true
            },
            username:{
                userListRequire:true
            },
            base_start_date:{
                dateFormat:true
            },
            staff_plan_start_date:{
                dateFormat:true
            },
            staff_plan_end_date:{
                dateFormat:true,
                compareDate:"#staff_plan_start_date"
            },
            lead_plan_start_date:{
                dateFormat:true
            },
            lead_plan_end_date:{
                dateFormat:true,
                compareDate:"#lead_plan_start_date"
            },
            staff_sub_start_date:{
                dateFormat:true
            },
            staff_sub_end_date:{
                dateFormat:true,
                compareDate:"#staff_sub_start_date"
            },
            lead_sub_start_date:{
                dateFormat:true
            },
            lead_sub_end_date:{
                dateFormat:true,
                compareDate:"#lead_sub_start_date"
            },

            //commission类型
            attr1_weight:{
                required: true,
                percent:true
            },
            indicator_child:{
                busChildRequire:true
            },
            zbyz:{
                required: true,
                digits:true
            },
            qz:{
                required: true,
                percent:true
            },

            //评分
            selfScore:{
                required: true,
                percent:true
            },
            leadScore:{
                required: true,
                percent:true
            },

            //job类型
            job_name:{
                required: true
            },
            job_qz:{
                required: true,
                percent:true
            },
            attr2_weight:{
                required: true
            },
            //score类型
            score_name:{
                required: true
            },
            attr3_cash:{
                required: true,
                number:true
            },

            //target类型
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