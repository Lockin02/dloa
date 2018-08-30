var show_page = function () {
    $("#costHookGrid").yxgrid("reload");
};

$(function () {
    $("#costHookGrid").yxgrid({
        model: 'finance_cost_costHook',
        param: {idArr: $("#ids").val()},
        title: '���÷�̯������¼',
        isAddAction: false,
        isEditAction: false,
        isDelAction: false,
        isOpButton: false,
        //����Ϣ
        colModel: [{
            display: '�������',
            name: 'id',
            sortable: true,
            width: 70
        }, {
            name: 'hookPeriod',
            display: '�����ڼ�',
            sortable: true,
            width: 70
        }, {
            name: 'hookYear',
            display: '�������',
            sortable: true,
            width: 70,
            hide: true
        }, {
            name: 'hookMonth',
            display: '�����·�',
            sortable: true,
            width: 70,
            hide: true
        }, {
            name: 'createId',
            display: '������',
            sortable: true,
            hide: true
        }, {
            name: 'createName',
            display: '��������',
            width: 80,
            sortable: true
        }, {
            name: 'createTime',
            display: '����ʱ��',
            sortable: true,
            width: 130
        }, {
            name: 'hookCode',
            display: '��������',
            sortable: true,
            width: 150
        }, {
            name: 'hookMoney',
            display: '�������',
            sortable: true,
            align: 'right',
            process: function (v) {
                return moneyFormat2(v);
            }
        }],
        searchitems: [{
            display: "��������",
            name: 'dHookCode'
        }]
    });
});