var show_page = function () {
    $("#grid").yxgrid("reload");
};
$(function () {
    // �б��ʼ��
    $("#grid").yxgrid({
        model: 'bi_deptFee_assetDepr',
        title: '�����豸�۾�',
        //����Ϣ
        colModel: [
            {
                display: 'id',
                name: 'id',
                sortable: true,
                hide: true
            },
            {
                name: 'business',
                display: '��ҵ��',
                width: 120,
                sortable: true
            },
            {
                name: 'thisYear',
                display: '����',
                sortable: false,
                process: function(v, row) {
                    return v + "-" + row.thisMonth;
                }
            },
            {
                name: 'deprMoney',
                display: '�����۾ɽ��',
                sortable: true,
                process: function(v) {
                    return moneyFormat2(v);
                }
            },
            {
                name: 'officeNames',
                display: '��̯����',
                width: 250
            },
            {
                name: 'information',
                display: '��̯��ϸ',
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
                display: "��ҵ��",
                name: 'businessSearch'
            }
        ]
    });
});