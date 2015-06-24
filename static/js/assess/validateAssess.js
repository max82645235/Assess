/**
 * Created by Administrator on 15-6-24.
 */
$(function(){
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
        if($("#bus_area_child").val()==''){
            return false;
        }
        return true;
    },'业务单元必填！');



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
            }
        }
    });
});