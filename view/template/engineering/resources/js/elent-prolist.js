var show_page = function () {
    $("#elentGrid").yxgrid("reload");
};

$(function () {
    var projectId = $("#projectId").val();
    $("#elentGrid").yxgrid({
        model: 'engineering_resources_elent',
        action: 'proPageJson',
        param: {
            projectId: projectId
        },
        title: '�豸ת�赥',
        isDelAction: false,
        isOpButton: false,
        isAddAction: false,
        isEditAction: false,
        showcheckbox: false,
        // ����Ϣ
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
            width: 120,
            process: function (v, row) {
                return "<a href='javascript:void(0)' onclick='showOpenWin(\"?model=engineering_resources_elent&action=toView&id="
                    + row.id + '&skey=' + row.skey_ + "\",1,700,1100," + row.id + ")'>" + v + "</a>";
            }
        }, {
            name: 'applyUser',
            display: '������',
            sortable: true
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
            width: 70
        }, {
            name: 'projectId',
            display: '�����Ŀid',
            sortable: true,
            hide: true
        }, {
            name: 'projectCode',
            display: '�����Ŀ���',
            sortable: true,
            width: 120
        }, {
            name: 'projectName',
            display: '�����Ŀ����',
            sortable: true,
            width: 120
        }, {
            name: 'managerName',
            display: '�����Ŀ����',
            sortable: true,
            hide: true
        }, {
            name: 'managerId',
            display: '�����Ŀ����id',
            sortable: true,
            hide: true
        }, {
            name: 'receiverId',
            display: '������id',
            sortable: true,
            hide: true
        }, {
            name: 'receiverName',
            display: '������',
            sortable: true,
            width: 50
        }, {
            name: 'receiverDept',
            display: '�����˲���',
            sortable: true,
            hide: true
        }, {
            name: 'receiverDeptId',
            display: '�����˲���id',
            sortable: true,
            hide: true
        }, {
            name: 'rcProjectId',
            display: '������Ŀid',
            sortable: true,
            hide: true
        }, {
            name: 'rcProjectCode',
            display: '������Ŀ���',
            sortable: true,
            width: 120
        }, {
            name: 'rcProjectName',
            display: '������Ŀ����',
            sortable: true,
            width: 120
        }, {
            name: 'rcManagerName',
            display: '������Ŀ����',
            sortable: true,
            hide: true
        }, {
            name: 'rcManagerId',
            display: '������Ŀ����id',
            sortable: true,
            hide: true
        }, {
            name: 'reason',
            display: 'ת��ԭ��',
            sortable: true
        }, {
            name: 'remark',
            display: '��ע',
            sortable: true
        }, {
            name: 'status',
            display: '״̬',
            sortable: true,
            process: function (v) {
                if (v == '1') {
                    return "���ύ";
                }
                else
                    return "��ȷ��";
            },
            hide: true
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
            hide: true
        }, {
            name: 'confirmName',
            display: 'ȷ����',
            sortable: true
        }, {
            name: 'confirmId',
            display: 'ȷ����id',
            sortable: true,
            hide: true
        }, {
            name: 'confirmTime',
            display: 'ȷ��ʱ��',
            sortable: true,
            width: 120
        }],
        toViewConfig: {
            toViewFn: function (p, g) {
                var rowObj = g.getSelectedRow();
                var row = rowObj.data('data');
                showOpenWin("?model=engineering_resources_elent&action=toView&id="
                + row[p.keyField], 1, 700, 1100, row.id);
            }
        },
        //��������
        comboEx: [{
            text: '����״̬',
            key: 'status',
            data: [{
                text: 'δ�ύ',
                value: '0'
            }, {
                text: '���ύ',
                value: '1'
            }, {
                text: '��ȷ��',
                value: '2'
            }]
        }],
        searchitems: [{
            display: "���뵥��",
            name: 'formNoSch'
        }, {
            display: "�����Ŀ���",
            name: 'projectCodeSch'
        }, {
            display: "�����Ŀ����",
            name: 'projectNameSch'
        }, {
            display: "������",
            name: 'receiverNameSch'
        }, {
            display: "������Ŀ���",
            name: 'rcProjectCodeSch'
        }, {
            display: "������Ŀ����",
            name: 'rcProjectNameSch'
        }]
    });
});