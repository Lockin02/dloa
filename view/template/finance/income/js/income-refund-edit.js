var $thisCustomerId = ""; // 单据客户
var $thisCustomerName = ""; // 单据客户
var defaultCurrency = '人民币'; // 默认货币

// 业务类型数据
$(function(){
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
    var currency = currencyObj.val();

    // 到款分配
    initAllot(currency);
});

// 到款分配明细
function initAllot(currency){
    var objTypeOptions = getObjType('KPRK');
    $("#allotTable").yxeditgrid({
        url : '?model=finance_income_incomeAllot&action=listJson',
        objName : 'income[incomeAllots]',
        title : '到款分配',
        param : { 'incomeId' : $("#id").val()},
        tableClass : 'form_in_table',
        event : {
            'reloadData' : function(e, g, data){
                if(!data){
                    g.addRow(0);
                    g.setRowColValue(0,'money',$("#incomeMoney").val(),true);
                    g.setRowColValue(0,'moneyCurrency',$("#incomeCurrency").val(),true);
                }
                // 统一计算一次
                countAll();
            }
        },
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
            type : 'date'
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