var $thisCustomerId = ""; // 单据客户
var $thisCustomerName = ""; // 单据客户
var defaultCurrency = '人民币'; // 默认货币

// 业务类型数据
$(function(){
    // 单选客户
    $("#incomeUnitName").yxcombogrid_customer({
        hiddenId : 'incomeUnitId',
        isFocusoutCheck : false,
        height : 300,
        gridOptions : {
            showcheckbox : false,
            event : {
                'row_dblclick' : function(e, row, data) {
                    $("#province").val(data.Prov);
                    $("#contractUnitId").val(data.id);
                    $("#contractUnitName").val(data.Name);
                    $("#incomeUnitType").val(data.TypeOne);
                    $("#areaName").val(data.AreaName);
                    $("#areaId").val(data.AreaId);
                    $("#managerId").val(data.AreaLeaderId);
                    $("#managerName").val(data.AreaLeader);

                    //收件人设置
                    if(data.SellManId != ""){
                        $("#TO_ID").val(data.SellManId);
                        $("#TO_NAME").val(data.SellMan);
                    }else{
                        if(data.Prov != ""){
                            //获取对应负责人
                            $.ajax({
                                type: "POST",
                                url: "?model=system_saleperson_saleperson&action=getPersonByProvince",
                                data: {'province' : data.Prov},
                                async: false,
                                success: function(data){
                                    if(data){
                                        var dataArr = eval("(" + data + ")");
                                        $("#TO_ID").val(dataArr.personId);
                                        $("#TO_NAME").val(dataArr.personName);
                                    }
                                }
                            });
                        }
                    }

                    var sendNamesArr = new Array();
                    var sendUserIdArr = new Array();

                    if($("#sendName").val() != ""){
                        sendNamesArr.push($("#sendName").val());
                        sendUserIdArr.push($("#sendUserId").val());
                    }

                    if(data.AreaLeader != ""){
                        sendNamesArr.push(data.AreaLeader);
                        sendUserIdArr.push(data.AreaLeaderId);
                    }
                    $("#ADDIDS").val(sendUserIdArr.toString());
                    $("#ADDNAMES").val(sendNamesArr.toString());

                    //获取分配从表数据
                    var objGrid = $("#allotTable"); // 缓存从表对象
                    var objArr = objGrid.yxeditgrid('getCmpByCol','objType');
                    objArr.each(function(){
                        var rowNum = $(this).data('rowNum');
                        objGrid.yxeditgrid('setRowColValue',rowNum,'rObjCode','');
                        objGrid.yxeditgrid('setRowColValue',rowNum,'objId','');
                        objGrid.yxeditgrid('setRowColValue',rowNum,'objCode','');
                        reloadCombo(this.value,rowNum);
                    });
                }
            }
        }
    });

    //归属公司
    $("#businessBelongName").yxcombogrid_branch({
        hiddenId : 'businessBelong',
        height : 250,
        isFocusoutCheck : false,
        gridOptions : {
            showcheckbox : false
        }
    });

    // 渲染币别
    var currencyObj = $("#currency");
    if(currencyObj.length > 0){
        // 金额币别
        currencyObj.yxcombogrid_currency({
            hiddenId : 'currency',
            valueCol : 'currencyCode',
            isFocusoutCheck : false,
            gridOptions : {
                showcheckbox : false,
                event : {
                    'row_dblclick' : function(e, row, data) {
                        $("#rate").val(data.rate);
                        if(data.Currency != '人民币'){
                            setMoney('incomeMoney',0,2);
                            setMoney('incomeCurrency',0,2);
                            $("#currencyInfo").show();
                            $("#currencyShow").text(data.Currency);
                            $("#incomeMoney_v").removeClass('txt').addClass('readOnlyTxtNormal');
                        }else{
                            setMoney('incomeMoney',$("#incomeCurrency").val(),2);
                            $("#currencyInfo").hide();
                            $("#incomeMoney_v").addClass('txt').removeClass('readOnlyTxtNormal');
                        }
                        // 如果变更了币别，则革新退款从表
                        initAllot(data.Currency);
                    }
                }
            }
        });
    }

    // 获取货币
    var currency = $("#currency").val();

    // 到款分配
    initAllot(currency);
});

// 到款分配明细
function initAllot(currency){
    var objTypeOptions = getObjType('KPRK');
    if($("#allotTable").html() != "") $("#allotTable").yxeditgrid('remove'); // 如果分配表已经存在，则删除再渲染
    $("#allotTable").yxeditgrid({
        objName : 'income[incomeAllots]',
        title : '退款分配',
        tableClass : 'form_in_table',
        colModel : [{
            display : 'id',
            name : 'id',
            type : 'hidden'
        }, {
            display : '源单类型',
            name : 'objType',
            type : 'select',
            options : objTypeOptions,
            event : {
                change : function(){
                    reloadCombo(this.value,$(this).data('rowNum'));
                }
            }
        }, {
            display : '源单id',
            name : 'objId',
            type : 'hidden'
        }, {
            display : '源单编号',
            name : 'objCode',
            tclass : 'txt',
            readonly : true,
            process : function($input) {
                var rowNum = $input.data("rowNum");
                var g = $input.data("grid");
                var thisType = g.getCmpByRowAndCol(rowNum,'objType').val();
                reloadComboClear(thisType,rowNum);
            }
        }, {
            display : '分配金额',
            name : 'money',
            type : 'money',
            readonly : currency == defaultCurrency ? false : true,
            tclass : currency == defaultCurrency ? 'txtmiddle' : 'readOnlyTxt',
            event : {
                blur : function(){
                    if(currency == defaultCurrency){
                        countRow($(this).data('rowNum'),currency);
                        countAll();
                    }
                }
            }
        }, {
            display : '分配金额('+ currency +')',
            name : 'moneyCurrency',
            type : currency == defaultCurrency ? 'hidden' : 'money',
            event : {
                blur : function(){
                    if(currency != defaultCurrency){
                        countRow($(this).data('rowNum'),currency);
                        countAll();
                    }
                }
            }
        }, {
            display : '业务编号',
            name : 'rObjCode',
            tclass : 'readOnlyTxt',
            readonly : true
        }, {
            display : '分配日期',
            name : 'allotDate',
            tclass : 'txtmiddle Wdate',
            type : 'date',
            value : $("#incomeDate").val()
        }]
    });
}

// 行处理
function countRow(rowNum,currency){
    var objGrid = $("#allotTable"); // 缓存从表对象
    var rate = $("#rate").val();
    if(currency == defaultCurrency){
        var money = objGrid.yxeditgrid('getCmpByRowAndCol',rowNum,'money',true).val();
        var moneyCurrency = accDiv(money,rate,2);
        objGrid.yxeditgrid("setRowColValue",rowNum,"moneyCurrency",moneyCurrency,true);
    }else{
        var moneyCurrency = objGrid.yxeditgrid('getCmpByRowAndCol',rowNum,'moneyCurrency',true).val();
        var money = accMul(moneyCurrency,rate,2);
        objGrid.yxeditgrid("setRowColValue",rowNum,"money",money,true);
    }
}

// 金额合计
function countAll(){
    //获取分配从表数据
    var objGrid = $("#allotTable"); // 缓存从表对象
    var objArr = objGrid.yxeditgrid('getCmpByCol','objType');
    var allAmount = 0; // 分配金额
    var allCurrency = 0; // 分配金额(对应币别)
    objArr.each(function(){
        var money = objGrid.yxeditgrid('getCmpByRowAndCol',$(this).data('rowNum'),'money',true).val();
        if(!isNaN(money)){
            allAmount = accAdd(money,allAmount,2);
            var moneyCurrency = objGrid.yxeditgrid('getCmpByRowAndCol',$(this).data('rowNum'),'moneyCurrency',true).val();
            allCurrency = accAdd(allCurrency,moneyCurrency,2);
        }
    });
    var allotAble = accSub($('#incomeMoney').val(),allAmount,2);
    $('#allotAble').val(allotAble);
    var allotCurrency = accSub($('#incomeCurrency').val(),allCurrency);
    $('#allotCurrency').val(allotCurrency);
}