var show_page = function () {
    $("#grid").yxgrid("reload");
};
$(function () {
    // 列表初始化
    $("#grid").yxgrid({
        model: 'bi_deptFee_assetDepr',
        title: '部门设备折旧',
        //列信息
        colModel: [
            {
                display: 'id',
                name: 'id',
                sortable: true,
                hide: true
            },
            {
                name: 'business',
                display: '事业部',
                width: 120,
                sortable: true
            },
            {
                name: 'thisYear',
                display: '年月',
                sortable: false,
                process: function(v, row) {
                    return v + "-" + row.thisMonth;
                }
            },
            {
                name: 'deprMoney',
                display: '财务折旧金额',
                sortable: true,
                process: function(v) {
                    return moneyFormat2(v);
                }
            },
            {
                name: 'officeNames',
                display: '分摊区域',
                width: 250
            },
            {
                name: 'information',
                display: '分摊明细',
                width: 400
            }
        ],
        toAddConfig: {
            formHeight: 500,
            formWidth: 800
        },
        toEditConfig: {
            action: 'toEdit',
            formHeight: 500,
            formWidth: 800
        },
        toViewConfig: {
            action: 'toView',
            formHeight: 500,
            formWidth: 800
        },
        searchitems: [
            {
                display: "事业部",
                name: 'businessSearch'
            }
        ]
    });
});