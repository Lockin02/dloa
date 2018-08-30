var show_page = function (page) {
    $("#contractGrid").yxgrid("reload");
};
$(function () {
    $("#contractGrid").yxgrid({
        model: 'contract_contract_contract',
        action: 'pageJsonBasic',
        title: '��ͬ���������б�',
        isViewAction: false,
        isEditAction: false,
        isDelAction: false,
        isAddAction: false,
        // ����Ϣ
        colModel: [
            {
                display: 'id',
                name: 'id',
                sortable: true,
                hide: true
            },
            {
                name: 'ExaDTOne',
                display: "��ͬ����ʱ��",
                sortable: true,
                width: 80
            },
            {
                name: 'contractCode',
                display: '��ͬ���',
                sortable: true,
                width: 120,
                process: function (v, row) {
                    return '<a href="javascript:void(0)" onclick="javascript:showModalWin(\'?model=contract_contract_contract&action=toViewTab&id='
                        + row.id
                        + '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=800\')">'
                        + "<font color = '#4169E1'>"
                        + v
                        + "</font>"
                        + '</a>';
                }
            },
            {
                name: 'contractName',
                display: '��ͬ����',
                sortable: true,
                width: 180
            },
            {
                name: 'contractMoney',
                display: '��ͬ���',
                sortable: true,
                width: 80
            },
            {
                name: 'projectCode',
                display: '��Ŀ���',
                sortable: true,
                width: 120,
                process: function (v) {
                    return "<span class='newline'>" + v + "</span>"
                }
            },
            {
                name: 'projectName',
                display: '��Ŀ����',
                sortable: true,
                width: 150,
                process: function (v) {
                    return "<span class='newline'>" + v + "</span>"
                }
            },
            {
                name: 'proMoney',
                display: '��Ŀ���',
                sortable: true,
                width: 80,
                process: function (v) {
                    return "<span class='newline'>" + v + "</span>"
                }
            },
            {
                name: 'projectType',
                display: '��Ŀ����',
                sortable: true,
                width: 60,
                process: function (v) {
                    return "<span class='newline'>" + v + "</span>"
                }
            },
            {
                name: 'createTime',
                display: '��ͬ�ύ����',
                sortable: true,
                width: 80
            },
            {
                name: 'costAppDate',
                display: '�ɱ��������',
                sortable: true,
                width: 80
            },
            {
                name: 'shipTimes',
                display: '������ͬ��������',
                sortable: true,
                width: 100
            },
            {
                name: 'standardDate',
                display: '��׼����',
                sortable: true,
                width: 80
            },
            {
                name: 'shipPlanDate',
                display: 'Ԥ�Ʒ�������',
                sortable: true,
                width: 80
            },
            {
                name: 'shipDate',
                display: 'ʵ�ʷ�������',
                sortable: true,
                width: 80
            },
            {
                name: 'estimates',
                display: '��Ŀ����',
                sortable: true,
                width: 80,
                process: function (v) {
                    return "<span class='newline'>" + v + "</span>"
                }
            },
            {
                name: 'saleCost',
                display: 'ʵ�ʷ����ɱ�',
                sortable: true,
                width: 80
            },
            {
                name: 'cost',
                display: '��Ŀ����',
                sortable: true,
                width: 80,
                process: function (v) {
                    return "<span class='newline'>" + v + "</span>"
                }
            },
            {
                name: 'earnings',
                display: '��Ŀ����',
                sortable: true,
                width: 80,
                process: function (v) {
                    return "<span class='newline'>" + v + "</span>"
                }
            },
            {
                name: 'exgross',
                display: 'Ԥ��ë����',
                sortable: true,
                width: 80
            },
            {
                name: 'rateOfGross',
                display: 'ë����',
                sortable: true,
                width: 80
            },
            {
                name: 'schedule',
                display: '��Ŀ����',
                sortable: true,
                width: 80,
                process: function (v) {
                    return "<span class='newline'>" + v + "</span>"
                }
            },
            {
                name: 'isAcquiringDate',
                display: '��ͬ�յ�����',
                sortable: true,
                width: 80
            },
            {
                name: 'signinDate',
                display: '��ͬǩ������',
                sortable: true,
                width: 80
            }
        ],
        buttonsEx : {
			name : 'export',
			text : "����",
			icon : 'excel',
			action : function(row) {
				window.open("?model=contract_contract_contract&action=basicExportExcel");
			}
		}
    });
});
