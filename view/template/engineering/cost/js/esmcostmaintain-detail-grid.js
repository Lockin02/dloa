$(function () {
    var projectId = $("#projectId").val();
    var parentCostType = $("#parentName").val();
    var costType = $("#budgetName").val();

    $("#esmcostmaintainGrid").yxeditgrid({
        url: '?model=engineering_cost_esmcostmaintain&action=searhDetailJson',
        type: 'view',
        param: {
            "projectId": projectId,
            "parentCostType": parentCostType,
            "costType": costType
        },
        //����Ϣ
        colModel: [{
            display: 'id',
            name: 'id',
            type: 'hidden'
        }, {
            name: 'category',
            display: '������Դ',
            process: function (v) {
                if (v == "") {
                    return "����ά��";
                } else {
                    return v;
                }
            },
            width: 100
        }, {
            name: 'parentCostType',
            display: '���ô���',
            align: 'left',
            type: 'hidden'
        }, {
            name: 'costType',
            display: '������Ŀ',
            align: 'left',
            width: 120
        }, {
            name: 'projectCode',
            display: '��Ŀ���',
            type: 'hidden'
        }, {
            name: 'projectName',
            display: '��Ŀ����',
            type: 'hidden'
        }, {
            name: 'month',
            display: '�·�',
            width: 100
        }, {
            name: 'budget',
            display: 'Ԥ��',
            align: 'right',
            process: function (v) {
                return moneyFormat2(v);
            },
            width: 100
        }, {
            name: 'fee',
            display: '����',
            align: 'right',
            process: function (v) {
                return moneyFormat2(v);
            },
            width: 100
        }, {
            name: 'remark',
            display: '��ע',
            align: 'left',
            process: function (v, row) {
                if (row.id == 'noId' || row.ExaStatus != 0)
                    return v;
                if (row.isDel == 1)
                    return v + "<span class='red'>[ɾ��������]</span>";
                if (row.fee * 1 == 0)
                    return v + "<span class='red'>[����������]</span>";
                if (row.fee * 1 != 0)
                    return v + "<span class='red'>[���´�����]</span>";
            },
            width: 250
        }, {
            name: '',
            display: ''
        }]
    });
});