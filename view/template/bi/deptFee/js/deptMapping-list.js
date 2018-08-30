var show_page = function () {
    $("#grid").yxgrid("reload");
};
$(function () {
    // ��ȡ����

    // �б��ʼ��
    $("#grid").yxgrid({
        model: 'bi_deptFee_deptMapping',
        title: '���Ź�ϵ����',
        isOpButton: false,
        //����Ϣ
        colModel: [
            {
                display: 'id',
                name: 'id',
                sortable: true,
                hide: true
            },
            {
                name: 'business',
                display: '��ҵ��',
                sortable: true
            },
            {
                name: 'secondDept',
                display: '��������',
                sortable: true,
                width: 80
            },
            {
                name: 'thirdDept',
                display: '��������',
                sortable: true,
                width: 80
            },
            {
                name: 'fourthDept',
                display: '�ļ�����',
                sortable: true,
                width: 80
            },
            {
                name: 'mappingDept',
                display: '��������',
                width: 200
            },
            {
                name: 'module',
                display: '���',
                sortable: true,
                width: 80
            },
            {
                name: 'productLine',
                display: '��Ʒ��',
                sortable: true,
                width: 80
            },
            {
                name: 'costCategory',
                display: '�������',
                sortable: true,
                width: 80
            },
            {
                name: 'forbidFeeType',
                display: '��ͳ����',
                sortable: true,
                width: 80
            },
            {
                name: 'sortOrder',
                display: '���',
                sortable: true,
                width: 30,
                align: 'center'
            },
            {
                name: 'filterStartDateStr',
                display: 'ͳ�ƿ�ʼ����',
                sortable: true,
                width: 80,
                align: 'center'
            },
            {
                name: 'filterEndDateStr',
                display: 'ͳ�ƽ�ֹ����',
                sortable: true,
                width: 80,
                align: 'center'
            },
            {
                name: 'remark',
                display: '��ע˵��',
                width: 200
            }
        ],
        buttonsEx: [{
            text: "��������",
            icon: 'add',
            action: function () {
                showThickboxWin("?model=bi_deptFee_deptMapping&action=toImport"
                    + "&placeValuesBefore&TB_iframe=true&modal=false&height=350&width=700")
            }
        }, {
            text: '��������',
            icon: 'excel',
            action: function () {
                var thisGrid = $("#grid").data('yxgrid');
                var colName = [];
                var colCode = [];
                var colModel = thisGrid.options.colModel;
                for (var i = 0; i < colModel.length; i++) {
                    if (!colModel[i].hide) {
                        colName.push(colModel[i].display);
                        colCode.push(colModel[i].name);
                    }
                }
                var url = "?model=bi_deptFee_deptMapping&action=export"
                    + '&colName=' + colName.toString() + '&colCode=' + colCode.toString();
                if (thisGrid.options.qtype) {
                    url += "&" + thisGrid.options.qtype + "=" + thisGrid.options.query;
                }
                window.open(url, "", "width=200,height=200,top=200,left=200");
            }
        }],
        toEditConfig: {
            action: 'toEdit'
        },
        toViewConfig: {
            action: 'toView'
        },
        searchitems: [
            {
                display: "��ҵ��/����",
                name: 'businessSearch'
            },
            {
                display: "��������",
                name: 'secondDeptSearch'
            },
            {
                display: "��������",
                name: 'thirdDeptSearch'
            },
            {
                display: "�ļ�����",
                name: 'fourthDeptSearch'
            },
            {
                display: "��������",
                name: 'mappingDeptSearch'
            },
            {
                display: "��ע˵��",
                name: 'remarkSearch'
            }
        ]
    });
});