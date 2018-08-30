var show_page = function () {
    $("#purhasestorageGrid").yxgrid("reload");
};

$(function () {
    // ��������
    var thisYear = $("#thisYear").val();
    var thisMonth = $("#thisMonth").val();

    $("#purhasestorageGrid").yxgrid({
        model: 'stock_outstock_stockout',
        action: 'calPageJson',
        title: "���ֳ�����㣨" + thisYear + "." + thisMonth + "��",
        isAddAction: false,
        isViewAction: false,
        isEditAction: false,
        isDelAction: false,
        showcheckbox: false,
        param: {
            'docStatus': 'YSH',
            'isRed': 1,
            'thisYear': thisYear,
            'thisMonth': thisMonth
        },
        usepager: false, // �Ƿ��ҳ
        isOpButton: false,
        // ����Ϣ
        colModel: [{
            display: 'id',
            name: 'id',
            sortable: true,
            hide: true
        }, {
            display: '��id',
            name: 'mainId',
            sortable: true,
            hide: true
        }, {
            name: 'auditDate',
            display: '��������',
            sortable: true,
            width: 80
        }, {
            name: 'docStatus',
            display: '����״̬',
            sortable: true,
            width: 50,
            process: function (v, row) {
                if (row.id == "nocheck") {
                    return "";
                }
                return row.docStatus == 'WSH' ? "δ���" : "�����";
            }
        }, {
            name: 'docType',
            display: '��������',
            sortable: true,
            width: 60,
            process: function (v, row) {
                if (row.docStatus != undefined) {
                    if (v == 'CKSALES') {
                        return '���۳���';
                    } else if (v == 'CKOTHER') {
                        return '��������';
                    } else {
                        return '���ϳ���';
                    }
                }
            }
        }, {
            name: 'docCode',
            display: '���ݱ��',
            sortable: true,
            width: 80
        }, {
            name: 'customerName',
            display: '�ͻ�����',
            sortable: true,
            width: 140
        }, {
            name: 'stockName',
            display: '���ϲֿ�',
            sortable: true,
            width: 80
        }, {
            name: 'catchStatus',
            display: '����״̬',
            sortable: true,
            hide: true,
            datacode: 'CGFPZT'
        }, {
            name: 'productCode',
            display: '���ϱ��',
            sortable: true,
            width: 80
        }, {
            name: 'productName',
            display: '��������',
            sortable: true,
            width: 110
        }, {
            name: 'unitName',
            display: '��λ',
            sortable: true,
            width: 50
        }, {
            name: 'actOutNum',
            display: '����',
            sortable: true,
            width: 50
        }, {
            name: 'cost',
            display: '����',
            sortable: true,
            align: 'right',
            process: function (v) {
                return moneyFormat2(v, 6);
            },
            width: 80
        }, {
            name: 'subCost',
            display: '���',
            sortable: true,
            align: 'right',
            process: function (v) {
                return moneyFormat2(v);
            },
            width: 80
        }],
        buttonsEx: [{
            name: 'edit',
            text: "���µ���",
            icon: 'edit',
            items: [{
                text: '�������Ȩƽ����',
                icon: 'edit',
                action: function () {
                    if (confirm("ȷ�ϰ��ա��������Ȩƽ���ۡ�������")) {
                        $.ajax({
                            type: "post",
                            url: "?model=stock_outstock_stockout&action=updateProductPrice",
                            data: {
                                docStatus: 'YSH', isRed: 1, thisYear: $("#thisYear").val(),
                                thisMonth: $("#thisMonth").val(), updateType: 0,
                                docType: $("#docType").val()
                            },
                            success: function (msg) {
                                if (msg != "0") {
                                    alert("���³ɹ������¼�¼��" + msg + "����");
                                } else {
                                    alert("�޸�������");
                                }
                                show_page();
                            }
                        });
                    }
                }
            }, {
                text: '���³����',
                icon: 'edit',
                action: function () {
                    if (confirm("ȷ�ϰ��ա����³���ۡ�������")) {
                        $.ajax({
                            type: "post",
                            url: "?model=stock_outstock_stockout&action=updateProductPrice",
                            data: {
                                docStatus: 'YSH', isRed: 1, thisYear: $("#thisYear").val(),
                                thisMonth: $("#thisMonth").val(), updateType: 1,
                                docType: $("#docType").val()
                            },
                            success: function (msg) {
                                if (msg != "0") {
                                    alert("���³ɹ������¼�¼��" + msg + "����");
                                } else {
                                    alert("�޸�������");
                                }
                                show_page();
                            }
                        });
                    }
                }
            }, {
                text: '��������',
                icon: 'edit',
                action: function () {
                    if (confirm("ȷ�ϰ��ա��������ۡ�������")) {
                        $.ajax({
                            type: "post",
                            url: "?model=stock_outstock_stockout&action=updateProductPrice",
                            data: {
                                docStatus: 'YSH', isRed: 1, thisYear: $("#thisYear").val(),
                                thisMonth: $("#thisMonth").val(), updateType: 2,
                                docType: $("#docType").val()
                            },
                            success: function (msg) {
                                if (msg != "0") {
                                    alert("���³ɹ������¼�¼��" + msg + "����");
                                } else {
                                    alert("�޸�������");
                                }
                                show_page();
                            }
                        });
                    }
                }
            }]
        }],
        menusEx: [{
            name: 'edit',
            text: "�޸�",
            icon: 'edit',
            action: function (row) {
                showOpenWin("?model=stock_outstock_stockout&action=toEditCost&id="
                    + row.mainId
                    + "&docType="
                    + row.docType
                    + "&skey="
                    + row['skey_']);
            }
        }, {
            name: 'view',
            text: "�鿴",
            icon: 'view',
            action: function (row) {
                showThickboxWin("?model=stock_outstock_stockout&action=toView&id="
                    + row.mainId
                    + "&docType="
                    + row.docType
                    + "&skey="
                    + row['skey_']
                    + "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=900");
            }
        }],
        comboEx: [{
            text: '��������',
            key: 'docType',
            value: 'CKPICKING',
            data: [{
                text: '���ϳ���',
                value: 'CKPICKING'
            }, {
                text: '���۳���',
                value: 'CKSALES'
            }, {
                text: '��������',
                value: 'CKOTHER'
            }
            ]
        }],
        searchitems: [{
            display: '���ݱ��',
            name: 'docCode'
        }, {
            display: '���ϲֿ�����',
            name: 'inStockName'
        }],
        sortorder: "DESC"
    });
});