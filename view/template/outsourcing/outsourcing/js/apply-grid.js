var show_page = function () {
    $("#applyGrid").yxgrid("reload");
};
$(function () {
    $("#applyGrid").yxgrid({
        model: 'outsourcing_outsourcing_apply',
        action: 'indexData',
        title: '�������',
        isAddAction: false,
        isDelAction: false,
        isEditAction: false,
        isViewAction: false,
        isOpAction: false,
        showcheckbox: false,
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
                width: 60,
                process: function (v, row) {
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
                    return "<a href='#' onclick='showModalWin(\"?model=outsourcing_outsourcing_apply&action=toView&id=" + row.id + "\")'>" + v + "</a>";
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
                name: 'createTime',
                display: '��������',
                width: 120,
                sortable: true
            }],
        buttonsEx: [{
            name: 'view',
            text: "����",
            icon: 'add',
            action: function () {
                showModalWin("?model=outsourcing_outsourcing_apply&action=toAdd", '1');
            }
        }
        ],
        menusEx: [{
            text: "�鿴",
            icon: 'view',
            action: function (row) {
                showModalWin("?model=outsourcing_outsourcing_apply&action=toView&id=" + row.id, 'newwindow1', 'resizable=yes,scrollbars=yes');
            }
        },
            {
                text: "�༭",
                icon: 'edit',
                showMenuFn: function (row) {
                    if (row.state == '0') {
                        return true;
                    }
                    return false;
                },
                action: function (row) {
                    showModalWin("?model=outsourcing_outsourcing_apply&action=toEdit&id=" + row.id, 'newwindow1', 'resizable=yes,scrollbars=yes');
                }
            }, {
                text: "�ύ����",
                icon: 'add',
                showMenuFn: function (row) {
                    if (row.state == '0') {
                        return true;
                    }
                    return false;
                },
                action: function (row) {
                    $.ajax({
                        type: "POST",
                        url: "?model=engineering_officeinfo_range&action=getOfficeInfoForId",
                        data: {'provinceId': row.provinceId},
                        async: false,
                        success: function (data) {
                            if (data != '') {
                                var proObj = eval("(" + data + ")");
                                showThickboxWin('controller/outsourcing/outsourcing/ewf_index.php?actTo=ewfSelect&billId='
                                    + row.id + "&billArea=" + proObj.id
                                    + "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=750");
                            } else {
                                showThickboxWin('controller/outsourcing/outsourcing/ewf_index.php?actTo=ewfSelect&billId=' + row.id + '&flowMoney=0&billDept=' + "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=750");
                            }
                        }
                    });
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
                text: 'ɾ��',
                icon: 'delete',
                showMenuFn: function (row) {
                    if (row.exaStatus == '0' || row.exaStatus == '���') {
                        return true;
                    }
                    return false;
                },
                action: function (row) {
                    if (window.confirm(("ȷ��Ҫɾ��?"))) {
                        $.ajax({
                            type: "POST",
                            url: "?model=outsourcing_outsourcing_apply&action=ajaxdeletes",
                            data: {
                                id: row.id
                            },
                            success: function (msg) {
                                if (msg == 1) {
                                    alert('ɾ���ɹ���');
                                    $("#applyGrid").yxgrid("reload");
                                }
                            }
                        });
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