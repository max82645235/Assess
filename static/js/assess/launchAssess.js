var Assess = function(){};
Assess.prototype = {
    triggerBusSelect:function(validAuth){
        var bus_area_parent =  $("#bus_area_parent").val();
        $.ajax({
            type:'get',
            url:'/salary/index.php',
            data:{
                m:'unlogin',a:'assessUnlogin',act:'ajaxBusClassify',bus_area_parent:bus_area_parent,validAuth:validAuth
            },
            dataType:'json',
            success:function(ret){
                var opList = "";
                if(bus_area_parent==''){
                    var opList = "<option value=''>请选择</option>";
                }
                var p_id = $("#bus_area_child_hidden").val();
                for(var i=0;i<ret.data.length;i++){
                    var selected = (p_id==ret.data[i].value)?"selected=selected":"";
                    opList+="<option value='"+ret.data[i].value+"' "+selected+">"+ret.data[i].name+"</option>";
                }
                $("#bus_area_child").html(opList);
            }
        });
    },

    initHide:function(){
        if(this.getLeadDirectSetValue()){
            $("#attr_type_checkboxes_td").parents('tr').hide();
            $(".attr_content").hide();
        }

        this.selectAttrType();
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
    formSubHandle:function(jumpUrl,status){
        var subFormData = {};
        var jumpUrl = jumpUrl;
        var status = status;
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
                    if(status==1){
                        alert('保存成功');
                    }else if(status==2){
                        jumpUrl+=retData.base_id;
                    }
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
                var c_table = $(".attr_form_1[flag=1] table.sm_xsmbadd");
                var tmp = {};
                c_table.children('tbody').children("tr:visible").each(function(){
                    if($(this).hasClass('ext_assess_tr')){
                        tmp.reachTime = $(this).find('input.hasDatepicker').val();
                        tmp.sourceData = $(this).find('input.sourceData').val();
                        tmp.detailTxt = $(this).find('textarea.detailCommisonTextarea').val();
                        tmp.assessStad = $(this).find('textarea.assessStad').val();
                        tmp.selfAssess = $(this).find('textarea.selfAssess').val();
                        tmp.leadAssess = $(this).find('textarea.leadAssess').val();
                        c_data['table_data'].push(tmp);
                        tmp = {};
                    }else{
                        tmp.indicator_parent = $(this).find("select[name=indicator_parent]").val();
                        tmp.indicator_child = $(this).find("select[name=indicator_child]").val();
                        tmp.zbyz = $(this).find("input[tagname=zbyz]").val();
                        tmp.qz = $(this).find("input[tagname=qz]").val();
                        tmp.selfScore = $(this).find("input[tagname=selfScore]").val();
                        tmp.selfAssess = $(this).find("input[tagname=selfAssess]").val();
                        tmp.leadScore = $(this).find("input[tagname=leadScore]").val();
                    }
                });

                //工作任务类
                var j_data =  {table_data:[]};
                var j_table = $(".attr_form_1[flag=2] table.sm_xsmbadd");
                tmp = {};
                j_table.children('tbody').children("tr:visible").each(function(k,v){
                    if($(this).hasClass('ext_assess_tr')){
                        tmp.reachTime = $(this).find('input.hasDatepicker').val();
                        tmp.sourceData = $(this).find('input.sourceData').val();
                        tmp.detailTxt = $(this).find('textarea.detailJobTextarea').val();
                        tmp.assessStad = $(this).find('textarea.assessStad').val();
                        tmp.selfAssess = $(this).find('textarea.selfAssess').val();
                        tmp.leadAssess = $(this).find('textarea.leadAssess').val();
                        j_data['table_data'].push(tmp);
                        tmp = {};
                    }else{
                        tmp.job_name = $(this).find("input[tagname=job_name]").val();
                        tmp.zbyz = $(this).find("input[tagname=zbyz]").val();
                        tmp.qz = $(this).find("input[tagname=job_qz]").val();
                        tmp.selfScore = $(this).find("input[tagname=selfScore]").val();
                        tmp.selfAssess = $(this).find("input[tagname=selfAssess]").val();
                        tmp.leadScore = $(this).find("input[tagname=leadScore]").val();
                    }
                });
                return {commission:c_data,job:j_data};
            },
            s_handler : function(){
                //打分类
                var s_data = {table_data:[]};
                var s_table = $(".attr_form_2[flag=3] table");
                s_data.cash = $(".attr_form_2[flag=3] input[name=attr3_cash]").val();
                s_table.find("tr:visible").each(function(k,v){
                    var tmp = {};
                    tmp.score_name = $(this).find("input[tagname=score_name]").val();
                    tmp.selfScore = $(this).find("input[tagname=selfScore]").val();
                    tmp.selfAssess = $(this).find("input[tagname=selfAssess]").val();
                    tmp.leadScore = $(this).find("input[tagname=leadScore]").val();
                    s_data['table_data'].push(tmp);
                });
                return {score:s_data};
            },

            t_handler: function(){
                //提成类
                var t_data = {table_data:[]};
                var t_table = $(".attr_form_3[flag=4] table");
                t_data.cash = $(".attr_form_3[flag=4] input[name=attr3_cash]").val();
                t_table.find("tr:visible").each(function(k,v){
                    var tmp = {};
                    tmp.tc_name = $(this).find("input[tagname=tc_name]").val();
                    tmp.finishCash = $(this).find("input[tagname=finishCash]").val();
                    tmp.selfAssess = $(this).find("input[tagname=selfAssess]").val();
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
        var itemContainer = jDom.parent().find('.kctjcon:eq(0) .sm_div table.sm_xsmbadd');
        var len = itemContainer.find('tr.tpl_tr').length;
        if( len>0){
            var clonedItemDom = itemContainer.find('tr.tpl_tr');
            var cDom = $.extend(true,{}, clonedItemDom);
            var html  = cDom.html().replace(new RegExp(/(\[@\])/g),len);
            itemContainer.append("<tr class='yellow'>"+html+"</tr>");

        }else if(this.delTrCache[type] !=undefined){
            var cDom = this.delTrCache[type];
            itemContainer.append("<tr class=\"tpl_tr yellow\">"+cDom.html()+"</tr>");
        }
        this.clearItemData(itemContainer.find('tr:last'),type);

        itemContainer.copyExtTr = function(trTpl){
            var timeStaft = new Date().getTime();
            var extTr = trTpl.html().replace(new RegExp(/(-_-)/g),timeStaft).replace('hasDatepicker','');
            itemContainer.append("<tr class='ext_assess_tr'>"+extTr+"</tr>");
            var datepickerInput = $("#time_new_"+type+timeStaft);
            datepickerInput.datepicker(); //日历控件实例化
            var ext_assess_tr = datepickerInput.parents('.ext_assess_tr');
            ext_assess_tr.find('input.hasDatepicker').val('');
            ext_assess_tr.find('.sourceData').val('');
            ext_assess_tr.find('tr textarea').each(function(){
                $(this).val('');
            });
        }
        if(itemContainer.find('tr.ext_assess_tr_tpl').length>0){
            itemContainer.copyExtTr(itemContainer.find('tr.ext_assess_tr_tpl'));
        }else if(this.delExtTrCache[type] !=undefined){
            itemContainer.copyExtTr(this.delExtTrCache[type]);
        }
    },

    /*清空克隆item节点里面的数据*/
    clearItemData:function(itemDom,type){
        switch (type){
            case '1':
                itemDom.find("select[name=indicator_parent] option:eq(0)").attr("checked",true);
                itemDom.find("select[name=indicator_child] option:eq(0)").attr("checked",true);
                itemDom.find("input[tagname=zbyz]").val('');
                itemDom.find("input[tagname=qz]").val('');
                itemDom.find("input[tagname=selfScore]").val('');
                itemDom.find("input[tagname=leadScore]").val('');
                itemDom.find("input[tagname=selfAssess]").val('');
                break;

            case '2':
                itemDom.find("input[tagname=job_name]").val('');
                itemDom.find("input[tagname=job_qz]").val('');
                itemDom.find("input[tagname=selfScore]").val('');
                itemDom.find("input[tagname=leadScore]").val('');
                itemDom.find("input[tagname=selfAssess]").val('');
                break;

            case '3':
                itemDom.find("input[tagname=score_name]").val('');
                itemDom.find("input[tagname=selfScore]").val('');
                itemDom.find("input[tagname=leadScore]").val('');
                itemDom.find("input[tagname=selfAssess]").val('');
                break;

            case '4':
                itemDom.find("input[tagname=tc_name]").val('');
                itemDom.find("input[tagname=finishCash]").val('');
                itemDom.find("input[tagname=selfAssess]").val('');
                break;
        }
        return itemDom;
    },

    delTrCache:{},
    delExtTrCache:{},
    //删除item节点
    delItemDom:function(dBtn,type){
        var find = '';
        if(type==1 || type ==2){
            find = 'td:eq(1) input';
        }else if(type==3){
            find = 'td:eq(0) input';
        }
        if($(dBtn).parents('table.sm_xsmbadd tbody').children('tr:not(.ext_assess_tr)').length==1){
            var name = $(dBtn).parents('tr').find(find).attr('name');
            var reg_new = /^.*_new_.*$/;
            var reg_old = /^.*_old_.*$/;
            if(reg_new.test(name) ||reg_old.test(name)){
                var tmp = name.split('_');
                name = tmp[0]+"_new_[@]";
                $(dBtn).parents('tr').find(find).attr('name',name);
                this.delTrCache[type]=  $.extend(true,{}, $(dBtn).parents('tr'));
            }

        }
        if($(dBtn).parents('tr').next().hasClass('ext_assess_tr')){
            if(this.delExtTrCache[type]==undefined){
                this.delExtTrCache[type] = $(dBtn).parents('table').find('tr.ext_assess_tr_tpl');
            }
            $(dBtn).parents('tr').next().fadeTo('fast',0,function(){
                $(this).remove();
            });
        }
        $(dBtn).parents('tr').fadeTo('fast',0,function(){
            $(this).remove();
        });
    },
    triggerIndicatorSelect:function(jSelectDom){
        var jSelectDom =jSelectDom;
        var indicator_parent =  jSelectDom.find("option:selected").val();
        if(this.delTrCache.indicator_ajax_cache==undefined){
            this.delTrCache.indicator_ajax_cache = {};
        }
        var ajax_cache = this.delTrCache.indicator_ajax_cache;
        ajax_cache.replaceChildSelect = function(jSelectDom,data){
            var opList = "";
            if(indicator_parent==''){
                 opList = "<option value=''>请选择</option>";
            }
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
                data:{m:'unlogin',a:'assessUnlogin',act:'ajaxIndicatorClassify',indicator_parent:indicator_parent},
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
        var url = url;
        var jp = function(){
           location.href = url;
        };
        setTimeout(jp,mis);
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
            if($(this).attr('name')=='indicator_child'){
                if($(this).parents('tr').find('input[tagname=qz]').val()==''){
                    return true;
                }
            }
            if(Assess.prototype.selectChildValid(this)==false){
                status = false;
            }
        });

        if(this.getLeadDirectSetValue()==0){
            status = this.validTrEmpty();

        }
        return status;
    },
    validTrEmpty:function(){
        var attrType = this.getAttrTypeCheckedValue();
        var trStatus = true;
        if(attrType==1){
            if($(".attr_form_1[flag=1] table").find("tr:visible").length==0 && $(".attr_form_1[flag=2] table").find("tr:visible").length==0){
                trStatus = false;
            }
        }else if(attrType==2){
            if($(".attr_form_2[flag=3] table").find("tr:visible").length==0){
                trStatus = false;
            }
        }else if(attrType==3){
            if($(".attr_form_3[flag=4] table").find("tr:visible").length==0){
                trStatus = false;
            }
        }
        if(!trStatus){
            alert('请添加考核项！');
        }

        return trStatus;
    },
    addRpItem:function(){
        var tr = $("#reward_punish_form table tr:hidden");
        var len = $("#reward_punish_form table tr").length;
        var html  = tr.html().replace(new RegExp(/(\[@\])/g),len);
        $("#reward_punish_form table").append("<tr>"+html+"</tr>");
    },
    delRpItem:function(dom){
        $(dom).parents('tr').remove();
    },
    getRpItems:function(){
        var RpItems = [];
        if($("#reward_punish_form").length>0){
            $("#reward_punish_form table tr:visible").each(function(){
                var item = {};
                item.rpType = $(this).find("select[name=rpType]").val();
                item.rpIntro = $(this).find("input[tagname=rpIntro]").val();
                item.unitType = $(this).find("select[name=unitType]").val();
                item.rpUnitValue = $(this).find("input[tagname=rpUnitValue]").val();
                RpItems.push(item);
            });
        }
        return RpItems;
    },
    //用户考核复制
    copyUserAssess:function(base_id,userId){
        var status = $("select[name=status]").val();
        var syspath = $('#syspath').val();
        var openUrl  = syspath+'index.php?m=myassessment&a=waitMeAssess&act=mulCopyCreateAssess';
        openUrl+= "&status="+status+"&base_id="+base_id+"&userId="+userId;
        art.dialog.open(openUrl,{height:'500px',width:'700px',lock: true});
    },

    //plugpload插件
    plugFileList:[],
    pushPlugFile:function(fileInfo){
        this.plugFileList.push(fileInfo);
    },
    getPlugList:function(){
        return this.plugFileList;
    },

    //考核类型校验
    validAssessType:function(){
        if(!$("#attr_type_checkboxes_td input:checked").val()){
            alert('考核类型必填');
            return false;
        }
        return true;
    },
    //滚动到valid未通过的第一个元素
    scrollToErrorElement:function(){
        if($.myValidate.errorList.length!=0){
            var element = $.myValidate.errorList[0].element;
            var winScrollTop = $(window).scrollTop();
            var eleTop = $(element).offset().top;
            if(winScrollTop>0 && winScrollTop>eleTop){
                $('html, body').animate({
                    scrollTop: eleTop-60
                }, 500);
            }
        }
    },
    //获取预计得分信息
    addExpectCalMsg:function(formData){
        var assess_status = $("#hidden_user_assess_status").val();
        if(assess_status==5 || assess_status==4){
            var scoreName = (assess_status==4)?'selfScore':'leadScore';
            var attr_type = parseInt(formData.type);
            var score = 0;
            switch(attr_type){
                case 1:
                            var commsionData = formData.handlerData.commission.table_data;
                            var jobData = formData.handlerData.job.table_data;
                            if(commsionData.length>0){
                                for(var i=0;i<commsionData.length;i++){
                                    score+= commsionData[i][scoreName]*commsionData[i].qz/100;
                                }
                            }
                            if(jobData.length>0){
                                for(var i=0;i<jobData.length;i++){
                                    score+= jobData[i][scoreName]*jobData[i].qz/100;
                                }
                            }

                    break;


                case 2:
                            var scoreData = formData.handlerData.score.table_data;
                            if(scoreData.length>0){
                                for(var i=0;i<scoreData.length;i++){
                                    score+= scoreData[i].cash*scoreData[i][scoreName];
                                }
                            }
                    break;

                case 3:
                            var targetData = formData.handlerData.target.table_data;
                            score = targetData[0].tc_name*targetData[0].finishCash/100;
                    break;
            }
            var retMsg = "预计最终得分：<span style='color: red;'>"+score+"</span> !";
            return retMsg;
        }
    }

};