var Assess = function(){};
Assess.prototype = {
    triggerBusSelect:function(validAuth){
        var bus_area_parent =  $("#bus_area_parent").val();
        $.ajax({
            type:'get',
            url:'/salary/index.php',
            data:{
                m:'assessment',a:'launchAssess',act:'ajaxBusClassify',bus_area_parent:bus_area_parent,validAuth:validAuth
            },
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

    initHide:function(){
        if(this.getLeadDirectSetValue()){
            $("#attr_type_checkboxes_td").parents('tr').hide();
            $(".attr_content").hide();
        }
    },

    getLeadDirectSetValue:function(){
        return ($("#lead_direct_set_status").is(":checked"))?1:0;
    },

    getCreateOnMonthValue:function(){
        return ($("#create_on_month_status").is(":checked"))?1:0;
    },

    getAttrTypeCheckedValue:function(){
        if(!this.getLeadDirectSetValue()){
            return $("#attr_type_checkboxes_td input:checked").val();
        }else{
            return '';
        }
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
            $("#attr_type_checkboxes_td").parents('tr').hide();
            $(".attr_content").hide();
        }else{
            $("#attr_type_checkboxes_td").parents('tr').show();
            $(".attr_content").show();
            this.selectAttrType();
        }
    },
    formSubHandle:function(jumpUrl){
        var subFormData = {};
        var jumpUrl = jumpUrl;
        subFormData.baseData = this.getBaseData().baseSubDataList;
        subFormData.attrData = this.getAttrData();
        var data = {
            m:'assessment',
            a:'launchAssess',
            formSubTag:1,
            subFormData:subFormData
        };

        $.ajax({
            type:'post',
            url:'/salary/index.php',
            data:data,
            dataType:'json',
            success:function(retData){
                if(retData.status=='success'){
                    alert('保存成功！');
                    location.href = jumpUrl;
                }
            }
        });
    },

    //获取表单基本数据
    getBaseData:function(){
        var baseData = {
            baseIdsNameList:[
                'base_id',
                'base_name',
                'bus_area_parent',
                'bus_area_child',
                'uids',
                'assess_period_type',
                'base_start_date',
                'staff_sub_start_date',
                'create_on_month_status'
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
            baseIdData['create_on_month_status'] = this.parentPro.getCreateOnMonthValue();
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

            c_j_handler:function(){
                //量化指标类
                var c_data = {table_data:[]};
                var c_table = $(".attr_form_1[flag=1] table");
                c_table.find("tr").each(function(){
                    var tmp = {};
                    tmp.indicator_parent = $(this).find("select[name=indicator_parent]").val();
                    tmp.indicator_child = $(this).find("select[name=indicator_child]").val();
                    tmp.zbyz = $(this).find("input[name=zbyz]").val();
                    tmp.qz = $(this).find("input[name=qz]").val();
                    tmp.selfScore = $(this).find("input[name=selfScore]").val();
                    tmp.leadScore = $(this).find("input[name=leadScore]").val();
                    c_data['table_data'].push(tmp);
                });

                //工作任务类
                var j_data =  {table_data:[]};
                var j_table = $(".attr_form_1[flag=2] table");
                j_table.find("tr").each(function(k,v){
                    var tmp = {};
                    tmp.job_name = $(this).find("input[name=job_name]").val();
                    tmp.zbyz = $(this).find("input[name=zbyz]").val();
                    tmp.qz = $(this).find("input[name=job_qz]").val();
                    tmp.selfScore = $(this).find("input[name=selfScore]").val();
                    tmp.leadScore = $(this).find("input[name=leadScore]").val();
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
                    tmp.selfScore = $(this).find("input[name=selfScore]").val();
                    tmp.leadScore = $(this).find("input[name=leadScore]").val();
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
                    tmp.tc_name = $(this).find("input[name=tc_name]").val();
                    tmp.finishCash = $(this).find("input[name=finishCash]").val();
                    t_data['table_data'].push(tmp);
                });
                return {target:t_data};
            },

            getHandlerData:function(){
                return this.retData;
            }

        };
        var attrCheckedType = this.getAttrTypeCheckedValue();
        if(!this.getLeadDirectSetValue()){
            attrData['fromData'] = fHandler.getHandler(attrCheckedType).getHandlerData();
        }
        return attrData;
    },

    /*添加指标*/
    addItem:function(jDom,type){
        var itemContainer = jDom.parent().find('.kctjcon:eq(0) .sm_div table');
        if( itemContainer.find('tr').length>0){
            var clonedItemDom = itemContainer.find('tr:eq(0)');
            var cDom = $.extend(true,{}, clonedItemDom);
        }else if(this.delTrCache[type] !=undefined){
            var cDom = this.delTrCache[type];
        }
        console.log(cDom);
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
                itemDom.find("input[name=selfScore]").val('');
                itemDom.find("input[name=leadScore]").val('');
                break;

            case '2':
                itemDom.find("input[name=job_name]").val('');
                itemDom.find("input[name=job_qz]").val('');
                itemDom.find("input[name=selfScore]").val('');
                itemDom.find("input[name=leadScore]").val('');
                break;

            case '3':
                itemDom.find("input[name=score_name]").val('');
                itemDom.find("input[name=selfScore]").val('');
                itemDom.find("input[name=leadScore]").val('');
                break;

            case '4':
                itemDom.find("input[name=tc_name]").val('');
                itemDom.find("input[name=finishCash]").val('');
                break;
        }
        return itemDom;
    },

    delTrCache:{},
    //删除item节点
    delItemDom:function(dBtn,type){
        if($(dBtn).parents('table').find('tr').length==1){
            this.delTrCache[type] =  $.extend(true,{}, $(dBtn).parents('tr'));
        }
        $(dBtn).parents('tr').remove();
    },
    triggerIndicatorSelect:function(jSelectDom){
        var jSelectDom =jSelectDom;
        var indicator_parent =  jSelectDom.find("option:selected").val();
        if(this.delTrCache.indicator_ajax_cache==undefined){
            this.delTrCache.indicator_ajax_cache = {};
        }
        var ajax_cache = this.delTrCache.indicator_ajax_cache;
        ajax_cache.replaceChildSelect = function(jSelectDom,data){
            var opList = "<option value=''>请选择</option>";
            var p_id = jSelectDom.parent().find('.indicator_parent_hidden').val();
            for(var i=0;i<data.length;i++){
                var selected = (p_id==data[i].childId)?"selected=selected":"";
                opList+="<option value='"+data[i].childId+"' "+selected+">"+data[i].title+"</option>";
            }
            jSelectDom.parent().find('.commission_indicator_child').html(opList);
        }

        if(ajax_cache[indicator_parent] == undefined){
            $.ajax({
                type:'get',
                url:'/salary/index.php',
                data:{m:'assessment',a:'launchAssess',act:'ajaxIndicatorClassify',indicator_parent:indicator_parent},
                dataType:'json',
                success:function(ret){
                    if(ret.status=='success'){
                        ajax_cache[indicator_parent] = ret.data; //缓存ajax结果
                        ajax_cache.replaceChildSelect(jSelectDom,ret.data)
                    }
                }
            });
        }else{
            ajax_cache.replaceChildSelect(jSelectDom,ajax_cache[indicator_parent])
        }
    },
    tableTopChecked:function(dom){
        var checked = $(dom).prop("checked");
        $(dom).parents('table ').find('tbody tr:gt(0)').each(function(){
            if(!$(this).find('td:eq(0) input').is(":disabled")){
                $(this).find('td:eq(0) input').prop('checked',checked);
            }
        });
    },
    tableBtnHandler:function(jTableElement,filterItem,callback){
        var selectedItem = [];
        var callBackStatus = true;
        jTableElement.find('tr:gt(0)').each(function(){
            var input = $(this).find('td:eq(0) input');
            if(input.prop("checked")==true){
                var itemId = input.attr('tag');
                if( typeof filterItem == 'function'){
                    if(!filterItem(input)){
                        callBackStatus = false;
                        return false;
                    }
                }
                selectedItem.push(itemId);
            }
        });
        if(callBackStatus){
            if(selectedItem.length>0){
                callback(selectedItem);
            }else{
                alert('请先勾选操作项');
            }
        }
    },
    jump:function(url,mis){
        var j = function(url){
           location.href = url;
        }
        setTimeout(j,mis,url);
    },
    adduser:function(userId,username){
        var uids = $("#uids").val();
        var uidArr = $("#uids").val().split(',');
        var status = true;
        for(var i=0;i<uidArr.length;i++){
            if(uidArr[i]==userId){
                status = false;
            }
        }
        if(status){
            if(uids==''){
                uids =userId;
            }else{
                uids+=","+userId;
            }
            $("#uids").val(uids);
            if($(".div_userlist").is(":hidden")){
                $(".div_userlist").show();
            }
            var newSpan = "<span id=\"span_auto_"+userId+"\">"+username+"<a id=\""+userId+","+username+"\" href=\"javascript:void(0)\" class=\"close deluser\" onclick=\"Assess.prototype.delUserADom(this)\"></a></span>";
            $(".div_userlist .userlist").append(newSpan);
            $("#username").val('');
        }
    },
    delUserADom:function(dom){
        //获取删除的userId
        var idArr = $(dom).parent().attr('id').split('_');
        var index = idArr.length-1;
        var userId = idArr[index];

        //获取遍历除userId的uids
        var uids = [];
        var uidArr = $("#uids").val().split(',');
        for(var i=0;i<uidArr.length;i++){
            if(uidArr[i]!=userId){
                uids.push(uidArr[i]);
            }
        }
        $("#uids").val(uids.join(','));
        $(dom).parent().remove();
        if($(".div_userlist .userlist span").length==0){
            $(".div_userlist").hide();
        }
    },
    userListTrigger:function(uidArr,delUids){
        for(var i=0;i<uidArr.length;i++){
            this.adduser(uidArr[i]['userId'],uidArr[i]['username']);
        }
        for(var i=0;i<delUids.length;i++){
            $("#span_auto_"+delUids[i]).find("a").trigger('click');
        }
    },
    selectChildValid:function(select){
        if($(select).val()=='' && $(select).is(":visible")){
            $(select).addClass('redBorder');
            return false;
        }else{
            $(select).removeClass('redBorder');
            return true;
        }
    },
    submitSelectValid:function(){
        var status = true;
        $("#bus_area_child,.commission_indicator_child").each(function(){
            if(Assess.prototype.selectChildValid(this)==false){
                status = false;
            }
        });
        return status;
    }
};