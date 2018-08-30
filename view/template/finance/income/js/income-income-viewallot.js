var defaultCurrency = '人民币'; // 默认货币
$(function () {
    // 获取货币
    var currency = $("#currency").val();

    // 到款分配
    initAllot(currency);

    //回款核销
    initIncomeCheck();

    // 货币显示
    if ($("#currency").val() != defaultCurrency) {
        $("#currencyInfo").show();
    }
});

// 到款分配明细
function initAllot(currency) {
    $("#allotTable").yxeditgrid({
        url: '?model=finance_income_incomeAllot&action=listJson',
        objName: 'income[incomeAllots]',
        title: '到款分配',
        param: {'incomeId': $("#id").val()},
        tableClass: 'form_in_table',
        type: 'view',
        event: {
            reloadData: function (e, g, data) {
                if (!data) {
                    $("#allotTable").find('tbody').append('<tr class="tr_odd"><td colspan="6">-- 暂无分配内容 --</td></tr>');
                }
            }
        },
        colModel: [{
            display: '源单类型',
            name: 'objType',
            datacode: 'KPRK'
        }, {
            display: '源单编号',
            name: 'objCode'
        }, {
            display: '销售区域',
            name: 'areaName',
            readonly: true,
            width: 100
        }, {
            display: '分配金额',
            name: 'money',
            process: function (v) {
                return moneyFormat2(v);
            }
        }, {
            display: '分配金额(' + currency + ')',
            name: 'moneyCurrency',
            type: currency == defaultCurrency ? 'hidden' : 'money',
            process: function (v) {
                return moneyFormat2(v);
            }
        }, {
            display: '业务编号',
            name: 'rObjCode'
        }, {
            display: '分配日期',
            name: 'allotDate'
        }]
    });
}

//渲染到款核销从表
function initIncomeCheck() {
    var objGrid = $("#checkTable");
    objGrid.yxeditgrid({
        url: '?model=finance_income_incomecheck&action=listJson',
        objName: 'income[incomeCheck]',
        title: '回款核销',
        param: {incomeId: $("#id").val(), incomeType: 0},
        tableClass: 'form_in_table',
        type: 'view',
        event: {
            'reloadData': function (e, g, data) {
                if (!data) {
                    objGrid.find("tbody").html("<tr><td colspan='6'>没有详细信息</td></tr>");
                }
            }
        },
        colModel: [{
            display: 'id',
            name: 'id',
            type: 'hidden'
        }, {
            display: '合同id',
            name: 'contractId',
            type: 'hidden'
        }, {
            display: '合同名称',
            name: 'contractName',
            type: 'hidden'
        }, {
            display: '合同编号',
            name: 'contractCode',
            tclass: 'readOnlyTxtNormal',
            readonly: true
        }, {
            display: '付款条件id',
            name: 'payConId',
            type: 'hidden'
        }, {
            display: '付款条件',
            name: 'payConName',
            tclass: 'readOnlyTxtMiddle',
            readonly: true
        }, {
            display: '本次核销金额',
            name: 'checkMoney',
            tclass: 'txtmiddle',
            type: 'money',
            process: function (v) {
                return moneyFormat2(v);
            }
        }, {
            display: '核销日期',
            name: 'checkDate',
            tclass: 'txtmiddle Wdate',
            type: 'date'
        }, {
            display: '备注',
            name: 'remark',
            tclass: 'txt'
        }]
    });
}