var show_page = function (page) {
    $("#presentGrid").yxgrid("reload");
};
$(function () {
    $("#presentGrid").yxgrid({
        model: 'projectmanagent_present_present',
        title: '��������',
        // ��ť
        isViewAction: false,
        isAddAction: false,
        isEditAction: false,
        isDelAction: false,
        customCode: 'presentGrid',
        comboEx: [
            {
                text: '����״̬',
                key: 'ExaStatus',
                data: [
                    {
                        text: 'δ����',
                        value: 'δ����'
                    },
                    {
                        text: '��������',
                        value: '��������'
                    },
                    {
                        text: '���',
                        value: '���'
                    },
                    {
                        text: '����ȷ��',
                        value: '����ȷ��'
                    },
                    {
                        text: '���������',
                        value: '���������'
                    }
                ]
            }
        ],
        // ����Ϣ
        colModel: [
            {
                display: 'id',
                name: 'id',
                sortable: true,
                hide: true
            },
            {
                name: 'Code',
                display: '���',
                sortable: true
            },
            {
                name: 'customerName',
                display: '�ͻ�����',
                sortable: true
            },
            {
                name: 'salesName',
                display: '������',
                sortable: true
            },
            {
                name: 'reason',
                display: '��������',
                sortable: true
            },
            {
                name: 'remark',
                display: '��ע',
                sortable: true
            },
            {
                name: 'ExaStatus',
                display: '����״̬',
                sortable: true
            },
            {
                name: 'objCode',
                display: 'ҵ����',
                width: 120
            },
            {
                name: 'SingleType',
                display: 'Դ������',
                width: 120,
                process: function (v) {
                    if (v == "chance") {
                        return "�̻�";
                    }
                }
            },
            {
                name: 'orderCode',
                display: 'Դ�����',
                width: 120
            },
            {
                name: 'orderName',
                display: 'Դ������',
                width: 120
            }
        ],
        // ��չ�Ҽ��˵�

        menusEx: [
            {
                text: '�鿴',
                icon: 'view',
                action: function (row) {
                    if (row) {
                        showModalWin("?model=projectmanagent_present_present&action=viewTab&id="
                            + row.id + "&skey=" + row['skey_']);
                    }
                }
            }
        ],
        /**
         * ��������
         */
        searchitems: [
            {
                display: 'Դ�����',
                name: 'orderCode'
            },
            {
                display: '���',
                name: 'Code'
            },
            {
                display: '�ͻ�����',
                name: 'customerName'
            },
            {
                display: 'ҵ����',
                name: 'objCode'
            }
        ]
    });
});