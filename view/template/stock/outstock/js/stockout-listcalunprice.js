var show_page = function () {
    $("#stockbalanceGrid").yxgrid("reload");
};

$(function () {
    $("#stockbalanceGrid").yxgrid({
        model: 'stock_outstock_stockout',
        action: 'calPageJson',
        title: '��ȷ�����۵���',
        param: {
            docStatus: 'YSH',
            thisYear: $("#thisYear").val(),
            thisMonth: $("#thisMonth").val(),
            prodcutNoInSB: '1'
        },
        sortname: 'auditDate ASC,productCode',
        sortorder: 'ASC',
        isDelAction: false,
        isEditAction: false,
        isAddAction: false,
        isViewAction: false,
        isShowNum: false,
        showcheckbox: false,
        usepager: false, // �Ƿ��ҳ
        // ����Ϣ
        colModel: [
            {
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
                    if (row.docStatus == 'WSH') {
                        return "δ���";
                    } else if (row.docStatus == 'YSH') {
                        return "�����";
                    }
                }
            }, {
                name: 'docType',
                display: '��������',
                sortable: true,
                width: 70,
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
                name: 'relDocName',
                display: 'Դ������',
                sortable: true,
                hide: true

            }, {
                name: 'customerName',
                display: '�ͻ�����',
                sortable: true,
                width: 140
            }, {
                name: 'stockName',
                display: '���ϲֿ�',
                sortable: true
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
                width: 70
            }, {
                name: 'actOutNum',
                display: '����',
                sortable: true,
                width: 70
            }, {
                name: 'cost',
                display: '����',
                sortable: true,
                process: function (v) {
                    return moneyFormat2(v);
                },
                width: 70
            }, {
                name: 'subCost',
                display: '���',
                sortable: true,
                process: function (v) {
                    return moneyFormat2(v);
                },
                width: 70
            }
        ],
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
                                docStatus: 'YSH', thisYear: $("#thisYear").val(),
                                thisMonth: $("#thisMonth").val(), updateType: 0
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
                text: '�������³����',
                icon: 'edit',
                action: function () {
                    if (confirm("ȷ�ϰ��ա��������³���ۡ�������")) {
                        $.ajax({
                            type: "post",
                            url: "?model=stock_outstock_stockout&action=updateProductPrice",
                            data: {
                                docStatus: 'YSH', thisYear: $("#thisYear").val(),
                                thisMonth: $("#thisMonth").val(), updateType: 1
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
                                docStatus: 'YSH', thisYear: $("#thisYear").val(),
                                thisMonth: $("#thisMonth").val(), updateType: 2
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
        menusEx: [
            {
                name: 'edit',
                text: "�޸�",
                icon: 'edit',
                action: function (row, rows, grid) {
                    showOpenWin("?model=stock_outstock_stockout&action=toEditCost&id="
                        + row.mainId
                        + "&docType="
                        + row.docType
                        + "&skey="
                        + row.skey_);
                }
            }, {
                name: 'view',
                text: "�鿴",
                icon: 'view',
                action: function (row, rows, grid) {
                    showThickboxWin("?model=stock_outstock_stockout&action=toView&id="
                        + row.mainId
                        + "&docType="
                        + row.docType
                        + "&skey="
                        + row.skey_
                        + "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=900");
                }
            }
        ],
        searchitems: [{
            display: '���ݱ��',
            name: 'docCode'
        }, {
            display: '�ֿ�����',
            name: 'stockNameLike'
        }, {
            display: '���ϴ���',
            name: 'productCode'
        }, {
            display: '��������',
            name: 'productName'
        }]
    });
});