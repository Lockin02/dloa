var show_page = function (page) {
    $("#invoiceDetail").yxgrid("reload");
};
$(function () {
    $("#invoiceDetail").yxgrid({
        // 如果传入url，则用传入的url，否则使用model及action自动组装
        model: 'finance_invoice_invoice',
        param: {'objId': $('#objId').val(), 'objType': $('#objType').val()},
        action: 'objPageJson',
        customCode: 'invoiceDetailOrder',
        isViewAction: false,
        isEditAction: false,
        isDelAction: false,
        isAddAction: false,
        isToolBar: false,
        showcheckbox: false,
        menusEx: [
            {
                text: '查看开票申请',
                icon: 'view',
                showMenuFn: function (row) {
                    if (row.applyId != "") {
                        return true;
                    }
                    return false;
                },
                action: function (row) {
                    showThickboxWin('?model=finance_invoiceapply_invoiceapply&action=init'
                        + '&id=' + row.applyId
                        + "&skey=" + row['skey_1']
                        + '&perm=view&placeValuesBefore&TB_iframe=true&modal=false&height=500&width=900');
                }
            },
            {
                text: '查看开票记录',
                icon: 'view',
                action: function (row) {
                    showThickboxWin('?model=finance_invoice_invoice&action=init&perm=view&id='
                        + row.id
                        + "&skey=" + row['skey_']
                        + "&placeValuesBefore&TB_iframe=true&modal=false&width=900&height=500");
                }
            },
            {
                name: 'view',
                text: "邮寄信息",
                icon: 'view',
                showMenuFn: function (row) {
                    if (row.isMail == 1) {
                        return true;
                    }
                    return false;
                },
                action: function (row) {
                    showThickboxWin("?model=mail_mailinfo&action=viewByDoc&docId="
                        + row.id
                        + '&docType=YJSQDLX-FPYJ'
                        + "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=900");
                }
            }],
        // 表单
        colModel: [{
            display: 'id',
            name: 'id',
            sortable: true,
            hide: true
        }, {
            display: '发票号码',
            name: 'invoiceNo',
            sortable: true,
            process: function (v, row) {
                if (row.isRed == 0) {
                    return "<a href='#' onclick='showThickboxWin(\"?model=finance_invoice_invoice&action=init&perm=view&id=" + row.id + '&skey=' + row.skey_ + "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=900\")'>" + v + "</a>";
                } else {
                    return "<a href='#' style='color:red' onclick='showThickboxWin(\"?model=finance_invoice_invoice&action=init&perm=view&id=" + row.id + '&skey=' + row.skey_ + "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=900\")'>" + v + "</a>";
                }
            }
        }, {
            display: '开票申请id',
            name: 'applyId',
            hide: true

        }, {
            display: '开票申请单号',
            name: 'applyNo',
            sortable: true,
            width: 140,
            process: function (v, row) {
                return "<a href='#' onclick='showThickboxWin(\"?model=finance_invoiceapply_invoiceapply&action=init&perm=view&id=" + row.applyId + '&skey=' + row.skey_1 + "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=900\")'>" + v + "</a>";
            }
        }, {
            display: '开票类型',
            name: 'invoiceTypeName',
            sortable: true,
            width: 90
        }, {
            display: '总金额',
            name: 'invoiceMoney',
            sortable: true,
            width: 90,
            process: function (v, row) {
                if (row.isRed == 0) {
                    return moneyFormat2(v);
                } else {
                    return '-' + moneyFormat2(v);
                }
            }
        }, {
            display: '软件金额',
            name: 'softMoney',
            sortable: true,
            width: 90,
            process: function (v, row) {
                if (row.isRed == 0) {
                    return moneyFormat2(v);
                } else {
                    return '-' + moneyFormat2(v);
                }
            }
        }, {
            display: '硬件金额',
            name: 'hardMoney',
            sortable: true,
            width: 90,
            process: function (v, row) {
                if (row.isRed == 0) {
                    return moneyFormat2(v);
                } else {
                    return '-' + moneyFormat2(v);
                }
            }
        }, {
            display: '服务金额',
            name: 'serviceMoney',
            sortable: true,
            width: 90,
            process: function (v, row) {
                if (row.isRed == 0) {
                    return moneyFormat2(v);
                } else {
                    return '-' + moneyFormat2(v);
                }
            }

        }, {
            display: '维修金额',
            name: 'repairMoney',
            sortable: true,
            width: 90,
            process: function (v, row) {
                if (row.isRed == 0) {
                    return moneyFormat2(v);
                } else {
                    return '-' + moneyFormat2(v);
                }
            }
        }, {
            display: '设备租赁金额',
            name: 'equRentalMoney',
            sortable: true,
            width: 90,
            process: function (v, row) {
                if (row.isRed == 0) {
                    return moneyFormat2(v);
                } else {
                    return '-' + moneyFormat2(v);
                }
            }

        }, {
            display: '场地租赁金额',
            name: 'spaceRentalMoney',
            sortable: true,
            width: 90,
            process: function (v, row) {
                if (row.isRed == 0) {
                    return moneyFormat2(v);
                } else {
                    return '-' + moneyFormat2(v);
                }
            }
        }, {
            display : '其他金额',
            name : 'otherMoney',
            sortable : true,
            process : function(v, row){
                if (row.isRed == 0) {
                    return moneyFormat2(v);
                } else {
                    return '-' + moneyFormat2(v);
                }
            }
        },  {
            display : '代收电费总金额',
            name : 'dsEnergyCharge',
            sortable : true,
            process : function(v, row){
                if (row.isRed == 0) {
                    return moneyFormat2(v);
                } else {
                    return '-' + moneyFormat2(v);
                }
            }
        },  {
            display : '代收水费总金额',
            name : 'dsWaterRateMoney',
            sortable : true,
            process : function(v, row){
                if (row.isRed == 0) {
                    return moneyFormat2(v);
                } else {
                    return '-' + moneyFormat2(v);
                }
            }
        },  {
            display : '房屋出租总金额',
            name : 'houseRentalFee',
            sortable : true,
            process : function(v, row){
                if (row.isRed == 0) {
                    return moneyFormat2(v);
                } else {
                    return '-' + moneyFormat2(v);
                }
            }
        },  {
            display : '安装服务总金额',
            name : 'installationCost',
            sortable : true,
            process : function(v, row){
                if (row.isRed == 0) {
                    return moneyFormat2(v);
                } else {
                    return '-' + moneyFormat2(v);
                }
            }
        }, {
            display: '开票人',
            name: 'createName',
            sortable: true,
            width: 80
        }, {
            display: '开票日期',
            name: 'invoiceTime',
            sortable: true,
            width: 80
        }, {
            display: '录入日期',
            name: 'createTime',
            sortable: true,
            width: 130
        }, {
            display: '最后更新日期',
            name: 'updateTime',
            sortable: true,
            hide: true,
            width: 80
        }, {
            display: '已邮寄',
            name: 'isMail',
            sortable: true,
            process: function (v) {
                if (v == 1) {
                    return '是';
                } else {
                    return '否';
                }
            }
        }],
        /**
         * 快速搜索
         */
        searchitems: [{
            display: '发票号',
            name: 'invoiceNo'
        }],
        sortorder: "ASC",
        title: '开票记录'
    });
});