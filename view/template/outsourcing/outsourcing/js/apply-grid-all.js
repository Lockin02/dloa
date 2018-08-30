var show_page = function () {
    $("#applyGrid").yxgrid("reload");
};
$(function () {
    $("#applyGrid").yxgrid({
        model: 'outsourcing_outsourcing_apply',
        title: '�������',
        isAddAction: false,
        isDelAction: false,
        isEditAction: false,
        isViewAction: false,
        isOpAction: false,
        showcheckbox: false,
        param: {exaStatusArr: '��������,���,���'},
        //����Ϣ
        colModel: [
            {
                display: 'id',
                name: 'id',
                sortable: true,
                hide: true
            }, {
                name: 'exaStatus',
                display: '����״̬',
                sortable: true,
                width: 60,
                process: function (v, row) {
                    if (row.exaStatus && row.exaStatus != 0) {
                        return row.exaStatus;
                    } else {
                        return "δ�ύ";
                    }
                }
            }, {
                name: 'state',
                display: '���״̬',
                sortable: true,
                width: 70,
                align: 'center',
                process: function (v) {
                    switch (v) {
                        case '0':
                            return 'δ�ύ';
                        case '1':
                            return '������';
                        case '2':
                            return '�Ѵ��';
                        case '3':
                            return '������';
                        case '4':
                            return '�������';
                        case '5':
                            return '�ر�';
                        case '6':
                            return 'ʵʩ��';
                        default:
                            return '';
                    }
                }
            }, {
                name: 'applyCode',
                display: '���ݱ��',
                width: 150,
                sortable: true,
                process: function (v, row) {
                    return "<a href='#' onclick='showOpenWin(\"?model=outsourcing_outsourcing_apply&action=toView&id=" + row.id + "\")'>" + v + "</a>";
                }
            }, {
                name: 'projecttName',
                display: '��Ŀ����',
                width: 120,
                sortable: true
            }, {
                name: 'projectCode',
                display: '��Ŀ���',
                width: 120,
                sortable: true
            }, {
                name: 'projectClientType',
                display: '��Ŀ�ͻ�����',
                width: 120,
                sortable: true
            }, {
                name: 'projectCharge',
                display: '��Ŀ������',
                width: 120,
                sortable: true
            }, {
                name: 'outType',
                display: '�����ʽ',
                width: 90,
                sortable: true,
                process: function (v, row) {
                    if (row.outType == 1) {
                        return "����";
                    } else if (row.outType == 2) {
                        return "�ְ�";
                    } else {
                        return "��Ա����/������";
                    }
                }
            }, {
                name: 'createName',
                display: '������',
                width: 60,
                sortable: true
            }, {
                name: 'createTime',
                display: '��������',
                sortable: true,
                process: function (v, row) {
                    return row.createTime.substr(0, 10);
                }
            }],
        menusEx: [
            {
                text: "�鿴",
                icon: 'view',
                action: function (row) {
                    showModalWin("?model=outsourcing_outsourcing_apply&action=toView&id=" + row.id, 'newwindow1', 'resizable=yes,scrollbars=yes');
                }
            }, {
                name: 'aduit',
                text: '�������',
                icon: 'view',
                showMenuFn: function (row) {
                    if (row.exaStatus == '���' || row.exaStatus == '���' || row.exaStatus == '��������') {
                        return true;
                    }
                    return false;
                },
                action: function (row, rows, grid) {
                    if (row) {
                        showThickboxWin("controller/common/readview.php?itemtype=oa_outsourcing_apply&pid="
                            + row.id
                            + "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=800");
                    }
                }
            }, {
                name: 'aduit',
                text: '��ز鿴',
                icon: 'view',
                showMenuFn: function (row) {
                    if (row.state == '2') {
                        return true;
                    }
                    return false;
                },
                action: function (row, rows, grid) {
                    if (row) {
                        showThickboxWin("?model=outsourcing_outsourcing_apply&action=toBackView&id="
                            + row.id
                            + "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=800");
                    }
                }
            }
        ],
        //��������
        comboEx: [{
            text: '���״̬',
            key: 'state',
            data: [{
                text: 'δ�ύ',
                value: '0'
            }, {
                text: '������',
                value: '1'
            }, {
                text: '�Ѵ��',
                value: '2'
            }, {
                text: '������',
                value: '3'
            }, {
                text: '�������',
                value: '4'
            }, {
                text: '�ر�',
                value: '5'
            }, {
                text: 'ʵʩ��',
                value: '6'
            }]
        }, {
            text: '�����ʽ',
            key: 'outType',
            data: [{
                text: '����',
                value: '1'
            }, {
                text: '�ְ�',
                value: '2'
            }, {
                text: '��Ա����',
                value: '3'
            }]
        }],
        searchitems: [{
            display: '���뵥��',
            name: 'applyCodeSearch'
        }, {
            display: '��Ŀ����',
            name: 'projecttNameLike'
        }, {
            display: '��Ŀ���',
            name: 'projectCodeLike'
        }, {
            display: '��Ŀ������',
            name: 'projectCharge'
        }, {
            display: '������',
            name: 'createNameLike'
        }, {
            display: '��������',
            name: 'createTimeLike'
        }]
    });
});