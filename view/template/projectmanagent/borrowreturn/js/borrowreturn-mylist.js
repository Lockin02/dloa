var show_page = function () {
    $("#myreturnGrid").yxgrid("reload");
};
$(function () {
    $("#myreturnGrid").yxgrid({
        model: 'projectmanagent_borrowreturn_borrowreturn',
        title: '�ҵĹ黹����',
        param: {'createId': $("#userId").val()},
        isViewAction: false,
        isAddAction: false,
        isEditAction: false,
        isDelAction: false,
        showcheckbox: false,
        // ����Ϣ
        colModel: [
            {
                display: 'id',
                name: 'id',
                sortable: true,
                hide: true
            },
            {
                name: 'borrowId',
                display: '���õ�ID',
                sortable: true,
                hide: true
            },
            {
                name: 'applyTypeName',
                display: '��������',
                width: 80,
                sortable: true
            },
            {
                name: 'Code',
                display: '�黹�����',
                sortable: true,
                width: 140,
                process: function (v, row) {
                    if (row.remark != "") {
                        v += " <img src='images/icon/msg.png' style='width:14px;height:14px;' title='��ע : " + row.remark + "'/>";
                    }
                    return v;
                }
            },
            {
                name: 'borrowCode',
                display: '���õ����',
                sortable: true
            },
            {
                name: 'borrowLimit',
                display: '��������',
                sortable: true,
                width: 80
            },
            {
                name: 'customerName',
                display: '�ͻ�����',
                sortable: true,
                width: 220
            },
            {
                name: 'remark',
                display: '��ע',
                sortable: true,
                width: 180,
                hide: true
            },
            {
                name: 'disposeState',
                display: '����״̬',
                sortable: true,
                process: function (v) {
                    switch (v) {
                        case '0' :
                            return '������';
                            break;
                        case '1' :
                            return '���ڴ���';
                            break;
                        case '2' :
                            return '�Ѵ���';
                            break;
                        case '3' :
                            return '�ʼ����';
                            break;
                        case '8' :
                            return '���';
                            break;
//					case '9' : return '�ȴ�����ȷ��';break;
                        default :
                            return '--';
                    }
                },
                width: 80
            },
            {
                name: 'ExaStatus',
                display: '����״̬',
                sortable: true,
                width: 80
            },
            {
                name: 'ExaDT',
                display: '��������',
                sortable: true,
                width: 80
            },
            {
                name: 'updateTime',
                display: '����ʱ��',
                sortable: true,
                width: 120
            }
        ],
        toEditConfig: {
            action: 'toEdit'
        },
        toViewConfig: {
            action: 'toView'
        },
        // ��չ�Ҽ��˵�
        menusEx: [
            {
                text: '�鿴',
                icon: 'view',
                action: function (row) {
                    if (row) {
                        showOpenWin("?model=projectmanagent_borrowreturn_borrowreturn&action=toView&id="
                            + row.id + "&skey=" + row['skey_']);
                    }
                }
            },
            {
                text: '�༭',
                icon: 'edit',
                showMenuFn: function (row) {
                    if (row.ExaStatus == '���ύ' || (row.ExaStatus == '����' && (row.disposeState == '8') || (row.disposeState=='0' && row.ExaStatus=='���') ||  (row.disposeState=='8' && row.ExaStatus=='���'))) {
                        return true;
                    }
                    return false;
                },
                action: function (row, rows, grid) {
                    if (row) {
                        showOpenWin("?model=projectmanagent_borrowreturn_borrowreturn&action=toEdit&id="
                            + row.id
                            + "&skey="
                            + row['skey_']);
                    } else {
                        alert("��ѡ��һ������");
                    }
                }
            },
            {
                text: 'ɾ��',
                icon: 'delete',
                showMenuFn: function (row) {
                    if (row.ExaStatus == '���ύ' || (row.ExaStatus == '����' && (row.disposeState == '8'))) {
                        return true;
                    }
                    return false;
                },
                action: function (row) {
                    if (window.confirm(("ȷ��Ҫɾ��?"))) {
                        $.ajax({
                            type: "POST",
                            url: "?model=projectmanagent_borrowreturn_borrowreturn&action=ajaxdeletes",
                            data: {
                                id: row.id
                            },
                            success: function (msg) {
                                if (msg == 1) {
                                    alert('ɾ���ɹ���');
                                    show_page();
                                }
                            }
                        });
                    }
                }
            }
        ],
        //��������
        comboEx: [
            {
                text: '����״̬',
                key: 'disposeStates',
                data: [
                    {
                        text: '������',
                        value: '0'
                    },
                    {
                        text: '���ڴ���',
                        value: '1'
                    },
                    {
                        text: '�Ѵ���',
                        value: '2'
                    },
                    {
                        text: '�ʼ����',
                        value: '3'
                    },
                    {
//				text : '�ȴ�����ȷ��',
//				value : '9'
//			}, {
                        text: '���',
                        value: '8'
                    }
                ]
            }
        ],
        searchitems: [
            {
                display: "�黹����",
                name: 'Code'
            },
            {
                display: "�����õ���",
                name: 'borrowCodeSearch'
            },
            {
                display: "���к�",
                name: 'serialName'
            }
        ]
    });
});