var show_page = function () {
    $("#purhasestorageGrid").yxgrid("reload");
};

$(function () {
    // ��������
    var thisYear = $("#thisYear").val();
    var thisMonth = $("#thisMonth").val();

    $("#purhasestorageGrid").yxgrid({
        model: 'stock_instock_stockin',
        action: 'calPageJson',
        title: "������⣨" + thisYear + "." + thisMonth + "��",
        isAddAction: false,
        isViewAction: false,
        isEditAction: false,
        isDelAction: false,
        showcheckbox: false,
        isOpButton: false,
        param: {
            'docType': $('#docType').val(),
            'docStatus': 'YSH',
            'thisYear': thisYear,
            'thisMonth': thisMonth
        },
        usepager: false, // �Ƿ��ҳ
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
                    if (v == 'RKPURCHASE') {
                        return '�⹺���';
                    } else if (v == 'RKOTHER') {
                        return '�������';
                    } else {
                        return '��Ʒ���';
                    }
                }
            }
        }, {
            name: 'docCode',
            display: '���ݱ��',
            sortable: true,
            width: 80
        }, {
            name: 'supplierName',
            display: '��Ӧ������',
            sortable: true,
            width: 140
        }, {
            name: 'inStockName',
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
            name: 'actNum',
            display: '����',
            sortable: true,
            width: 50
        }, {
            name: 'price',
            display: '����',
            sortable: true,
            align: 'right',
            process: function (v) {
                return moneyFormat2(v, 6);
            },
            width: 80
        }, {
            name: 'subPrice',
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
                            url: "?model=stock_instock_stockin&action=updateProductPrice",
                            data: {
                                docStatus: 'YSH', thisYear: $("#thisYear").val(),
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
                            url: "?model=stock_instock_stockin&action=updateProductPrice",
                            data: {
                                docStatus: 'YSH', thisYear: $("#thisYear").val(),
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
                            url: "?model=stock_instock_stockin&action=updateProductPrice",
                            data: {
                                docStatus: 'YSH', thisYear: $("#thisYear").val(),
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
            action: function (row, rows, grid) {
                showOpenWin("?model=stock_instock_stockin&action=toEditPrice&id="
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
            action: function (row, rows, grid) {
                showThickboxWin("?model=stock_instock_stockin&action=toView&id="
                    + row.mainId
                    + "&docType="
                    + row.docType
                    + "&skey="
                    + row['skey_']
                    + "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=900");
            }
        }],
        searchitems: [{
            display: '���ݱ��',
            name: 'docCode'
        }, {
            display: '���ϲֿ�',
            name: 'inStockName'
        }],
        sortorder: "DESC"
    });
});