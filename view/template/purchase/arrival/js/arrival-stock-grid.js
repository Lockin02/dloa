var show_page = function () {
    $("#arrivalGrid").yxsubgrid("reload");
};
$(function () {
    $("#arrivalGrid").yxsubgrid({
        model: 'purchase_arrival_arrival',
        action: 'stockPageJson',
        title: '���������֪ͨ��',
        showcheckbox: false,
        isEditAction: false,
        isViewAction: false,
        isDelAction: false,
        isAddAction: false,
        param: {
            'stateArr': "0,4", "arrivalType": "ARRIVALTYPE1", "isCanInstock": "1"
        },
        // ����Ϣ
        colModel: [
            {
                display: 'id',
                name: 'id',
                sortable: true,
                hide: true
            },
            {
                name: 'arrivalCode',
                display: '���ϵ���',
                sortable: true,
                width: 90
            },
            {
                name: 'state',
                display: '����֪ͨ��״̬',
                sortable: true,
                process: function (v, row) {
                    if (row.state == '0') {
                        return "δִ��";
                    } else if (row.state == '4') {
                        return "����ִ��";
                    } else if (row.state == '2') {
                        return "��ִ��";
                    }
                }
            },
            {
                name: 'arrivalDate',
                display: '��������',
                sortable: true,
                width: 80
            },
            {
                name: 'purchaseCode',
                display: '�ɹ��������',
                sortable: true,
                width: 150
            },
            {
                name: 'arrivalType',
                display: '��������',
                sortable: true,
                datacode: 'ARRIVALTYPE'
            },
            {
                name: 'supplierName',
                display: '��Ӧ������',
                sortable: true,
                width: 150
            },
            {
                name: 'purchManName',
                display: '�ɹ�Ա',
                sortable: true,
                width: 70
            },
            {
                name: 'purchMode',
                display: '�ɹ���ʽ',
                hide: true,
                datacode: 'cgfs'
            },
            {
                name: 'stockName',
                display: '���ϲֿ�����',
                sortable: true
            },
            {
                name: 'completionTime',
                display: "�ʼ����ʱ��",
                width: '80'
            }
        ],
        // ���ӱ������
        subGridOptions: {
            url: '?model=purchase_arrival_equipment&action=pageJson',
            param: [
                {
                    paramId: 'arrivalId',
                    colId: 'id'
                }
            ],
            colModel: [
                {
                    name: 'sequence',
                    display: '���ϱ��',
                    width: 80
                },
                {
                    name: 'productName',
                    width: 200,
                    display: '��������'
                },
                {
                    name: 'pattem',
                    display: "����ͺ�"
                },
                {
                    name: 'batchNum',
                    display: "���κ�"
                },
                {
                    name: 'month',
                    display: "�·�",
                    width: '50'
                },
                {
                    name: 'arrivalNum',
                    display: "��������",
                    width: '70'
                },
                {
                    name: 'storageNum',
                    display: "���������",
                    width: '70'
                },
                {
                    name: 'qualityName',
                    display: "�ɹ�����",
                    width: '60'
                },
                {
                    name: 'qualityPassNum',
                    display: "�ʼ�ϸ�����",
                    width: '80'
                },
                {
                    name: 'completionTime',
                    display: "�ʼ����ʱ��",
                    width: '80'
                }
            ]
        },
        // ��չ�Ҽ��˵�
        menusEx: [
            {
                name: 'view',
                text: '�鿴',
                icon: 'view',
                action: function (row, rows, grid) {
                    if (row) {
                        showThickboxWin("?model=purchase_arrival_arrival&action=init&perm=view&id="
                            + row.id
                            + "&skey="
                            + row['skey_']
                            + "&placeValuesBefore&TB_iframe=true&modal=false&height=550&width=900");
                    } else {
                        alert("��ѡ��һ������");
                    }
                }
            },
            {
                name: 'bluepush',
                text: '������ⵥ',
                icon: 'business',
                action: function (row, rows, grid) {
                    if (row) {
                        // alert()
                        showModalWin("index1.php?model=stock_instock_stockin&action=toBluePush&docType=RKPURCHASE&relDocType=RSLTZD&relDocId="
                            + row.id + "&relDocCode=" + row.arrivalCode);
                    } else {
                        alert("��ѡ��һ������");
                    }
                }
            },
            {
                text: '�ر�',
                icon: 'delete',
                action: function (row, rows, grid) {
                    if (row) {
                        if (window.confirm("ȷ��Ҫ�ر�?")) {
                            $.ajax({
                                type: "POST",
                                url: "?model=purchase_arrival_arrival&action=changStateClose",
                                data: {
                                    id: row.id
                                },
                                success: function (msg) {
                                    if (msg == 1) {
                                        alert('�رճɹ�!');
                                        show_page();
                                    }
                                }
                            });
                        }
                    }
                }
            }
        ],
        searchitems: [
            {
                display: '���ϵ���',
                name: 'arrivalCode'
            },
            {
                display: '�ɹ�Ա',
                name: 'purchManName'
            },
            {
                display: '��Ӧ��',
                name: 'supplierName'
            },
            {
                display: '��������',
                name: 'productName'
            },
            {
                display: '���ϱ��',
                name: 'sequence'
            },
            {
                display: '�ɹ��������',
                name: 'purchaseCodeSearch'
            }
        ],
        // Ĭ������˳��
        sortorder: "DESC",
        sortname: "updateTime"
    });
});