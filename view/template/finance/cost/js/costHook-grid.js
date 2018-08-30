var show_page = function () {
    $("#costHookGrid").yxgrid("reload");
};

$(function () {
    $("#costHookGrid").yxgrid({
        model: 'finance_cost_costHook',
        param: {idArr: $("#ids").val()},
        title: '费用分摊核销记录',
        isAddAction: false,
        isEditAction: false,
        isDelAction: false,
        isOpButton: false,
        //列信息
        colModel: [{
            display: '勾稽序号',
            name: 'id',
            sortable: true,
            width: 70
        }, {
            name: 'hookPeriod',
            display: '勾稽期间',
            sortable: true,
            width: 70
        }, {
            name: 'hookYear',
            display: '勾稽年份',
            sortable: true,
            width: 70,
            hide: true
        }, {
            name: 'hookMonth',
            display: '勾稽月份',
            sortable: true,
            width: 70,
            hide: true
        }, {
            name: 'createId',
            display: '勾稽人',
            sortable: true,
            hide: true
        }, {
            name: 'createName',
            display: '勾稽人名',
            width: 80,
            sortable: true
        }, {
            name: 'createTime',
            display: '勾稽时间',
            sortable: true,
            width: 130
        }, {
            name: 'hookCode',
            display: '勾稽单号',
            sortable: true,
            width: 150
        }, {
            name: 'hookMoney',
            display: '勾稽金额',
            sortable: true,
            align: 'right',
            process: function (v) {
                return moneyFormat2(v);
            }
        }],
        searchitems: [{
            display: "勾稽单号",
            name: 'dHookCode'
        }]
    });
});