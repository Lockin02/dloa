var show_page = function () {
    $("#esmchangeGrid").yxgrid("reload");

    //ˢ����Ŀ�༭ҳ��
    reloadTab('��Ŀ�ƻ�');
    reloadTab('��ĿԤ��');
    reloadTab('��Ŀ�豸');
};

//����ˢ��tab
function reloadTab(thisVal) {
    var tt = window.parent.$("#tt");
    var tb = tt.tabs('getTab', thisVal);
    try {
        tb.panel('options').headerCls = tb.panel('options').thisUrl;
    } catch(e) {
        console.log(e);
    }
}

$(function () {
    var isManager = $("#isManager").val();
    $("#esmchangeGrid").yxgrid({
        model: 'engineering_change_esmchange',
        title: '��Ŀ������뵥',
        param: {"projectId": $("#projectId").val()},
        isAddAction: false,
        isEditAction: false,
        isDelAction: false,
        //����Ϣ
        colModel: [{
            display: 'id',
            name: 'id',
            sortable: true,
            hide: true
        }, {
            name: 'applyDate',
            display: '��������',
            sortable: true,
            width: 70
        }, {
            name: 'newBudgetAll',
            display: '��Ԥ��(��)',
            sortable: true,
            process: function (v, row) {
                if (v != row.orgBudgetAll) {
                    return "<span class='red'>" + moneyFormat2(v) + "</span>";
                }
                return moneyFormat2(v);
            },
            width: 70
        }, {
            name: 'orgBudgetAll',
            display: '��Ԥ��(��)',
            sortable: true,
            process: function (v) {
                return moneyFormat2(v);
            },
            width: 70
        }, {
            name: 'newBudgetField',
            display: '�ֳ�Ԥ��(��)',
            sortable: true,
            process: function (v, row) {
                if (v != row.orgBudgetField) {
                    return "<span class='red'>" + moneyFormat2(v) + "</span>";
                }
                return moneyFormat2(v);
            },
            width: 70
        }, {
            name: 'orgBudgetField',
            display: '�ֳ�Ԥ��(��)',
            sortable: true,
            process: function (v) {
                return moneyFormat2(v);
            },
            width: 70
        }, {
            name: 'newBudgetPerson',
            display: '����Ԥ��(��)',
            sortable: true,
            process: function (v, row) {
                if (v != row.orgBudgetPerson) {
                    return "<span class='red'>" + moneyFormat2(v) + "</span>";
                }
                return moneyFormat2(v);
            },
            width: 70
        }, {
            name: 'orgBudgetPerson',
            display: '����Ԥ��(��)',
            sortable: true,
            process: function (v) {
                return moneyFormat2(v);
            },
            width: 70
        }, {
            name: 'newBudgetEqu',
            display: '�豸Ԥ��(��)',
            sortable: true,
            process: function (v, row) {
                if (v != row.orgBudgetEqu) {
                    return "<span class='red'>" + moneyFormat2(v) + "</span>";
                }
                return moneyFormat2(v);
            },
            width: 70
        }, {
            name: 'orgBudgetEqu',
            display: '�豸Ԥ��(��)',
            sortable: true,
            process: function (v) {
                return moneyFormat2(v);
            },
            width: 70
        }, {
            name: 'newBudgetOutsourcing',
            display: '���Ԥ��(��)',
            sortable: true,
            process: function (v, row) {
                if (v != row.orgBudgetOutsourcing) {
                    return "<span class='red'>" + moneyFormat2(v) + "</span>";
                }
                return moneyFormat2(v);
            },
            width: 70
        }, {
            name: 'orgBudgetOutsourcing',
            display: '���Ԥ��(��)',
            sortable: true,
            process: function (v) {
                return moneyFormat2(v);
            },
            width: 70
        }, {
            name: 'newBudgetOther',
            display: '����Ԥ��(��)',
            sortable: true,
            process: function (v, row) {
                if (v != row.orgBudgetOther) {
                    return "<span class='red'>" + moneyFormat2(v) + "</span>";
                }
                return moneyFormat2(v);
            },
            width: 70
        }, {
            name: 'orgBudgetOther',
            display: '����Ԥ��(��)',
            sortable: true,
            process: function (v) {
                return moneyFormat2(v);
            },
            width: 70
        }, {
            name: 'newPlanEndDate',
            display: '��Ŀ��������(��)',
            sortable: true,
            width: 100,
            process: function (v, row) {
                if (v != row.orgPlanEndDate) {
                    return "<span class='red'>" + v + "</span>";
                }
                return v;
            }
        }, {
            name: 'orgPlanEndDate',
            display: '��Ŀ��������(��)',
            sortable: true,
            width: 100
        }, {
            name: 'ExaStatus',
            display: '����״̬',
            sortable: true,
            width: 60
        }, {
            name: 'ExaDT',
            display: '��������',
            sortable: true,
            width: 80,
            hide: true
        }, {
            name: 'changeDescription',
            display: '���˵��',
            sortable: true,
            width: 150
        }, {
            name: 'createTime',
            display: '��������',
            sortable: true,
            width: 120,
            hide: true
        }],
        toViewConfig: {
            toViewFn: function (p, g) {
                var rowObj = g.getSelectedRow();
                var rowData = rowObj.data('data');
                showModalWin("?model=engineering_change_esmchange&action=toView&id=" + rowData[p.keyField], 1);
            }
        },
        // ��չ�Ҽ��˵�
        menusEx: [{
            text: '�ύ���',
            icon: 'add',
            showMenuFn: function (row) {
                return isManager != "0" && (row.ExaStatus == "���ύ" || row.ExaStatus == '���');
            },
            action: function (row) {
                //�ж���Ŀ�ƻ��¼������аٷֱ��Ƿ����100
                var result = $.ajax({
                    type: 'GET',
                    url: '?model=engineering_change_esmchangeact&action=workRateCountNew&changeId=' + row.id + '&parentId=-1',
                    async: false
                }).responseText;
                result = eval("(" + result + ")");
                if (result.count < 100) {
                    alert('����!\n��Ŀ����-�¼������� ' + result.parentName + ' ����ռ���ܺ�' + result.count + '%,δ��100%');
                    return false;
                }
                if (result.count > 100) {
                    alert('����!\n��Ŀ����-�¼������� ' + result.parentName + ' ����ռ���ܺ�' + result.count + '%,����100%');
                    return false;
                }
                //������Ŀʱ����֤��Ŀʵʩ���ڼ�Ԥ���Ƿ񳬳�ԭPK����ʱ������
                if (row.contractType == 'GCXMYD-04') {
                    var isPKOverproof = false;
                    $.ajax({
                        type: "POST",
                        url: "?model=engineering_change_esmchange&action=isPKOverproof",
                        data: {'id': row.id},
                        async: false,
                        success: function (data) {
                            if (data == 1) {
                                alert("��Ŀʵʩ���ڳ���ԭPK����ʱ�����ã����޸ĺ������ύ����!");
                                isPKOverproof = true;
                            }
                            if (data == 2) {
                                alert("��ĿԤ�㳬��ԭPK����ʱ�����ã����޸ĺ������ύ����!");
                                isPKOverproof = true;
                            }
                        }
                    });
                    if(isPKOverproof){
                        return false;
                    }
                    if (confirm('��Ŀ��ǰ����������Ҫ�������������Ƿ�ȷ�ϱ����')) {
                        //���±����¼�ļƻ���ʼ�ͼƻ�����
                        $.ajax({
                            type: 'POST',
                            url: '?model=engineering_change_esmchange&action=ajaxChange',
                            data: {'id': row.id},
                            success: function (data) {
                                if (data == "1") {
                                    alert('������');
                                    show_page();
                                } else {
                                    alert('���ʧ��');
                                }
                            }
                        });
                    }
                } else {
                    if (row.contractType == 'GCXMYD-01') {
                        if (row.newBudgetAll * 1 > $("#estimates").val() * 1) {
                            alert("��ĿԤ�㲻�ܴ�����Ŀ���㣬���޸ĺ������ύ����!");
                            return false;
                        }
                    }
                    showThickboxWin('?model=engineering_change_esmchange&action=toEdit&id='
                        + row.id
                        + "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=750");
                }
            }
        }, {
            name: 'aduit',
            text: '�������',
            icon: 'view',
            showMenuFn: function (row) {
                return row.ExaStatus != "���ύ";
            },
            action: function (row) {
                showThickboxWin("controller/common/readview.php?itemtype=oa_esm_change_baseinfo&pid="
                + row.id
                + "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=600");
            }
        }, {
            text: 'ɾ��',
            icon: 'delete',
            showMenuFn: function (row) {
                return isManager != "0" && (row.ExaStatus == "���ύ" || row.ExaStatus == '���');
            },
            action: function (rowData, rows, rowIds, g) {
                g.options.toDelConfig.toDelFn(g.options, g);
            }
        }],
        // ����״̬���ݹ���
        comboEx: [{
            text: "����״̬",
            key: 'ExaStatus',
            type: 'workFlow'
        }]
    });
});