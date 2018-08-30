var show_page = function () {
    $("#erenewGrid").yxgrid("reload");
};

$(function () {
    var projectId = $("#projectId").val();
    $("#erenewGrid").yxgrid({
        model: 'engineering_resources_erenew',
        title: '�豸���赥',
        action: 'proPageJson',
        param: {
            projectId: projectId
        },
        isOpButton: false,
        showcheckbox: false,
        isDelAction: false,
        isAddAction: false,
        isEditAction: false,
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
            name: 'formNo',
            display: '���뵥���',
            sortable: true,
            process: function (v, row) {
                return "<a href='javascript:void(0)' onclick='showOpenWin(\"?model=engineering_resources_erenew&action=toView&id="
                    + row.id + "\",1,700,1100," + row.id + ")'>" + v + "</a>";
            },
            width: 140
        }, {
            name: 'applyUser',
            display: '������',
            sortable: true,
            width: 100,
            hide: true
        }, {
            name: 'applyUserId',
            display: '������id',
            sortable: true,
            hide: true
        }, {
            name: 'deptId',
            display: '��������id',
            sortable: true,
            hide: true
        }, {
            name: 'deptName',
            display: '������������',
            sortable: true,
            hide: true
        }, {
            name: 'applyDate',
            display: '��������',
            sortable: true,
            width: 120
        }, {
            name: 'projectId',
            display: '��Ŀid',
            sortable: true,
            hide: true
        }, {
            name: 'projectCode',
            display: '��Ŀ���',
            sortable: true,
            width: 130
        }, {
            name: 'projectName',
            display: '��Ŀ����',
            sortable: true,
            width: 130
        }, {
            name: 'managerName',
            display: '��Ŀ����',
            sortable: true,
            hide: true,
            width: 100
        }, {
            name: 'managerId',
            display: '��Ŀ����id',
            sortable: true,
            hide: true
        }, {
            name: 'reason',
            display: '����',
            sortable: true,
            width: 170
        }, {
            name: 'remark',
            display: '��ע',
            sortable: true,
            width: 170
        }, {
            name: 'createId',
            display: '������Id',
            sortable: true,
            hide: true
        }, {
            name: 'createName',
            display: '����������',
            sortable: true,
            hide: true
        }, {
            name: 'createTime',
            display: '����ʱ��',
            sortable: true,
            hide: true
        }, {
            name: 'updateId',
            display: '�޸���Id',
            sortable: true,
            hide: true
        }, {
            name: 'updateName',
            display: '�޸�������',
            sortable: true,
            hide: true
        }, {
            name: 'updateTime',
            display: '�޸�ʱ��',
            sortable: true,
            hide: true
        }, {
            name: 'confirmName',
            display: 'ȷ����',
            sortable: true,
            hide: true
        }, {
            name: 'confirmId',
            display: 'ȷ����id',
            sortable: true,
            hide: true
        }, {
            name: 'confirmTime',
            display: 'ȷ��ʱ��',
            sortable: true,
            hide: true
        }],
        toViewConfig: {
            toViewFn: function (p, g) {
                var rowObj = g.getSelectedRow();
                var row = rowObj.data('data');
                showOpenWin("?model=engineering_resources_erenew&action=toView&id="
                + row[p.keyField], 1, 700, 1100, row.id);
            }
        },
        searchitems: [{
            display: "���뵥���",
            name: 'formNoSch'
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