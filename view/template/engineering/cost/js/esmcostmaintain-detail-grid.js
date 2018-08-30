$(function () {
    var projectId = $("#projectId").val();
    var parentCostType = $("#parentName").val();
    var costType = $("#budgetName").val();

    $("#esmcostmaintainGrid").yxeditgrid({
        url: '?model=engineering_cost_esmcostmaintain&action=searhDetailJson',
        type: 'view',
        param: {
            "projectId": projectId,
            "parentCostType": parentCostType,
            "costType": costType
        },
        //列信息
        colModel: [{
            display: 'id',
            name: 'id',
            type: 'hidden'
        }, {
            name: 'category',
            display: '费用来源',
            process: function (v) {
                if (v == "") {
                    return "费用维护";
                } else {
                    return v;
                }
            },
            width: 100
        }, {
            name: 'parentCostType',
            display: '费用大类',
            align: 'left',
            type: 'hidden'
        }, {
            name: 'costType',
            display: '费用名目',
            align: 'left',
            width: 120
        }, {
            name: 'projectCode',
            display: '项目编号',
            type: 'hidden'
        }, {
            name: 'projectName',
            display: '项目名称',
            type: 'hidden'
        }, {
            name: 'month',
            display: '月份',
            width: 100
        }, {
            name: 'budget',
            display: '预算',
            align: 'right',
            process: function (v) {
                return moneyFormat2(v);
            },
            width: 100
        }, {
            name: 'fee',
            display: '决算',
            align: 'right',
            process: function (v) {
                return moneyFormat2(v);
            },
            width: 100
        }, {
            name: 'remark',
            display: '备注',
            align: 'left',
            process: function (v, row) {
                if (row.id == 'noId' || row.ExaStatus != 0)
                    return v;
                if (row.isDel == 1)
                    return v + "<span class='red'>[删除待审批]</span>";
                if (row.fee * 1 == 0)
                    return v + "<span class='red'>[新增待审批]</span>";
                if (row.fee * 1 != 0)
                    return v + "<span class='red'>[更新待审批]</span>";
            },
            width: 250
        }, {
            name: '',
            display: ''
        }]
    });
});