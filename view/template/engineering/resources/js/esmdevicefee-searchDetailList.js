$(function () {
    $("#grid").yxeditgrid({
        url: '?model=engineering_resources_esmdevicefee&action=searchDetailList',
        type: 'view',
        param: {
            projectId: $("#projectId").val()
        },
        //����Ϣ
        colModel: [
            {
                display: 'id',
                name: 'id',
                type: 'hidden'
            },
            {
                name: 'budgetType',
                display: '������Դ',
                width: 80
            },
            {
                name: 'yearMonth',
                display: '����',
                width: 80
            },
            {
                name: 'fee',
                display: '����',
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