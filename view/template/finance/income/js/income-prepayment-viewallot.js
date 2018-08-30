var defaultCurrency = '人民币'; // 默认货币
$(function(){
    // 获取货币
    var currency = $("#currency").val();

    // 到款分配
    initAllot(currency);

    // 货币显示
    if($("#currency").val() != defaultCurrency){
        $("#currencyInfo").show();
    }
});

// 到款分配明细
function initAllot(currency){
    $("#allotTable").yxeditgrid({
        url : '?model=finance_income_incomeAllot&action=listJson',
        objName : 'income[incomeAllots]',
        title : '预收款分配',
        param : { 'incomeId' : $("#id").val()},
        tableClass : 'form_in_table',
        type : 'view',
        event : {
            'reloadData' : function(e, g, data){
                if(!data){
                    $("#allotTable").find('tbody').append('<tr class="tr_odd"><td colspan="6">-- 暂无分配内容 --</td></tr>');
                }
            }
        },
        colModel : [{
            display : '源单类型',
            name : 'objType',
            datacode : 'KPRK'
        }, {
            display : '源单编号',
            name : 'objCode'
        }, {
            display : '分配金额',
            name : 'money',
            process : function(v){
                return moneyFormat2(v);
            }
        }, {
            display : '分配金额('+ currency +')',
            name : 'moneyCurrency',
            type : currency == defaultCurrency ? 'hidden' : 'money',
            process : function(v){
                return moneyFormat2(v);
            }
        }, {
            display : '业务编号',
            name : 'rObjCode'
        }, {
            display : '分配日期',
            name : 'allotDate'
        }]
    });
}