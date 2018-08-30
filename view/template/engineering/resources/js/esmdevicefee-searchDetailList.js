$(function () {
    $("#grid").yxeditgrid({
        url: '?model=engineering_resources_esmdevicefee&action=searchDetailList',
        type: 'view',
        param: {
            projectId: $("#projectId").val()
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
                name: 'fee',
                display: '决算',
                align: 'right',
                process: function (v) {
                    if (v == 0 || v == "") {
                        return '--';
                    } else {
                        return moneyFormat2(v);
                    }
                },
                width: 70
            },
            {
                name: '',
                display: ''
            }
        ]
    });
});