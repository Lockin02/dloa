var show_page = function (page) {
    $("#invoiceDetail").yxgrid("reload");
};
$(function () {
    $("#invoiceDetail").yxgrid({
        // �������url�����ô����url������ʹ��model��action�Զ���װ
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
                text: '�鿴��Ʊ����',
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
                text: '�鿴��Ʊ��¼',
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
                text: "�ʼ���Ϣ",
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
        // ��
        colModel: [{
            display: 'id',
            name: 'id',
            sortable: true,
            hide: true
        }, {
            display: '��Ʊ����',
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
            display: '��Ʊ����id',
            name: 'applyId',
            hide: true

        }, {
            display: '��Ʊ���뵥��',
            name: 'applyNo',
            sortable: true,
            width: 140,
            process: function (v, row) {
                return "<a href='#' onclick='showThickboxWin(\"?model=finance_invoiceapply_invoiceapply&action=init&perm=view&id=" + row.applyId + '&skey=' + row.skey_1 + "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=900\")'>" + v + "</a>";
            }
        }, {
            display: '��Ʊ����',
            name: 'invoiceTypeName',
            sortable: true,
            width: 90
        }, {
            display: '�ܽ��',
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
            display: '������',
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
            display: 'Ӳ�����',
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
            display: '������',
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
            display: 'ά�޽��',
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
            display: '�豸���޽��',
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
            display: '�������޽��',
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
            display : '�������',
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
            display : '���յ���ܽ��',
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
            display : '����ˮ���ܽ��',
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
            display : '���ݳ����ܽ��',
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
            display : '��װ�����ܽ��',
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
            display: '��Ʊ��',
            name: 'createName',
            sortable: true,
            width: 80
        }, {
            display: '��Ʊ����',
            name: 'invoiceTime',
            sortable: true,
            width: 80
        }, {
            display: '¼������',
            name: 'createTime',
            sortable: true,
            width: 130
        }, {
            display: '����������',
            name: 'updateTime',
            sortable: true,
            hide: true,
            width: 80
        }, {
            display: '���ʼ�',
            name: 'isMail',
            sortable: true,
            process: function (v) {
                if (v == 1) {
                    return '��';
                } else {
                    return '��';
                }
            }
        }],
        /**
         * ��������
         */
        searchitems: [{
            display: '��Ʊ��',
            name: 'invoiceNo'
        }],
        sortorder: "ASC",
        title: '��Ʊ��¼'
    });
});