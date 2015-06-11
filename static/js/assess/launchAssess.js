function Assess(){
}
Assess.prototype = {
    selectAttrType:function(){
        var lead_direct_set_status = $("#lead_direct_set_status").is(":checked");
        if(!lead_direct_set_status){
            var selectedType = $("#attr_type_checkboxes_td input:checked").val();
            $(".attr_content").children("div").each(function(){
                if($(this).hasClass('attr_form_'+selectedType)){
                    $(this).show();
                }else{
                    $(this).hide();
                }
            });
        }
    },
    selectLeadSetStatus:function(){
        var lead_direct_set_status = $("#lead_direct_set_status").is(":checked");
        if(lead_direct_set_status){
            $(".attr_content").children("div").each(function(){
                $(this).hide();
            });
        }else{
            this.selectAttrType();
        }
    },
    formSubHandle:function(){


    },
    getSubFormData:function(){
        var getBaseIdsValue = function(idNameList){
            var baseIdData = {};
            for(var i=0;i<=idNameList.length;i++){
                baseIdData[idNameList[i]] = $("#"+idNameList[i]).val();
            }
            return baseIdData;
        };
        var baseData = {
            baseIdsNameList:[
                    'base_name',
                    'bus_area_parent',
                    'bus_area_child',
                    'uids',
                    'assess_period_type',
                    'base_start_date',
                    'staff_plan_start_date',
                    'staff_plan_end_date',
                    'lead_plan_start_date',
                    'lead_plan_end_date',
                    'staff_sub_start_date',
                    'staff_sub_end_date',
                    'lead_sub_start_date',
                    'lead_sub_end_date'
            ]
        };
        baseData.initData = function(){
            this.getBaseIdsValue();
        }
        baseData.getBaseIdsValue = function(){
            var baseIdData = {};
            for(var i=0;i<=this.baseIdsNameList.length;i++){
                baseIdData[this.baseIdsNameList[i]] = $("#"+this.baseIdsNameList[i]).val();
            }

            baseIdData['assess_attr_type'] = $("#assess_attr_type:checked").val();
            baseIdData['lead_direct_set_status'] = ($("#lead_direct_set_status").is(":checked"))?1:0;
            this.baseSubDataList = baseIdData;
        };

        baseData.initData();

    }
};