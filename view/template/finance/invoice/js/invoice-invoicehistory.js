var show_page = function () {
    $("#invoiceGrid").yxgrid("reload");
};

$(function () {
    $("#invoiceGrid").yxgrid({
        model: 'finance_invoice_invoice',
        title: '��Ʊ��ʷ',
        param: { "objId": $('#objId').val(), "objType": $("#objType").val() },
        isToolBar: true,
        isAddAction: false,
        isEditAction: false,
        isDelAction: false,
        showcheckbox: true,
        colModel: [
            {
                display: 'id',
                name: 'id',
                sortable: true,
                hide: true
            },
            {
                display: '���뵥��',
                name: 'applyNo',
                hide: true
            },
            {
                display: '��Ʊ��',
                name: 'invoiceNo',
                sortable: true
            },
            {
                display: '�������',
                name: 'objCode',
                width: 130
            },
            {
                display: '��������',
                name: 'objType',
                datacode: 'KPRK',
                width: 80
            },
            {
                display: '��λ����',
                name: 'invoiceUnitName',
                width: 130
            },
            {
                display: '��Ʊ����',
                name: 'invoiceTime',
                sortable: true,
                width: 80
            },
            {
                display: '��Ʊ����',
                name: 'invoiceTypeName',
                width: 80
            },
            {
                display: '������',
                name: 'softMoney',
                process: function (v) {
                    return moneyFormat2(v);
                },
                width: 80
            },
            {
                display: 'Ӳ�����',
                name: 'hardMoney',
                process: function (v) {
                    return moneyFormat2(v);
                },
                width: 80
            },
            {
                display: 'ά�޽��',
                name: 'repairMoney',
                process: function (v) {
                    return moneyFormat2(v);
                },
                width: 80
            },
            {
                display: '������',
                name: 'serviceMoney',
                process: function (v) {
                    return moneyFormat2(v);
                },
                width: 80
            },
            {
                display: '�豸���޽��',
                name: 'equRentalMoney',
                process: function (v) {
                    return moneyFormat2(v);
                },
                width: 80
            },
            {
                display: '�������޽��',
                name: 'spaceRentalMoney',
                process: function (v) {
                    return moneyFormat2(v);
                },
                width: 80
            },
            {
                display: '�ܽ��',
                name: 'invoiceMoney',
                process: function (v) {
                    return moneyFormat2(v);
                },
                width: 80
            },
            {
                display: '��Ʊ��',
                name: 'createName'
            }
        ],
        toViewConfig: {
            text: '�鿴��Ʊ��¼',
            formWidth: 800,
            formHeight: 500
        },
        searchitems: [
            {
                display: '��Ʊ��',
                name: 'invoiceNo'
            }
        ],
        sortorder: 'DESC'
    });
});