$(function () {
    var projectId = $("#projectId").val();
    var budgetName = $("#budgetName").val();
    var budgetType = $("#budgetType").val();
    $("#esmbudgetGrid").yxeditgrid({
        url: '?model=engineering_budget_esmbudget&action=feeDetail',
        type: 'view',
        param: {
            projectId: projectId,
            budgetName: budgetName,
            budgetType: budgetType
        },
        //列信息
        colModel: [
            {
                display: 'id',
                name: 'id',
                type: 'hidden'
            },
            {
                name: 'budgetType',
                display: '决算来源',
                width: 80
            },
            {
                name: 'yearMonth',
                display: '年月',
                width: 80
            },
            {
                name: 'actFee',
                display: '决算',
                align: 'right',
                process: function (v, row) {
                    if (v == 0 || v == "") {
                        return '--';
                    } else {
                        var projectCode = $("#projectCode").val();
                        var contractCode = $("#contractCode").val();
                        switch (row.budgetType) {
                            case "报销成本" :
                                // 获取费用类型对应的id
                                return '<a href="javascript:void(0);" onclick="viewExpense(\'' + projectCode + '\', \'' + budgetName + '\')">' + moneyFormat2(v) + "</a>";
                            case "支付决算" :
                                var url = "?model=finance_cost_costshare&action=toHistoryForProject&projectCode=" +
                                    projectCode + "&contractCode=" + contractCode;
                                return '<a href="javascript:void(0);" onclick="window.open(\'' + url + '\')">' + moneyFormat2(v) + "</a>";
                            default:
                                return moneyFormat2(v);
                        }
                    }
                },
                width: 70
            },
            {
                name: 'createName',
                display: '导入人',
                width: 90
            },
            {
                name: 'createTime',
                display: '导入时间',
                width: 130
            },
            {
                name: 'remark',
                display: '备注',
                width: 130
            },
            {
                name: '',
                display: ''
            }
        ]
    });
});

var viewExpense = function(projectCode, costTypeName) {
    var costTypeId = '';
    $.ajax({
        url: '?model=finance_expense_costtype&action=nameToId',
        data: {names: costTypeName},
        type: 'POST',
        async: false,
        success: function(msg) {
            costTypeId = msg;
        }
    });
    var url = "general/costmanage/statistics/project/index_type.php?seaPro=" + projectCode + "&checkType=now" +
        "&seaType=" + costTypeId + "&submitYes=1";
    window.open(url);
};