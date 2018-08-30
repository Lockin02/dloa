var show_page = function () {
    $("#esmbudgetGrid").yxgrid("reload");
};

$(function () {
    //������Ŀid
    var projectId = $("#projectId").val();

    //��񲿷�
    $("#esmbudgetGrid").yxgrid({
        model: 'engineering_budget_esmbudget',
        title: '��Ŀ����Ԥ��',
        param: {
            "projectId": projectId
        },
        isAddAction: false,
        isDelAction: false,
        noCheckIdValue: 'noId',
        isOpButton: false,
        //����Ϣ
        colModel: [
            {
                display: 'id',
                name: 'id',
                sortable: true,
                hide: true
            },
            {
                name: 'parentName',
                display: '���ô���',
                sortable: true,
                process: function (v, row) {
                    switch (row.budgetType) {
                        case 'budgetField' :
                            return '<span class="blue">' + v + '</span>';
                            break;
                        case 'budgetPerson' :
                            return '<span class="green">' + v + '</span>';
                            break;
                        case 'budgetOutsourcing' :
                            return '<span style="color:gray">' + v + '</span>';
                            break;
                        case 'budgetOther' :
                            return '����Ԥ��';
                            break;
                        case 'budgetTrial' :
                            return '<span style="color:orange">' + v + '</span>';
                            break;
                        case 'budgetFlights' :
                            return '<span style="color:lightseagreen;">' + v + '</span>';
                            break;
                        default :
                            return v;
                    }
                }
            },
            {
                name: 'budgetName',
                display: '����С��',
                sortable: true,
                width: 120
            },
            {
                name: 'projectCode',
                display: '��Ŀ���',
                sortable: true,
                hide: true
            },
            {
                name: 'projectName',
                display: '��Ŀ����',
                sortable: true,
                hide: true
            },
            {
                name: 'price',
                display: '����',
                align: 'right',
                process: function (v, row) {
                    if (row.customPrice == "1") {
                        return "<span class='blue' title='�Զ���۸�'>" + moneyFormat2(v) + "</span>";
                    } else if (row.isImport == 1) {
                        if (row.status == 0) {//���������,δ��˵����ݱ��
                            return "<span class='red'>" + moneyFormat2(v) + "</span>";
                        } else {//���������,����˵����ݱ���
                            return "<span class='green'>" + moneyFormat2(v) + "</span>";
                        }
                    } else {
                        return moneyFormat2(v);
                    }
                },
                width: 80
            },
            {
                name: 'numberOne',
                display: '����1',
                align: 'right',
                process: function (v, row) {
                    if (row.isImport == 1) {
                        if (row.status == 0) {//���������,δ��˵����ݱ��
                            return "<span class='red'>" + v + "</span>";
                        } else {//���������,����˵����ݱ���
                            return "<span class='green'>" + v + "</span>";
                        }
                    } else {
                        return v;
                    }
                },
                width: 70
            },
            {
                name: 'numberTwo',
                display: '����2',
                align: 'right',
                process: function (v, row) {
                    if (row.isImport == 1) {
                        if (row.status == 0) {//���������,δ��˵����ݱ��
                            return "<span class='red'>" + v + "</span>";
                        } else {//���������,����˵����ݱ���
                            return "<span class='green'>" + v + "</span>";
                        }
                    } else {
                        return v;
                    }
                },
                width: 70
            },
            {
                name: 'amount',
                display: 'Ԥ��',
                align: 'right',
                process: function (v, row) {
                    if (row.isImport == 1) {
                        if (row.status == 0) {//���������,δ��˵����ݱ��
                            return "<span class='red'>" + moneyFormat2(v) + "</span>";
                        } else {//���������,����˵����ݱ���
                            return "<span class='green'>" + moneyFormat2(v) + "</span>";
                        }
                    } else {
                        return moneyFormat2(v);
                    }
                },
                width: 80
            },
            {
                name: 'actFee',
                display: '����',
                align: 'right',
                process: function (v, row) {
                    if (row.isImport == 1) {
                        if (row.status == 0) {//���������,δ��˵����ݱ��
                            return "<span class='red'>" + moneyFormat2(v) + "</span>";
                        } else {//���������,����˵����ݱ���
                            return "<span class='green'>" + moneyFormat2(v) + "</span>";
                        }
                    } else {
                        return moneyFormat2(v);
                    }
                },
                width: 80,
                hide: true
            },
            {
                name: 'amountWait',
                display: '�����Ԥ��',
                align: 'right',
                process: function (v, row) {
                    if (row.isImport == 1) {
                        if (row.status == 0) {//���������,δ��˵����ݱ��
                            return "<span class='red'>" + moneyFormat2(v) + "</span>";
                        } else {//���������,����˵����ݱ���
                            return "<span class='green'>" + moneyFormat2(v) + "</span>";
                        }
                    } else {
                        return moneyFormat2(v);
                    }
                },
                width: 80,
                hide: true
            },
            {
                name: 'actFeeWait',
                display: '����˾���',
                align: 'right',
                process: function (v, row) {
                    if (row.isImport == 1) {
                        if (row.status == 0) {//���������,δ��˵����ݱ��
                            return "<span class='red'>" + moneyFormat2(v) + "</span>";
                        } else {//���������,����˵����ݱ���
                            return "<span class='green'>" + moneyFormat2(v) + "</span>";
                        }
                    } else {
                        return moneyFormat2(v);
                    }
                },
                width: 80,
                hide: true
            },
            {
                name: 'feeProcess',
                display: '���ý���',
                align: 'right',
                process: function (v, row) {
                    if (row.isImport == 1) {
                        if (row.status == 0) {//���������,δ��˵����ݱ��
                            return "<span class='red'>" + v + " %</span>";
                        } else {//���������,����˵�,����100%�����ݱ�죬�����ı���
                            if (v * 1 > 100) {
                                return "<span class='red'>" + v + " %</span>";
                            } else {
                                return "<span class='green'>" + v + " %</span>";
                            }
                        }
                    } else {
                        if (v * 1 > 100) {
                            return "<span class='red'>" + v + " %</span>";
                        } else {
                            return v == "" ? "" : v + " %";
                        }
                    }
                },
                width: 80,
                hide: true
            },
            {
                name: 'budgetType',
                display: '��������',
                sortable: true,
                process: function (v) {
                    switch (v) {
                        case 'budgetField' :
                            return '<span class="blue">�ֳ�Ԥ��</span>';
                            break;
                        case 'budgetPerson' :
                            return '<span class="green">����Ԥ��</span>';
                            break;
                        case 'budgetOutsourcing' :
                            return '<span style="color:gray">���Ԥ��</span>';
                            break;
                        case 'budgetOther' :
                            return '����Ԥ��';
                            break;
                        default:
                            return '';
                    }
                },
                width: 80,
                hide: true
            },
            {
                name: 'remark',
                display: '��ע˵��',
                sortable: true,
                process: function (v, row) {
                    if (row.isImport == 1) {//��̨���������,��ӱ�챸ע����
                        if (row.actFee == undefined) {//���ҳ����ʾ
                            return "<span class='red'>��̨��������</span>";
                        } else {
                            if (row.status == 0) {
                                return "<span class='red'>��̨�������ݣ�δ���</span>";
                            } else {
                                return "<span class='green'>��̨�������ݣ������</span>";
                            }
                        }
                    } else {
                        return v;
                    }
                },
                width: 250
            }
        ],
        buttonsEx: [
            {
                name: 'add',
                text: "����Ԥ��",
                icon: 'add',
                items: [
                    {
                        text: '�ֳ�Ԥ��',
                        icon: 'add',
                        action: function () {
                            var canChange = true;
                            //�ж���Ŀ�Ƿ���Խ��б��
                            $.ajax({
                                type: "POST",
                                url: "?model=engineering_change_esmchange&action=hasChangeInfo",
                                data: {
                                    "projectId": $("#projectId").val()
                                },
                                async: false,
                                success: function (data) {
                                    if (data * 1 == -1) {
                                        canChange = false;
                                    }
                                }
                            });

                            //������ɱ��
                            if (canChange == false) {
                                alert('��Ŀ��������У���ȴ�������ɺ��ٽ��б��������');
                                return false;
                            }
                            showOpenWin("?model=engineering_budget_esmbudget&action=toAddField&projectId="
                                + projectId, 1, 600, 1000, 'toAddField');
                        }
                    },
                    {
                        text: '����Ԥ��',
                        icon: 'add',
                        action: function () {
                            var canChange = true;
                            //�ж���Ŀ�Ƿ���Խ��б��
                            $.ajax({
                                type: "POST",
                                url: "?model=engineering_change_esmchange&action=hasChangeInfo",
                                data: {
                                    "projectId": $("#projectId").val()
                                },
                                async: false,
                                success: function (data) {
                                    if (data * 1 == -1) {
                                        canChange = false;
                                    }
                                }
                            });

                            //������ɱ��
                            if (canChange == false) {
                                alert('��Ŀ��������У���ȴ�������ɺ��ٽ��б��������');
                                return false;
                            }
                            showOpenWin("?model=engineering_budget_esmbudget&action=toAddPerson&projectId="
                                + projectId, 1, 600, 1000, 'toAddPerson');
                        }
                    },
                    {
                        text: '���Ԥ��',
                        icon: 'add',
                        action: function () {
                            var canChange = true;
                            //�ж���Ŀ�Ƿ���Խ��б��
                            $.ajax({
                                type: "POST",
                                url: "?model=engineering_change_esmchange&action=hasChangeInfo",
                                data: {
                                    "projectId": $("#projectId").val()
                                },
                                async: false,
                                success: function (data) {
                                    if (data * 1 == -1) {
                                        canChange = false;
                                    }
                                }
                            });

                            //������ɱ��
                            if (canChange == false) {
                                alert('��Ŀ��������У���ȴ�������ɺ��ٽ��б��������');
                                return false;
                            }
                            showOpenWin("?model=engineering_budget_esmbudget&action=toAddOutsourcing&projectId="
                                + projectId, 1, 600, 1000, 'toAddOutsourcing');
                        }
                    },
                    {
                        text: '����Ԥ��',
                        icon: 'add',
                        action: function () {
                            var canChange = true;
                            //�ж���Ŀ�Ƿ���Խ��б��
                            $.ajax({
                                type: "POST",
                                url: "?model=engineering_change_esmchange&action=hasChangeInfo",
                                data: {
                                    "projectId": $("#projectId").val()
                                },
                                async: false,
                                success: function (data) {
                                    if (data * 1 == -1) {
                                        canChange = false;
                                    }
                                }
                            });

                            //������ɱ��
                            if (canChange == false) {
                                alert('��Ŀ��������У���ȴ�������ɺ��ٽ��б��������');
                                return false;
                            }
                            showOpenWin("?model=engineering_budget_esmbudget&action=toAddOther&projectId="
                                + projectId, 1, 600, 1000, 'toAddOther');
                        }
                    }
                ]
            },
            {
                text: "ɾ��Ԥ��",
                icon: 'delete',
                name: 'batchAdd',
                action: function (row, rows) {
                    if (row) {
                        var canChange = true;
                        var changeId = '';
                        //�ж���Ŀ�Ƿ���Խ��б��
                        $.ajax({
                            type: "POST",
                            url: "?model=engineering_change_esmchange&action=hasChangeInfo",
                            data: {
                                "projectId": projectId
                            },
                            async: false,
                            success: function (data) {
                                if (data * 1 == -1) {
                                    canChange = false;
                                }else{
                                    changeId = data;
                                }
                            }
                        });
                        //������ɱ��
                        if (canChange == false) {
                            alert('��Ŀ��������У���ȴ�������ɺ��ٽ��б��������');
                            return false;
                        }
                        if (confirm('ȷ��ɾ��ѡ�е�Ԥ����ô��')) {
                            var idArr = [];//����id
                            for (var i = 0; i < rows.length; i++) {
                                if (rows[i].isImport == 1) {
                                    alert('����ɾ����̨��������ݣ�');
                                    return false;
                                } else {
                                    idArr.push(rows[i].id);
                                }
                            }
                            // try {
                            //     changeId = rows[i].changeId ? rows[i].changeId : '';
                            // } catch (err) {
                            //
                            // }
                            $.ajax({
                                type: "POST",
                                url: "?model=engineering_budget_esmbudget&action=ajaxdeletes",
                                data: {
                                    "id": idArr.toString(),
                                    "changeId": changeId,
                                    "projectId": projectId
                                },
                                success: function (msg) {
                                    if (msg == 1) {
                                        alert('ɾ���ɹ���');
                                        show_page();
                                    } else {
                                        alert('ɾ��ʧ��!');
                                    }
                                }
                            });
                        }
                    } else {
                        alert('����ѡ������һ����¼');
                    }
                }
            }
        ],
        // ��չ�Ҽ��˵�
        menusEx: [
            {
                text: 'ɾ��',
                icon: 'delete',
                showMenuFn: function (row) {
                    //��������ݲ�����ɾ������
                    if (row.isImport == 1) {
                        return false;
                    }
                },
                action: function (row) {
                    if (row) {
                        var canChange = true;
                        //�ж���Ŀ�Ƿ���Խ��б��
                        $.ajax({
                            type: "POST",
                            url: "?model=engineering_change_esmchange&action=hasChangeInfo",
                            data: {
                                "projectId": projectId
                            },
                            async: false,
                            success: function (data) {
                                if (data * 1 == -1) {
                                    canChange = false;
                                }
                            }
                        });

                        //������ɱ��
                        if (canChange == false) {
                            alert('��Ŀ��������У���ȴ�������ɺ��ٽ��б��������');
                            return false;
                        }
                        if (window.confirm(("ȷ��Ҫɾ��?"))) {
                            $.ajax({
                                type: "POST",
                                url: "?model=engineering_budget_esmbudget&action=ajaxdeletes",
                                data: {
                                    "id": row.id,
                                    'changeId': row.changeId,
                                    "projectId": projectId
                                },
                                success: function (msg) {
                                    if (msg == 1) {
                                        alert('ɾ���ɹ���');
                                        show_page();
                                    } else {
                                        alert("ɾ��ʧ��! ");
                                    }
                                }
                            });
                        }
                    }
                }
            },
            {
                text: 'ȷ��',
                icon: 'add',
                showMenuFn: function (row) {
                    //ֻ�е����������Ҫ��������
                    if (row.isImport == 0 || row.status == 1 || row.actFeeWait == undefined) {
                        return false;
                    }
                },
                action: function (row) {
                    if (row) {
                        if (window.confirm(("����ͨ��?"))) {
                            $.ajax({
                                type: "POST",
                                url: "?model=engineering_budget_esmbudget&action=ajaxAuditing",
                                data: {
                                    "id": row.id
                                },
                                async: false,
                                success: function (data) {
                                    if (data == 1) {
                                        alert('�����ɹ�!');
                                        show_page();
                                    } else {
                                        alert('����ʧ��!');
                                    }
                                }
                            });
                        }
                    }
                }
            },
            {
                text: '���',
                icon: 'delete',
                showMenuFn: function (row) {
                    //ֻ�е����������Ҫ��ز���
                    if (row.isImport == 0 || row.status == 1 || row.actFeeWait == undefined) {
                        return false;
                    }
                },
                action: function (row) {
                    if (row) {
                        if (window.confirm(("ȷ�����?"))) {
                            $.ajax({
                                type: "POST",
                                url: "?model=engineering_budget_esmbudget&action=ajaxBack",
                                data: {
                                    "id": row.id
                                },
                                async: false,
                                success: function (data) {
                                    if (data == 1) {
                                        alert('��سɹ�!');
                                        show_page();
                                    } else {
                                        alert('���ʧ��!');
                                    }
                                }
                            });
                        }
                    }
                }
            }
        ],
        toEditConfig: {
            formWidth: 950,
            formHeight: 500,
            showMenuFn: function (row) {
                //��������ݲ�����ɾ������
                if (row.isImport == 1) {
                    return false;
                }
            },
            action: 'toEdit',
            toEditFn: function (p, g) {
                var rowObj = g.getSelectedRow();
                var row = rowObj.data('data');
                var canChange = true;
                //�ж���Ŀ�Ƿ���Խ��б��
                $.ajax({
                    type: "POST",
                    url: "?model=engineering_change_esmchange&action=hasChangeInfo",
                    data: {
                        "projectId": $("#projectId").val()
                    },
                    async: false,
                    success: function (data) {
                        if (data * 1 == -1) {
                            canChange = false;
                        }
                    }
                });

                //������ɱ��
                if (canChange == false) {
                    alert('��Ŀ��������У���ȴ�������ɺ��ٽ��б��������');
                    return false;
                }
                if (row.changeId) {
                    return showThickboxWin("?model=engineering_budget_esmbudget&action=toEditChange&id=" + row.id + "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=950");
                } else {
                    return showThickboxWin("?model=engineering_budget_esmbudget&action=toEdit&id=" + row.id + '&skey=' + row.skey_ + "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=950");
                }
            }
        },
        toViewConfig: {
            formWidth: 900,
            formHeight: 400,
            action: 'toView',
            toViewFn: function (p, g) {
                var rowObj = g.getSelectedRow();
                var row = rowObj.data('data');
                if (row.changeId) {
                    if (row.isImport == 1) {//����Ŀ����ά�����������
                        showThickboxWin("?model=engineering_cost_esmcostmaintain&action=toSearchDetailList&projectId=" + row.projectId + "&parentName=" + row.parentName
                            + "&budgetName=" + row.budgetName + "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=900");
                    } else {
                        if (row.isChanging == "0") {
                            showThickboxWin("?model=engineering_budget_esmbudget&action=toViewChange&id=" + row.id + '&skey=' + row.skey_ + "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=900");
                        } else {
                            showThickboxWin("?model=engineering_budget_esmbudget&action=toViewChange&id=" + row.id + "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=900");
                        }
                    }
                } else {
                    if (row.isImport == 1) {//����Ŀ����ά�����������
                        showThickboxWin("?model=engineering_cost_esmcostmaintain&action=toSearchDetailList&projectId=" + row.projectId + "&parentName=" + row.parentName
                            + "&budgetName=" + row.budgetName + "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=900");
                    } else {
                        showThickboxWin("?model=engineering_budget_esmbudget&action=toView&id=" + row.id + '&skey=' + row.skey_ + "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=900");
                    }
                }
            }
        },
        //��������
        comboEx: [
            {
                text: '��������',
                key: 'budgetType',
                data: [
                    {
                        text: '�ֳ�Ԥ��',
                        value: 'budgetField'
                    },
                    {
                        text: '����Ԥ��',
                        value: 'budgetPerson'
                    },
                    {
                        text: '���Ԥ��',
                        value: 'budgetOutsourcing'
                    },
                    {
                        text: '����Ԥ��',
                        value: 'budgetOther'
                    },
                    {
                        text: '��Ʊ����',
                        value: 'budgetFlights'
                    },
                    {
                        text: '������Ŀ',
                        value: 'budgetTrial'
                    }
                ]
            }
        ],
        searchitems: [
            {
                display: "����С��",
                name: 'budgetNameSearch'
            },
            {
                display: "���ô���",
                name: 'parentNameSearch'
            }
        ],
        sortname: 'c.budgetType,c.parentName',
        sortorder: 'ASC'
    });
});