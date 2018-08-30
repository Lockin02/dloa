var $thisCustomerId = ""; // 单据客户
var $thisCustomerName = ""; // 单据客户
var defaultCurrency = '人民币'; // 默认货币

// 业务类型数据
$(function(){
    // 获取货币
    var currency = $("#currency").val();

    // 到款分配
    initAllot(currency);

    // 初始化设置
    if($("#id").length > 0){
        if(currency != '人民币'){
            $("#currencyInfo").show();
            $("#currencyShow").text(currency);
        }else{
            $("#currencyInfo").hide();
        }
    }
});

// 到款分配明细
function initAllot(currency){
    var objTypeOptions = getObjType('KPRK');
    $("#allotTable").yxeditgrid({
        url : '?model=finance_income_incomeAllot&action=listJson',
        objName : 'income[incomeAllots]',
        title : '到款分配',
        param : { 'incomeId' : $("#id").val()},
        isAddAndDel : false,
        tableClass : 'form_in_table',
        event : {
            'reloadData' : function(e, g, data){
                if(data){
                    var objKeyArr = {};
                    for(i in objTypeOptions){
                        if(objTypeOptions[i].value){
                            objKeyArr[objTypeOptions[i].value] = objTypeOptions[i].name;
                        }
                    }
                    for(var i = 0; i < data.length; i++){
                        g.setRowColValue(i,'objTypeView',objKeyArr[data[i].objType]);
                    }
                }
            }
        },
        colModel : [{
            display : '源单类型',
            tclass : 'readOnlyTxt',
            name : 'objTypeView'
        }, {
            display : '源单类型',
            name : 'objType',
            type : 'hidden'
        }, {
            display : '源单id',
            name : 'objId',
            type : 'hidden'
        }, {
            display : '源单编号',
            name : 'objCode',
            tclass : 'txt',
            tclass : 'readOnlyTxtNormal',
            readonly : true
        }, {
            display : '分配金额',
            name : 'money',
            type : 'money',
            readonly : currency == defaultCurrency ? false : true,
            tclass : 'readOnlyTxt',
            readonly : true
        }, {
            display : '分配金额('+ currency +')',
            name : 'moneyCurrency',
            type : currency == defaultCurrency ? 'hidden' : 'money',
            tclass : 'readOnlyTxt',
            readonly : true
        }, {
            display : '业务编号',
            name : 'rObjCode',
            tclass : 'readOnlyTxt',
            readonly : true
        }, {
            display : '分配日期',
            name : 'allotDate',
            tclass : 'readOnlyTxt',
            readonly : true
        }]
    });
}