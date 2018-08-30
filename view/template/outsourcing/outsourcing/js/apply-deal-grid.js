var show_page = function (page) {
    $("#applyGrid").yxgrid("reload");
};

$(function () {
    $("#applyGrid").yxgrid({
        model: 'outsourcing_outsourcing_apply',
        action: "pageJsonDeal",
        title: '�����������',
        isAddAction: false,
        isDelAction: false,
        isEditAction: false,
        isViewAction: false,
        isOpAction: false,
        showcheckbox: false,
        bodyAlign: 'center',
        //����Ϣ
        colModel: [
            {
                display: 'id',
                name: 'id',
                sortable: true,
                hide: true
            }, {
                name: 'applyCode',
                display: '���ݱ��',
                width: 155,
                sortable: true,
                process: function (v, row) {
                    return "<a href='#' onclick='showOpenWin(\"?model=outsourcing_outsourcing_apply&action=toView&id=" + row.id + "\",1)'>" + v + "</a>";
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
                name: 'officeName',
                display: '����',
                width: 60,
                sortable: true
            }, {
                name: 'province',
                display: 'ʡ��',
                width: 60,
                sortable: true
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
                name: 'outType',
                display: '�����ʽ',
                width: 60,
                sortable: true,
                process: function (v, row) {
                    if (row.outType == 1) {
                        return "����";
                    } else if (row.outType == 2) {
                        return "�ְ�";
                    } else {
                        return "��Ա����";
                    }
                }
            }, {
                name: 'natureName',
                display: '��Ŀ����',
                width: 70,
                sortable: true
            }, {
                name: 'personSum',
                display: '������Ա����',
                width: 70,
                sortable: true
            }, {
                name: 'createName',
                display: '������',
                width: 60,
                sortable: true
            }, {
                name: 'createTime',
                display: '����ʱ��',
                width: 120,
                sortable: true
            }, {
                name: 'hopeDate',
                display: '������λʱ��',
                width: 80,
                sortable: true
            }, {
                name: 'ExaDT',
                display: '����ͨ��ʱ��',
                width: 120,
                sortable: true
            }, {
                name: 'predictDate',
                display: 'Ԥ�Ƶ�λʱ��',
                width: 120
            }],

        lockCol: ['applyCode', 'state'],//����������
        //��������
        comboEx: [{
            text: '���״̬',
            key: 'state',
            value: '1',
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
        menusEx: [
            {
                text: "�鿴",
                icon: 'view',
                action: function (row) {
                    showModalWin("?model=outsourcing_outsourcing_apply&action=toView&id=" + row.id, 'newwindow1', 'resizable=yes,scrollbars=yes');
                }
            }, {
                name: 'aduit',
                text: '�����������',
                icon: 'view',
                showMenuFn: function (row) {
                    return row.exaStatus == '���' || row.exaStatus == '���' || row.exaStatus == '��������';
                },
                action: function (row, rows, grid) {
                    if (row) {
                        showThickboxWin("controller/common/readview.php?itemtype=oa_outsourcing_apply&pid="
                            + row.id
                            + "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=800");
                    }
                }
            }, {
            //    text: "����",
            //    icon: 'add',
            //    showMenuFn: function (row) {
            //        if (row.state == '0') {
            //            return true;
            //        }
            //        return false;
            //    },
            //    action: function (row) {
            //        showModalWin("?model=outsourcing_approval_basic&action=toAddByApply&applyId=" + row.id, '1');
            //    }
            //}, {
                text: "����",
                icon: 'add',
                showMenuFn: function (row) {
                    return row.state == '1';
                },
                action: function (row) {
                    if (confirm("ȷ��������")) {
                        $.ajax({
                            url: "?model=outsourcing_outsourcing_apply&action=deal",
                            data: {'id': row.id},
                            type: "post",
                            success: function(msg) {
                                if (msg == "1") {
                                    alert("����ɹ�");
                                    show_page();
                                } else {
                                    alert(msg);
                                }
                            }
                        });
                    }
                }
            }, {
                name: 'aduit',
                text: '���',
                icon: 'delete',
                showMenuFn: function (row) {
                    return row.state == '1';
                },
                action: function (row, rows, grid) {
                    if (row) {
                        showThickboxWin("?model=outsourcing_outsourcing_apply&action=toBackApply&id="
                            + row.id
                            + "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=800");
                    }
                }
            }, {
                text: "�ر�",
                icon: 'delete',
                showMenuFn: function (row) {
                    return row.state == '1';
                },
                action: function (row) {
                    if (row) {
                        showThickboxWin("?model=outsourcing_outsourcing_apply&action=toCloseApply&id=" + row.id
                            + "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=800");
                    }
                }
            }, {
            //    text: "�༭",
            //    icon: 'edit',
            //    showMenuFn: function (row) {
            //        if ((row.appExaStatus == 'δ����' || row.appExaStatus == '���') && row.state == '1' && row.appId > 0) {
            //            return true;
            //        }
            //        return false;
            //    },
            //    action: function (row) {
            //        showModalWin("?model=outsourcing_approval_basic&action=toEdit&id=" + row.appId, '1');
            //    }
            //}, {
            //    text: "�ύ����",
            //    icon: 'add',
            //    showMenuFn: function (row) {
            //        if ((row.appExaStatus == 'δ����' || row.appExaStatus == '���') && row.state == '1' && row.appId > 0) {
            //            return true;
            //        }
            //        return false;
            //    },
            //    action: function (row) {
            //        showThickboxWin('controller/outsourcing/approval/ewf_index.php?actTo=ewfSelect&billId=' + row.appId + '&flowMoney=0&billDept=' + "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=750");
            //    }
            //}, {
            //    text: "������",
            //    icon: 'delete',
            //    showMenuFn: function (row) {
            //        if (( row.appExaStatus == '���') && row.appId > 0 && row.state == '4') {
            //            return true;
            //        }
            //        return false;
            //    },
            //    action: function (row) {
            //        showModalWin("?model=outsourcing_approval_basic&action=toChange&id=" + row.appId, '1');
            //    }
            //}, {
            //    text: "�鿴�������",
            //    icon: 'view',
            //    showMenuFn: function (row) {
            //        if ((row.state == '1' || row.state == '3' || row.state == '4') && row.appId > 0) {
            //            return true;
            //        }
            //        return false;
            //    },
            //    action: function (row) {
            //        showModalWin("?model=outsourcing_approval_basic&action=toViewTab&id=" + row.appId, '1');
            //    }
            //}, {
            //    name: 'aduit',
            //    text: '�����������',
            //    icon: 'view',
            //    showMenuFn: function (row) {
            //        if ((row.appExaStatus == '���' || row.appExaStatus == '���' || row.appExaStatus == '��������') && row.appId > 0) {
            //            return true;
            //        }
            //        return false;
            //    },
            //    action: function (row, rows, grid) {
            //        if (row) {
            //            showThickboxWin("controller/common/readview.php?itemtype=oa_outsourcing_approval&pid="
            //                + row.appId
            //                + "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=800");
            //        }
            //    }
            //}, {
                name: 'aduit',
                text: '�鿴���ԭ��',
                icon: 'view',
                showMenuFn: function (row) {
                    return row.state == '2';
                },
                action: function (row, rows, grid) {
                    if (row) {
                        showThickboxWin("?model=outsourcing_outsourcing_apply&action=toBackView&id="
                            + row.id
                            + "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=800");
                    }
                }
            }, {
                name: 'aduit',
                text: '�鿴�ر�ԭ��',
                icon: 'view',
                showMenuFn: function (row) {
                    return row.state == '5';
                },
                action: function (row, rows, grid) {
                    if (row) {
                        showThickboxWin("?model=outsourcing_outsourcing_apply&action=toCloseView&id="
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