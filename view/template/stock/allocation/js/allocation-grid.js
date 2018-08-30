var show_page = function () {
    $("#allocationGrid").yxsubgrid("reload");
};
$(function () {
    $("#allocationGrid").yxsubgrid({
        model: 'stock_allocation_allocation',
        action: 'pageListGridJson',
        title: '����������',
        isAddAction: true,
        isViewAction: false,
        isEditAction: false,
        isDelAction: false,
        showcheckbox: false,
        // ����Ϣ
        colModel: [
            {
                display: 'id',
                name: 'id',
                sortable: true,
                hide: true
            },
            {
                name: 'docCode',
                display: '���ݱ��',
                sortable: true
            },
            {
                name: 'auditDate',
                display: '��������',
                sortable: true
            },
            {
                name: 'toUse',
                display: '������;',
                sortable: true,
                datacode: 'CHUKUYT',
                width: 70
            },
            {
                name: 'docType',
                display: '��������',
                sortable: true,
                hide: true
            },
            {
                name: 'relDocId',
                display: 'Դ��id',
                sortable: true,
                hide: true
            },
            {
                name: 'relDocCode',
                display: 'Դ�����',
                sortable: true,
                hide: true
            },
            {
                name: 'relDocName',
                display: 'Դ������',
                sortable: true,
                hide: true
            },
            {
                name: 'relDocType',
                display: 'Դ������',
                sortable: true,
                hide: true
            },
            {
                name: 'contractId',
                display: '��ͬid',
                sortable: true,
                hide: true
            },
            {
                name: 'contractName',
                display: '��ͬ����',
                sortable: true,
                hide: true
            },
            {
                name: 'contractCode',
                display: '���뵥���',
                sortable: true,
                hide: true
            },
            {
                name: 'outStartDate',
                display: '�����ʼ����',
                sortable: true
            },
            {
                name: 'outEndDate',
                display: '�����������',
                sortable: true

            },
            {
                name: 'customerName',
                display: '�ͻ�(��λ)����',
                sortable: true
            },
            {
                name: 'customerId',
                display: '�ͻ�(��λ)id',
                sortable: true,
                hide: true
            },
            {
                name: 'linkmanId',
                display: '�ͻ���ϵ��id',
                sortable: true,
                hide: true
            },
            {
                name: 'linkmanName',
                display: '�ͻ���ϵ��',
                sortable: true
            },
            {
                name: 'exportStockId',
                display: '�����ֿ�id',
                sortable: true,
                hide: true
            },
            {
                name: 'exportStockCode',
                display: '�����ֿ����',
                sortable: true,
                hide: true
            },
            {
                name: 'exportStockName',
                display: '�����ֿ�����',
                sortable: true
            },
            {
                name: 'importStockId',
                display: '����ֿ�id',
                sortable: true,
                hide: true
            },
            {
                name: 'importStockCode',
                display: '����ֿ����',
                sortable: true,
                hide: true
            },
            {
                name: 'importStockName',
                display: '����ֿ�����',
                sortable: true
            },
            {
                name: 'deptName',
                display: '����',
                sortable: true,
                hide: true
            },
            {
                name: 'deptCode',
                display: '���ű���',
                sortable: true,
                hide: true
            },
            {
                name: 'pickName',
                display: '������(ְԱ)����',
                sortable: true,
                hide: true
            },
            {
                name: 'docStatus',
                display: '����״̬',
                sortable: true,
                process: function (v, row) {
                    if (v == "WSH") {
                        return "δ���";
                    } else {
                        return "�����";
                    }
                },
                width: 50
            },
            {
                name: 'remark',
                display: '��ע',
                sortable: true,
                hide: true

            },
            {
                name: 'pickCode',
                display: '������(ְԱ)����',
                sortable: true,
                hide: true

            },
            {
                name: 'auditerCode',
                display: '����˱��',
                sortable: true,
                hide: true
            },
            {
                name: 'custosCode',
                display: '�����˱��',
                sortable: true,
                hide: true
            },
            {
                name: 'custosName',
                display: '����������',
                sortable: true,
                hide: true
            },
            {
                name: 'chargeCode',
                display: '�����˱��',
                sortable: true,
                hide: true
            },
            {
                name: 'chargeName',
                display: '����������',
                sortable: true,
                hide: true
            },
            {
                name: 'acceptorCode',
                display: '�����˱��',
                sortable: true,
                hide: true
            },
            {
                name: 'acceptorName',
                display: '����������',
                sortable: true,
                hide: true
            },
            {
                name: 'accounterCode',
                display: '�����˱��',
                sortable: true,
                hide: true
            },
            {
                name: 'accounterName',
                display: '����������',
                sortable: true,
                hide: true
            },
            {
                name: 'updateId',
                display: '�޸���',
                sortable: true,
                hide: true
            },
            {
                name: 'updateTime',
                display: '�޸�ʱ��',
                sortable: true,
                hide: true
            },
            {
                name: 'updateName',
                display: '�޸�������',
                sortable: true,
                hide: true
            },
            {
                name: 'createId',
                display: '������',
                sortable: true,
                hide: true
            },
            {
                name: 'createName',
                display: '�Ƶ�',
                sortable: true
            },
            {
                name: 'createTime',
                display: '����ʱ��',
                sortable: true,
                hide: true
            },
            {
                name: 'auditerName',
                display: '�����',
                sortable: true
            }
        ],
        // ���ӱ������
        //���ӱ��м��˸��ֶ�   ����ͺ�   2013.7.5
        subGridOptions: {
            url: '?model=stock_allocation_allocationitem&action=pageJson',
            param: [
                {
                    paramId: 'mainId',
                    colId: 'id'
                }
            ],
            colModel: [
                {
                    name: 'productCode',
                    display: '���ϱ���'
                },
                {
                    name: 'k3Code',
                    display: 'K3����'
                },
                {
                    name: 'productName',
                    width: 200,
                    display: '��������'
                },
                {
                    name: 'pattern',
                    width: 200,
                    display: '����ͺ�'
                },
                {
                    name: 'allocatNum',
                    display: "��������"
                },
                {
                    name: 'serialnoName',
                    display: "���к�",
                    width: '500'
                }
            ]
        },
        toViewConfig: {
            formWidth: 1170,
            formHeight: 500
        },
        comboEx: [
            {
                text: '����״̬',
                key: 'docStatus',
                data: [
                    {
                        text: 'δ���',
                        value: 'WSH'
                    },
                    {
                        text: '�����',
                        value: 'YSH'
                    }
                ]
            }
        ],
        buttonsEx: [
            {
                name: 'advancedsearch',
                text: "�߼�����",
                icon: 'search',
                action: function (row) {
                    showThickboxWin("?model=stock_allocation_allocation&action=toAdvancedSearch"
                        + "&placeValuesBefore&TB_iframe=true&modal=false&height=300&width=600")
                }
            },
            {
                name: 'export',
                text: "����δ�黹����",
                icon: 'excel',
                action: function (row) {
                    window
                        .open(
                            "?model=stock_allocation_allocation&action=exportExcel",
                            "", "width=200,height=200,top=200,left=200");
                }
            }
        ],
        searchitems: [
            {
                display: "���к�",
                name: 'serialnoName'
            },
            {
                display: '���ݱ��',
                name: 'docCode'
            },
            {
                name: 'customerName',
                display: '�ͻ�(��λ)����'
            },
            {
                name: 'pickName',
                display: 'ְԱ����'
            },
            {
                display: '���ϱ���',
                name: 'productCode'
            },
            {
                display: '����K3����',
                name: 'k3Code'
            },
            {
                display: '��������',
                name: 'productName'
            },
            {
                display: '���Ϲ���ͺ�',
                name: 'pattern'
            },
            {
                display: 'Դ�����',
                name: 'relDocCode'
            },
            {
                display: '������������',
                name: 'contractCodeLike'
            }
        ],
        toAddConfig: {
            formWidth: 880,
            formHeight: 600,
            toAddFn: function (p) {
                action : showModalWin("?model=stock_allocation_allocation&action=toAdd")
            }
        },
        sortorder: "DESC",
        menusEx: [
            {
                name: 'view',
                text: "�鿴",
                icon: 'view',
                action: function (row, rows, grid) {
                    showThickboxWin("?model=stock_allocation_allocation&action=toView&id="
                        + row.id
                        + "&skey="
                        + row['skey_']
                        + "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=900");
                }
            },
            {
                name: 'edit',
                text: "�޸�",
                icon: 'edit',
                showMenuFn: function (row) {
                    return row.docStatus == "WSH";
                },
                action: function (row, rows, grid) {
                    showModalWin("?model=stock_allocation_allocation&action=toEdit&id="
                        + row.id + "&skey=" + row['skey_']);
                }
            },
            {
                name: 'copy',
                text: "����",
                icon: 'copy',
                showMenuFn: function (row) {
                    return row.docStatus == "YSH";
                },
                action: function (row, rows, grid) {
                    showModalWin("?model=stock_allocation_allocation&action=toCopy&id="
                        + row.id + "&skey=" + row['skey_']);
                }
            },
            {
                name: 'unlock',
                text: "�����",
                icon: 'unlock',
                showMenuFn: function (row) {
                    if (row.docStatus == "YSH") {
                        var cancelAudit = false;
                        $.ajax({
                            type: "POST",
                            async: false,
                            url: "?model=stock_allocation_allocation&action=cancelAuditLimit",
                            data: {},
                            success: function (result) {
                                if (result == 1)
                                    cancelAudit = true;
                            }
                        })
                        return cancelAudit;
                    } else {
                        return false;
                    }

                },
                action: function (row, rows, grid) {
                    var canAudit = true;// �Ƿ��ѽ���
                    var closedAudit = true;// �Ƿ��ѹ���
                    $.ajax({
                        type: "POST",
                        async: false,
                        url: "?model=finance_period_period&action=isLaterPeriod",
                        data: {
                            thisDate: row.auditDate
                        },
                        success: function (result) {
                            if (result == 0)
                                canAudit = false;
                        }
                    });
                    $.ajax({
                        type: "POST",
                        async: false,
                        url: "?model=finance_period_period&action=isClosed",
                        data: {},
                        success: function (result) {
                            if (result == 1)
                                closedAudit = false;
                        }
                    })
                    if (closedAudit) {
                        if (canAudit) {
                            if (window.confirm("ȷ�Ͻ��з������?")) {
                                $.ajax({
                                    type: "POST",
                                    url: "?model=stock_allocation_allocation&action=cancelAudit",
                                    data: {
                                        id: row.id
                                    },
                                    success: function (result) {
                                        show_page();
                                        if (result == 1) {
                                            alert('���ݷ���˳ɹ���');
                                        } else {
                                            alert(result);
                                        }
                                    }
                                });

                            }
                        } else {
                            alert("�������ڲ��������Ѿ����ˣ����ܽ��з���ˣ�����ϵ������Ա���з����ˣ�")
                        }
                    } else {
                        alert("�����ѹ��ˣ����ܽ��з���ˣ�����ϵ������Ա���з����ˣ�")
                    }
                }
            },
            {
                name: 'view',
                text: "������־",
                icon: 'view',
                action: function (row) {
                    showThickboxWin("?model=syslog_operation_logoperation&action=businessView&pkValue="
                        + row.id
                        + "&tableName=oa_stock_allocation"
                        + "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=850");
                }
            },
            {
                name: 'view',
                text: "��ӡ",
                icon: 'print',
                action: function (row, rows, grid) {
                    showThickboxWin("?model=stock_allocation_allocation&action=toPrint&id="
                        + row.id
                        + "&skey="
                        + row['skey_']
                        + "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=900");
                }
            },
            {
                text: 'ɾ��',
                icon: 'delete',
                showMenuFn: function (row) {
                    return row.docStatus == "WSH";
                },
                action: function (row, rows, grid) {
                    if (window.confirm("ȷ��Ҫɾ��?")) {
                        $.ajax({
                            type: "POST",
                            url: "?model=stock_allocation_allocation&action=ajaxdeletes",
                            data: {
                                id: row.id
                            },
                            success: function (msg) {
                                if (msg == 1) {
                                    show_page();
                                    alert('ɾ���ɹ���');
                                } else {
                                    alert('ɾ��ʧ�ܣ��ö�������Ѿ�������!');
                                }
                            }
                        });
                    }
                }
            }
        ]
    });
});