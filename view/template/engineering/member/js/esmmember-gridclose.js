var show_page = function (page) {
    window.opener.location.reload();
    $("#esmmemberGrid").yxgrid("reload");
};

$(function () {
    // �ر���ҳʱ���ص��¼�
    $(window).bind('beforeunload', function () {
        window.opener.loadGrid();
    });

    var projectId = $("#projectId").val();
    $("#esmmemberGrid").yxgrid({
        model: 'engineering_member_esmmember',
        param: {
            "projectId": projectId,
            "memberIdNot": 'SYSTEM'
        },
        title: '��Ŀ��Ա',
        pageSize: 1000,
        usepager: false, // �Ƿ��ҳ
        isAddAction: false,
        isDelAction: false,
        showcheckbox: false,
        noCheckIdValue: 'noId',
        //����Ϣ
        colModel: [{
            display: 'id',
            name: 'id',
            sortable: true,
            hide: true
        }, {
            name: 'projectCode',
            display: '��Ŀ���',
            sortable: true,
            hide: true
        }, {
            name: 'projectName',
            display: '��Ŀ����',
            sortable: true,
            hide: true
        }, {
            name: 'memberName',
            display: '����',
            sortable: true
        }, {
            name: 'memberId',
            display: '��Աid',
            sortable: true,
            hide: true
        }, {
            name: 'personLevel',
            display: '����',
            sortable: true,
            width: 80
        }, {
            name: 'beginDate',
            display: '������Ŀ',
            sortable: true,
            width: 80
        }, {
            name: 'endDate',
            display: '�뿪��Ŀ',
            sortable: true,
            width: 80
        }, {
            name: 'roleName',
            display: '��ɫ',
            sortable: true,
            width: 80
        }, {
            name: 'activityName',
            display: '��������',
            width: 150,
            sortable: true
        }, {
            name: 'feeDay',
            display: '��������',
            sortable: true,
            process: function (v, row) {
                if (row.beginDate == '') {
                    return '0.00';
                }
                if (row.endDate == '') {
                    return "<span class='green' title='��ʱ��Ϣ'>" + moneyFormat2(v) + "</span>";
                } else {
                    return moneyFormat2(v);
                }
            },
            hide: true
        }, {
            name: 'feePeople',
            display: '�����ɱ�(��)',
            sortable: true,
            process: function (v, row) {
                if (row.beginDate == '') {
                    return '0.00';
                }
                if (row.endDate == '') {
                    return "<span class='green' title='��ʱ��Ϣ'>" + moneyFormat2(v) + "</span>";
                } else {
                    return moneyFormat2(v);
                }
            },
            hide: true
        }, {
            name: 'feePerson',
            display: '�����ɱ�(Ԫ)',
            sortable: true,
            process: function (v, row) {
                if (row.beginDate == '') {
                    return '0.00';
                }
                if (row.endDate == '') {
                    return "<span class='green' title='��ʱ��Ϣ'>" + moneyFormat2(v) + "</span>";
                } else {
                    return moneyFormat2(v);
                }
            },
            hide: true
        }, {
            name: 'remark',
            display: '��ע˵��',
            width: 150,
            sortable: true
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
            width: 150,
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
            width: 150,
            hide: true
        }
        ],
        toEditConfig: {
            formWidth: 950,
            formHeight: 400,
            action: 'toEdit',
            showMenuFn: function (row) {
                return $("#read").val() != "1";
            },
        },
        toViewConfig: {
            formWidth: 900,
            formHeight: 400,
            action: 'toView'
        },
        searchitems: [{
            display: "��Ա����",
            name: 'memberNameSearch'
        }, {
            display: "��Ա�ȼ�",
            name: 'personLevelSearch'
        }],
        sortorder: 'ASC',
        sortname: 'personLevel'
    });
});