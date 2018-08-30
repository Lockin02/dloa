var show_page = function (page) {
    $("#conProListGrid").yxgrid("reload");
};
$(function () {
    $("#conProListGrid").yxgrid({
        model: 'contract_conproject_conproject',
        action: 'contractProReportJson',
//        customCode: 'conprojectStoreListNewList',
        title: '��ƷӪ��',
        isOpButton: false,
        isViewAction: false,
        isEditAction: false,
        isDelAction: false,
        isAddAction: false,
        showcheckbox: false,
        leftLayout: false,
//		lockCol : ['conflag','checkTip'],// ����������
        //����Ϣ
        colModel: [
            {
                display: 'id',
                name: 'id',
                sortable: true,
                hide: true
            },
            {
                name: 'exeDeptName',
                display: 'ִ������',
                sortable: true
            },
            {
                name: 'conProductName',
                display: '��Ʒ����',
                sortable: true,
                width: 220,
                align: 'left'
            },
            {
                name: 'earnings',
                display: '����',
                sortable: true,
                align: 'right',
                width: 100,
                process: function (v) {
                    if (v == '0.00') {
                        return "-";
                    }
                    return moneyFormat2(v);
                },
                align: 'right'
            },
            {
                name: 'gross',
                display: '�ɱ�',
                sortable: true,
                align: 'right',
                width: 100,
                process: function (v) {
                    if (v == '0.00') {
                        return "-";
                    }
                    return moneyFormat2(v);
                },
                align: 'right'
            },
            {
                name: 'exgross',
                display: 'ë����',
                sortable: true,
                align: 'right',
                width: 70,
                process: function (v) {
                    if (v)
                        if (v < 0)
                            return "<span class='red'>" + v + "%</span>";
                        else if (v == 0)
                            return "-";
                        else
                            return v + "%";
                    else
                        return "-";
                }
            }
        ],
        comboEx: [
        {
            text: 'ִ������',
            key: 'exeDeptId',
            datacode: 'GCSCX'
        },
            {
                text: '���ݹ���',
                key: 'warningStr',
                value : '1',
                data: [
                    {
                        text: '��Ч����',
                        value: '1'
                    }
                ]
            }
    ],
        // Ĭ������˳��
        sortorder: "desc",
        searchitems: [
            {
                display: "��ͬ���",
                name: 'contractCode'
            },
            {
                display: "��Ŀ���",
                name: 'projectCode'
            },
            {
                display: "ִ������",
                name: 'proLineName'
            }
        ]



    });


});





