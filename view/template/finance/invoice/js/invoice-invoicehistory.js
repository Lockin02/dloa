var show_page = function () {
    $("#invoiceGrid").yxgrid("reload");
};

$(function () {
    $("#invoiceGrid").yxgrid({
        model: 'finance_invoice_invoice',
        title: '开票历史',
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
                display: '申请单号',
                name: 'applyNo',
                hide: true
            },
            {
                display: '发票号',
                name: 'invoiceNo',
                sortable: true
            },
            {
                display: '关联编号',
                name: 'objCode',
                width: 130
            },
            {
                display: '关联类型',
                name: 'objType',
                datacode: 'KPRK',
                width: 80
            },
            {
                display: '单位名称',
                name: 'invoiceUnitName',
                width: 130
            },
            {
                display: '开票日期',
                name: 'invoiceTime',
                sortable: true,
                width: 80
            },
            {
                display: '开票类型',
                name: 'invoiceTypeName',
                width: 80
            },
            {
                display: '软件金额',
                name: 'softMoney',
                process: function (v) {
                    return moneyFormat2(v);
                },
                width: 80
            },
            {
                display: '硬件金额',
                name: 'hardMoney',
                process: function (v) {
                    return moneyFormat2(v);
                },
                width: 80
            },
            {
                display: '维修金额',
                name: 'repairMoney',
                process: function (v) {
                    return moneyFormat2(v);
                },
                width: 80
            },
            {
                display: '服务金额',
                name: 'serviceMoney',
                process: function (v) {
                    return moneyFormat2(v);
                },
                width: 80
            },
            {
                display: '设备租赁金额',
                name: 'equRentalMoney',
                process: function (v) {
                    return moneyFormat2(v);
                },
                width: 80
            },
            {
                display: '场地租赁金额',
                name: 'spaceRentalMoney',
                process: function (v) {
                    return moneyFormat2(v);
                },
                width: 80
            },
            {
                display: '总金额',
                name: 'invoiceMoney',
                process: function (v) {
                    return moneyFormat2(v);
                },
                width: 80
            },
            {
                display: '开票人',
                name: 'createName'
            }
        ],
        toViewConfig: {
            text: '查看开票记录',
            formWidth: 800,
            formHeight: 500
        },
        searchitems: [
            {
                display: '发票号',
                name: 'invoiceNo'
            }
        ],
        sortorder: 'DESC'
    });
});