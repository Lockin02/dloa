var show_page = function () {
    $("#ereturnlistGrid").yxgrid("reload");
};

$(function () {
    var projectId = $("#projectId").val();
    $("#ereturnlistGrid").yxgrid({
        model: 'engineering_resources_ereturn',
        action: 'proPageJson',
        param: {
            projectId: projectId
        },
        title: '�豸�黹��',
        isDelAction: false,
        isOpButton: false,
        isAddAction: false,
        isEditAction: false,
        showcheckbox: false,
        //����Ϣ
        colModel: [{
            display: 'id',
            name: 'id',
            sortable: true,
            hide: true
        }, {
            name: 'status',
            display: '״̬',
            sortable: true,
            width: 30,
            align: 'center',
            process: function (v) {
                switch (v) {
                    case '0' :
                        return '<img src="images/icon/cicle_grey.png" title="δ�ύ"/>';
                    case '1' :
                        return '<img src="images/icon/cicle_blue.png" title="���ύ"/>';
                    case '2' :
                        return '<img src="images/icon/cicle_green.png" title="��ȷ��"/>';
                }
            }
        }, {
            display: '���뵥���',
            name: 'formNo',
            width: 140,
            sortable: true,
            process: function (v, row) {
                return "<a href='javascript:void(0)' onclick='showOpenWin(\"?model=engineering_resources_ereturn&action=toView&id="
                    + row.id + "\",1,700,1100," + row.id + ")'>" + v + "</a>";
            }
        }, {
            name: 'applyUser',
            display: '������',
            sortable: true,
            width: 90,
            hide: true
        }, {
            name: 'applyDate',
            display: '��������',
            sortable: true,
            width: 90
        }, {
            name: 'areaName',
            display: '�黹����',
            sortable: true,
            width: 80
        }, {
            name: 'projectCode',
            display: '��Ŀ���',
            sortable: true,
            width: 150
        }, {
            name: 'projectName',
            display: '��Ŀ����',
            sortable: true,
            width: 150
        }, {
            name: 'managerName',
            display: '��Ŀ����',
            sortable: true,
            width: 80,
            hide: true
        }, {
            name: 'expressName',
            display: '��ݹ�˾',
            sortable: true,
            width: 120
        }, {
            name: 'expressNo',
            display: '��ݵ���',
            sortable: true,
            width: 120
        }, {
            name: 'remark',
            display: '��ע��Ϣ',
            sortable: true,
            width: 130
        }],
        toViewConfig: {
            toViewFn: function (p, g) {
                var rowObj = g.getSelectedRow();
                var row = rowObj.data('data');
                showOpenWin("?model=engineering_resources_ereturn&action=toView&id="
                + row[p.keyField], 1, 700, 1100, row.id);
            }
        },

        searchitems: [{
            display: "���뵥���",
            name: 'formNoSch'
        }, {
            display: "�黹����",
            name: 'areaNameSch'
        }, {
            display: "��Ŀ���",
            name: 'projectCodeSch'
        }, {
            display: "��Ŀ����",
            name: 'projectNameSch'
        }],

        //��������
        comboEx: [{
            text: '״̬',
            key: 'status',
            data: [{
                text: '���ύ',
                value: '0'
            }, {
                text: '���ύ',
                value: '1'
            }, {
                text: '��ȷ��',
                value: '2'
            }]
        }]
    });
});