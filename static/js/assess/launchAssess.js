var Assess = function(){};
Assess.prototype = {
    triggerBusSelect:function(){
       var bus_area_parent =  $("#bus_area_parent").val();
        this.getAjaxBusChildList(bus_area_parent);
    },

    getAjaxBusChildList:function(bus_area_parent){
        $.ajax({
            type:'get',
            url:'/salary/index.php',
            data:{m:'assessment',a:'launchAssess',act:'ajaxBusClassify',bus_area_parent:bus_area_parent},
            dataType:'json',
            success:function(ret){
                if(ret.status=='success'){
                    var opList = "<option value=''>请选择</option>";
                    var p_id = $("#bus_area_child_hidden").val();
                    for(var i=0;i<ret.data.length;i++){
                        var selected = (p_id==ret.data[i].value)?"selected=selected":"";
                        opList+="<option value='"+ret.data[i].value+"' "+selected+">"+ret.data[i].name+"</option>";
                    }
                    $("#bus_area_child").html(opList);
                }
            }
        });
    },

    getLeadDirectSetValue:function(){
        return ($("#lead_direct_set_status").is(":checked"))?1:0;
    },

    getAttrTypeCheckedValue:function(){
        return $("#attr_type_checkboxes_td input:checked").val();
    },

    selectAttrType:function(){
        var lead_direct_set_status = this.getLeadDirectSetValue();
        if(!lead_direct_set_status){
            var selectedType = this.getAttrTypeCheckedValue();
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
        var lead_direct_set_status = this.getLeadDirectSetValue();
        if(lead_direct_set_status){
            $(".attr_content").children("div").each(function(){
                $(this).hide();
            });
        }else{
            this.selectAttrType();
        }
    },
    formSubHandle:function(){
        var subFormData = this.getSubFormData();
        console.log(subFormData);
    },
    getSubFormData:function(){
        var subFormData = {};
        subFormData.baseData = this.getBaseData();
        subFormData.attrData = this.getAttrData();
        return subFormData;
    },

    //获取表单基本数据
    getBaseData:function(){
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
            ],
            baseSubDataList:{}
        };
        baseData.parentPro = this;
        //获取base表基本数据
        baseData.getBaseIdsValue = function(){
            var baseIdData = {};
            for(var i=0;i<=this.baseIdsNameList.length;i++){
                baseIdData[this.baseIdsNameList[i]] = $("#"+this.baseIdsNameList[i]).val();
            }
            baseIdData['assess_attr_type'] = this.parentPro.getAttrTypeCheckedValue();
            baseIdData['lead_direct_set_status'] = this.parentPro.getLeadDirectSetValue();

            this.baseSubDataList = baseIdData;
        };

        baseData.initData = function(){
            this.getBaseIdsValue();
        }
        baseData.initData();
        return baseData;
    },

    //获取属性分类扩展信息
    getAttrData:function(){
        var attrData = {};
        var fHandler = {
            retData :{},
            getHandler:function(attrCheckedType){
                var type = attrCheckedType;
               this.retData.type = type;
                switch (type){
                    case '1':
                        this.retData.handlerData = this.c_j_handler();
                        break;

                    case '2':
                        this.retData.handlerData = this.s_handler();
                        break;

                    case '3':
                        this.retData.handlerData = this.t_handler();
                        break;
                }
                return this;
            },
            getHandlerData:function(){
                return this.retData;
            },

            c_j_handler:function(){
                //量化指标类
                var c_data = {table_data:[]};
                var c_table = $(".attr_form_1[flag=1] table");
                c_data.height = $(".attr_form_1[flag=1] input[name=attr1_widget]").val();
                c_table.find("tr").each(function(){
                    var tmp = {};
                    tmp.indicator_parent = $(this).find("select[name=indicator_parent]").val();
                    tmp.indicator_child = $(this).find("select[name=indicator_child]").val();
                    tmp.zbyz = $(this).find("input[name=zbyz]").val();
                    tmp.weight = $(this).find("input[name=qz]").val();
                    c_data['table_data'].push(tmp);
                });

                //工作任务类
                var j_data =  {table_data:[]};
                var j_table = $(".attr_form_1[flag=2] table");
                j_data.height = $(".attr_form_1[flag=2] input[name=attr2_widget]").val();
                j_table.find("tr").each(function(k,v){
                    var tmp = {};
                    tmp.job_name = $(this).find("input[name=job_name]").val();
                    tmp.zbyz = $(this).find("input[name=zbyz]").val();
                    tmp.weight = $(this).find("input[name=qz]").val();
                    j_data['table_data'].push(tmp);
                });
                return {commission:c_data,job:j_data};
            },
            s_handler : function(){
                //打分类
                var s_data = {table_data:[]};
                var s_table = $(".attr_form_2[flag=3] table");
                s_data.cash = $(".attr_form_2[flag=3] input[name=attr3_cash]").val();
                s_table.find("tr").each(function(k,v){
                    var tmp = {};
                    tmp.score_name = $(this).find("input[name=score_name]").val();
                    tmp.zbyz = $(this).find("input[name=zbyz]").val();
                    s_data['table_data'].push(tmp);
                });
                return {score:s_data};
            },

            t_handler: function(){
                //提成类
                var t_data = {table_data:[]};
                var t_table = $(".attr_form_3[flag=4] table");
                t_data.cash = $(".attr_form_3[flag=4] input[name=attr3_cash]").val();
                t_table.find("tr").each(function(k,v){
                    var tmp = {};
                    tmp.score_name = $(this).find("input[name=score_name]").val();
                    tmp.zbyz = $(this).find("input[name=zbyz]").val();
                    t_data['table_data'].push(tmp);
                });
                return {target:t_data};
            }

        };
        var attrCheckedType = this.getAttrTypeCheckedValue();
        if(true || !this.getLeadDirectSetValue()){
            attrData['fromData'] = fHandler.getHandler(attrCheckedType).getHandlerData();
        }
        return attrData;
    },

    /*添加指标*/
    addItem:function(jDom,type){
        var itemContainer = jDom.parent().find('.kctjcon:eq(1) .sm_div table');
        var clonedItemDom = itemContainer.find('tr:eq(0)');
        var cDom = $.extend(true,{}, clonedItemDom);
        itemContainer.append("<tr>"+cDom.html()+"</tr>");
         this.clearItemData(itemContainer.find('tr:last'),type);
    },

    /*清空克隆item节点里面的数据*/
    clearItemData:function(itemDom,type){
        switch (type){
            case '1':
                        itemDom.find("select[name=indicator_parent] option:eq(0)").attr("checked",true);
                        itemDom.find("select[name=indicator_child] option:eq(0)").attr("checked",true);
                        itemDom.find("input[name=zbyz]").val('');
                        itemDom.find("input[name=qz]").val('');
                break;

            case '2':
                        itemDom.find("input[name=job_name]").val('');
                        itemDom.find("input[name=zbyz]").val('');
                break;

            case '3':
                        itemDom.find("input[name=score_name]").val('');
                        itemDom.find("input[name=zbyz]").val('');
                break;

            case '4':
                        itemDom.find("input[name=score_name]").val('');
                        itemDom.find("input[name=zbyz]").val('');
                break;
        }
        return itemDom;
    }
};