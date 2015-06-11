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
    }
};