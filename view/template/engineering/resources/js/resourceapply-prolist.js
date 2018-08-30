var show_page = function () {
    $("#resourceapplyGrid").yxgrid("reload");
};

$(function () {
    var projectCode = $("#projectCode").val();
    var projectId = $("#projectId").val();
    $("#resourceapplyGrid").yxgrid({
        model: 'engineering_resources_resourceapply',
        action: 'proPageJson',
        param: {
            projectId: projectId,
            projectCode: projectCode
        },
        title: '��Ŀ�豸����',
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
            name: 'confirmStatus',
            display: 'ȷ��',
            sortable: true,
            width: 30,
            align: 'center',
            process: function (v, row) {
                switch (v) {
                    case '0' :
                        return '';
                    case '1' :
                        return '<img src="images/icon/ok3.png" title="����ȷ��"/>';
                    case '2' :
                        return '<img src="images/icon/ok2.png" title="��ȷ��,ȷ����[' + row.confirmName + '],ȷ��ʱ��[' + row.confirmTime + ']"/>';
                }
            }
        }, {
            name: 'status',
            display: '����',
            sortable: true,
            width: 30,
            align: 'center',
            process: function (v) {
                switch (v) {
                    case '0' :
                        return '';
                    case '1' :
                        return '<img src="images/icon/cicle_blue.png" title="������"/>';
                    case '2' :
                        return '<img src="images/icon/cicle_green.png" title="�Ѵ���"/>';
                }
            }
        }, {
            name: 'formNo',
            display: '���뵥���',
            sortable: true,
            width: 120,
            process: function (v, row) {
                return "<a href='javascript:void(0)' onclick='showOpenWin(\"?model=engineering_resources_resourceapply&action=toView&id="
                    + row.id + '&skey=' + row.skey_ + "\",1,700,1100," + row.id + ")'>" + v + "</a>";
            }
        }, {
            name: 'applyUser',
            display: '������',
            sortable: true,
            width: 80,
            hide: true
        }, {
            name: 'applyUserId',
            display: '������id',
            sortable: true,
            hide: true
        }, {
            name: 'applyDate',
            display: '��������',
            sortable: true,
            width: 75
        }, {
            name: 'applyTypeName',
            display: '��������',
            sortable: true,
            width: 70
        }, {
            name: 'getTypeName',
            display: '���÷�ʽ',
            sortable: true,
            width: 70
        }, {
            name: 'place',
            display: '�豸ʹ�õ�',
            sortable: true
        }, {
            name: 'deptName',
            display: '��������',
            sortable: true,
            hide: true
        }, {
            name: 'projectCode',
            display: '��Ŀ���',
            sortable: true,
            width: 120
        }, {
            name: 'projectName',
            display: '��Ŀ����',
            sortable: true,
            width: 120
        }, {
            name: 'managerName',
            display: '��Ŀ����',
            sortable: true,
            width: 80,
            hide: true
        }, {
            name: 'managerId',
            display: '��Ŀ����id',
            sortable: true,
            hide: true
        }, {
            name: 'remark',
            display: '��ע��Ϣ',
            sortable: true,
            width: 130,
            hide: true
        }, {
            name: 'ExaStatus',
            display: '����״̬',
            sortable: true,
            width: 70
        }, {
            name: 'ExaDT',
            display: '��������',
            sortable: true,
            width: 75
        }, {
            name: 'createName',
            display: '������',
            sortable: true,
            hide: true
        }, {
            name: 'createTime',
            display: '����ʱ��',
            sortable: true,
            hide: true
        }, {
            name: 'updateName',
            display: '�޸���',
            sortable: true,
            hide: true
        }, {
            name: 'updateTime',
            display: '�޸�ʱ��',
            sortable: true,
            hide: true
        }],
        toAddConfig: {
            toAddFn: function () {
                showOpenWin("?model=engineering_resources_resourceapply&action=toAdd", 1, 700, 1100, 'toRAdd');
            }
        },
        toEditConfig: {
            showMenuFn: function (row) {
                return row.confirmStatus == "0" && (row.ExaStatus == '���ύ' || row.ExaStatus == '���');
            },
            toEditFn: function (p, g) {
                var rowObj = g.getSelectedRow();
                var row = rowObj.data('data');
                showOpenWin("?model=engineering_resources_resourceapply&action=toEdit&id="
                + row.id + "&skey=" + row['skey_'], 1, 700, 1100, row.id);
            }
        },
        toViewConfig: {
            toViewFn: function (p, g) {
                var rowObj = g.getSelectedRow();
                var row = rowObj.data('data');
                showOpenWin("?model=engineering_resources_resourceapply&action=toView&id="
                + row[p.keyField], 1, 700, 1100, row.id);
            }
        },
        //��������
        comboEx: [{
            text: '����״̬',
            key: 'status',
            data: [{
                text: 'δ����',
                value: '0'
            }, {
                text: '������',
                value: '1'
            }, {
                text: '�Ѵ���',
                value: '2'
            }]
        }, {
            text: '���״̬',
            key: 'ExaStatus',
            type: 'workFlow'
        }],
        searchitems: [{
            display: "���뵥��",
            name: 'formNoSch'
        }, {
            display: "��Ŀ���",
            name: 'projectCodeSch'
        }, {
            display: "��Ŀ����",
            name: 'projectNameSch'
        }]
    });
});