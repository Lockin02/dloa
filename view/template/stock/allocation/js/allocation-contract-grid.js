var show_page = function () {
    $("#allocationGrid").yxsubgrid("reload");
};
$(function () {
    $("#allocationGrid")
        .yxsubgrid(
        {
            model: 'stock_allocation_allocation',
            action: 'pageListGridJson',
            param: {
                'contractTypeArr': $("#contractType").val(),
                "contractIdArr": $("#contractId").val()
            },
            title: '����������',
            isAddAction: false,
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
                    display: '��ͬ���',
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
                    process: function (v) {
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
                        display: '���ϱ��'
                    },
                    {
                        name: 'productName',
                        width: 200,
                        display: '��������'
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
                    display: '���ϴ���',
                    name: 'productCode'
                },
                {
                    display: '��������',
                    name: 'productName'
                },
                {
                    display: '���Ϲ���ͺ�',
                    name: 'pattern'
                }
            ],
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

                }
            ]
        });
});