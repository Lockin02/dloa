var show_page = function () {
    $("#expenseGrid").yxgrid("reload");
};

//�鿴���� - �����¾ɱ�����
function viewBill(id, billNo, isNew) {
    if (isNew == '1') {
        showModalWin("?model=finance_expense_exsummary&action=toView&id=" + id, 1)
    } else {
        showOpenWin("general/costmanage/reim/summary_detail.php?status=���ɸ���&BillNo=" + billNo, 1)
    }
}

function getCostSummaryProvinceOpts(){
    var result = $.ajax({
        type: 'POST',
        url: "index1.php?model=finance_expense_expense&action=getAllProProvince",
        async: false
    }).responseText;
    var result = eval("("+result+")");
    var optArr = [];
    $.each(result.optsArr,function(i,item){
        optArr.push({
            text: item,
            value: i
        });
    })
    return optArr;
}

$(function () {
    var provinceOpts = getCostSummaryProvinceOpts();
    $("#expenseGrid").yxgrid({
        model: 'finance_expense_expense',
        action: 'myPageJson',
        title: '�ҵı�����ϸ',
        isDelAction: false,
        customCode: 'myexpense',
        showcheckbox: false,
        isOpButton: false,
        //����Ϣ
        colModel: [{
            display: 'id',
            name: 'id',
            sortable: true,
            hide: true
        }, {
            display: '�յ�',
            name: 'recView',
            sortable: true,
            align: 'center',
            width: 30,
            process: function (v, row) {
                if (row.needExpenseCheck == "1") {
                    if (row.IsFinRec == '1') {
                        return '<img title="�����յ�[' + row.RecInvoiceDT + '] \n�Ͻ�����[' + row.HandUpDT + '] \n�����յ�[' + row.FinRecDT + ']" src="images/icon/ok3.png" style="width:15px;height:15px;">';
                    } else {
                        if (row.isHandUp == "1") {
                            return '<img title="�����յ�[' + row.RecInvoiceDT + '] \n�Ͻ�����[' + row.HandUpDT + ']" src="images/icon/ok2.png" style="width:15px;height:15px;">';
                        } else {
                            if (row.isNotReced == '0') {
                                return '<img title="�����յ�[' + row.RecInvoiceDT + ']" src="images/icon/ok1.png" style="width:15px;height:15px;">';
                            }
                        }
                    }
                } else {
                    if (row.IsFinRec == '1') {
                        return '<img title="�����յ�[' + row.FinRecDT + ']" src="images/icon/ok3.png" style="width:15px;height:15px;">';
                    }
                }
            }
        }, {
            display: '�����յ�״̬',
            name: 'isNotReced',
            sortable: true,
            width: 60,
            hide: true,
            process: function (v) {
                return v == '0' ? '���յ�' : 'δ�յ�';
            }
        }, {
            display: '�����յ�ʱ��',
            name: 'RecInvoiceDT',
            sortable: true,
            width: 120,
            hide: true
        }, {
            display: '�����Ͻ�״̬',
            name: 'isHandUp',
            sortable: true,
            width: 60,
            hide: true,
            process: function (v) {
                return v == '1' ? '���Ͻ�' : 'δ�Ͻ�';
            }
        }, {
            display: '�����Ͻ�ʱ��',
            name: 'HandUpDT',
            sortable: true,
            width: 120,
            hide: true
        }, {
            display: '�����յ�״̬',
            name: 'IsFinRec',
            sortable: true,
            width: 60,
            hide: true,
            process: function (v) {
                return v == '1' ? '���յ�' : 'δ�յ�';
            }
        }, {
            display: '�����յ�ʱ��',
            name: 'FinRecDT',
            sortable: true,
            width: 120,
            hide: true
        }, {
            display: '�±�����',
            name: 'isNew',
            sortable: true,
            width: 50,
            process: function (v) {
                return v == '1' ? '��' : '��';
            },
            hide: true
        }, {
            display: '��Ҫ���ż��',
            name: 'needExpenseCheck',
            sortable: true,
            width: 50,
            process: function (v) {
                return v == '1' ? '��' : '��';
            },
            hide: true
        }, {
            name: 'BillNo',
            display: '��������',
            sortable: true,
            width: 130,
            process: function (v, row) {
                return "<a href='javascript:void(0)' onclick='viewBill(\"" + row.id + "\",\"" + row.BillNo + "\",\"" +
                    row.isNew + "\")'>" + v + "</a>";
            }
        }, {
            name: 'DetailType',
            display: '��������',
            sortable: true,
            width: 70,
            process: function (v, row) {
                if (v * 1 > 0) {
                    switch (v) {
                        case '1' :
                            return '���ŷ���';
                        case '2' :
                            return '��ͬ��Ŀ����';
                        case '3' :
                            return '�з�����';
                        case '4' :
                            return '��ǰ����';
                        case '5' :
                            return '�ۺ����';
                        default :
                            return v;
                    }
                } else {
                    switch (row.CostBelongTo) {
                        case '1' :
                            return '���ŷ���';
                        default :
                            return '���̷���';
                    }
                }
            }
        }, {
            name: 'CostManName',
            display: '������',
            sortable: true,
            width: 90,
            hide: true
        },  {
            name: 'CostDepartName',
            display: '�����˲���',
            sortable: true,
            width: 80,
            hide: true
        }, {
            name: 'CostManCom',
            display: '�����˹�˾',
            sortable: true,
            width: 80,
            hide: true
        }, {
            name: 'CostBelongDeptName',
            display: '���ù�������',
            sortable: true,
            width: 75
        }, {
            name: 'CostBelongCom',
            display: '���ù�����˾',
            sortable: true,
            width: 75
        }, {
            name: 'Amount',
            display: '�������',
            sortable: true,
            width: 80,
            process: function (v) {
                return moneyFormat2(v);
            }
        }, {
            name: 'feeRegular',
            display: '�������',
            sortable: true,
            width: 80,
            process: function (v) {
                return moneyFormat2(v);
            },
            hide: true
        }, {
            name: 'feeSubsidy',
            display: '��������',
            sortable: true,
            width: 80,
            process: function (v) {
                return moneyFormat2(v);
            },
            hide: true
        }, {
            name: 'invoiceMoney',
            display: '��Ʊ���',
            sortable: true,
            width: 80,
            hide: true,
            process: function (v) {
                return moneyFormat2(v);
            }
        }, {
            name: 'invoiceNumber',
            display: '��Ʊ����',
            sortable: true,
            width: 60,
            hide: true
        }, {
            name: 'CheckAmount',
            display: '�����',
            sortable: true,
            hide: true,
            width: 80,
            process: function (v) {
                return moneyFormat2(v);
            }
        }, {
            name: 'isProject',
            display: '��Ŀ����',
            sortable: true,
            process: function (v) {
                return v == '1' ? '��' : '��';
            },
            width: 60,
            hide: true
        }, {
            name: 'ProjectNO',
            display: '��Ŀ���',
            sortable: true,
            width: 150,
            hide: true
        }, {
            name: 'projectName',
            display: '��Ŀ����',
            sortable: true,
            width: 150,
            hide: true
        }, {
            name: 'proManagerName',
            display: '��Ŀ����',
            sortable: true,
            width: 80,
            hide: true
        }, {
            name: 'proProvince',
            display: '��Ŀʡ��',
            sortable: true,
            width: 90
        }, {
            name: 'contractCode',
            display: '��ͬ���',
            sortable: true,
            width: 120,
            hide: true
        }, {
            name: 'contractName',
            display: '��ͬ����',
            sortable: true,
            width: 120,
            hide: true
        }, {
            name: 'customerName',
            display: '�ͻ�����',
            sortable: true,
            width: 120,
            hide: true
        }, {
            name: 'CustomerType',
            display: '�ͻ�����',
            sortable: true,
            hide: true
        }, {
            name: 'Purpose',
            display: '����',
            sortable: true,
            width: 180
        }, {
            name: 'InputManName',
            display: '¼����',
            sortable: true,
            width: 90,
            hide: true
        }, {
            name: 'Status',
            display: '����״̬',
            sortable: true,
            width: 70
        }, {
            name: 'ExaStatus',
            display: '����״̬',
            width: 70,
            sortable: true,
            hide: true
        }, {
            name: 'ExaDT',
            display: '��������',
            width: 80,
            sortable: true
        }, {
            name: 'InputDate',
            display: '¼��ʱ��',
            sortable: true,
            width: 130
        }, {
            name: 'subCheckDT',
            display: '�ύ���ʱ��',
            sortable: true,
            width: 130,
            hide: true
        }, {
            name: 'UpdateDT',
            display: '����ʱ��',
            sortable: true,
            width: 130,
            hide: true
        }],
        toAddConfig: {
            toAddFn: function () {
                showModalWin("?model=finance_expense_expense&action=toAdd", 1, 'expenseAdd');
            }
        },
        //�򿪵���expense�еķ��� -- ���αȽ�����
        toEditConfig: {
            showMenuFn: function (row) {
                return (row.isNew == '1' && row.ExaStatus == '�༭' && row.Status == '�༭')
                    || row.isNew == '0' && row.Status == '�༭';
            },
            toEditFn: function (p, g) {
                var rowObj = g.getSelectedRow();
                var rowData = rowObj.data('data');
                if (rowData.isNew == '1') {
                    showModalWin("?model=finance_expense_expense&action=toEdit&id=" + rowData.id, 1, rowData.BillNo);
                } else {
                    alert('�˵���Ϊ���ɱ������������ܽ��С��༭��������������¼��һ���±�����������֮���������½�');
                }
            }
        },
        //�򿪵���expense�еķ��� -- ���αȽ�����
        toViewConfig: {
            toViewFn: function (p, g) {
                var rowObj = g.getSelectedRow();
                var row = rowObj.data('data');
                viewBill(row.id, row.BillNo, row.isNew);
            }
        },
        menusEx: [{
            text: "���±༭",
            icon: 'edit',
            showMenuFn: function (row) {
                return row.Status == '���';
            },
            action: function (row) {
                if (row.isNew == '1') {
                    showModalWin("?model=finance_expense_expense&action=toEdit&id=" + row.id, 1, row.BillNo);
                } else {
                    alert('�˵���Ϊ���ɱ������������ܽ��С����±༭��������������¼��һ���±�����������֮���������½�');
                }
            }
        }, {
            text: "�ύ���ż��",
            icon: 'edit',
            showMenuFn: function (row) {
                if (row.needExpenseCheck == "0") {
                    return false;
                }
                return row.isNew == '1' && row.Status == '�༭';
            },
            action: function (row) {
                if (window.confirm(("ȷ��Ҫ�ύ���ż����?"))) {
                    if (row.projectId != "0") {
                        var projectOverspend = false;
                        $.ajax({
                            type: "POST",
                            url: "?model=engineering_project_esmproject&action=ajaxGetProject",
                            data: {'id': row.projectId},
                            dataType: 'json',
                            async: false,
                            success: function (data) {
                                if (data != null) {
                                    if (data.feeAll * 1 > data.budgetAll * 1) {
                                        projectOverspend = false;
                                        alert('��ʾ������Ŀ���ѳ�֧���ݲ������ύ������������ϵ��Ŀ������������ĿԤ�㡣');
                                    }else{
                                        projectOverspend = true;
                                    }
                                } else {
                                    projectOverspend = false;
                                    alert('��ʾ����Ŀƥ����������³���');
                                }
                            }
                        });
                        if (!projectOverspend) {
                            return false;
                        }
                    }

                    $.ajax({
                        type: "POST",
                        url: "?model=finance_expense_expense&action=ajaxHand",
                        data: {
                            id: row.id
                        },
                        success: function (msg) {
                            if (msg == '1') {
                                alert('�ύ�ɹ���');
                                show_page(1);
                            } else {
                                alert("�ύʧ��! ");
                            }
                        }
                    });
                }
            }
        }, {
            text: "ȷ�ϵ���",
            icon: 'edit',
            showMenuFn: function (row) {
                return row.isNew == '1' && row.Status == '�ȴ�ȷ��';
            },
            action: function (row) {
                if (window.confirm(("ȷ��Ҫȷ�ϵ�����"))) {
                    $.ajax({
                        type: "POST",
                        url: "?model=finance_expense_expense&action=confirmCheck",
                        data: {
                            id: row.id
                        },
                        success: function (msg) {
                            if (msg == '1') {
                                alert('ȷ�ϳɹ���');
                                show_page(1);
                            } else {
                                alert("ȷ��ʧ��! ");
                            }
                        }
                    });
                }
            }
        }, {
            text: "���ϵ���",
            icon: 'delete',
            showMenuFn: function (row) {
                return row.isNew == '1' && row.Status == '�ȴ�ȷ��';
            },
            action: function (row) {
                if (window.confirm(("ȷ��Ҫ���ϵ�����"))) {
                    $.ajax({
                        type: "POST",
                        url: "?model=finance_expense_expense&action=unconfirmCheck",
                        data: {
                            id: row.id
                        },
                        success: function (msg) {
                            if (msg == '1') {
                                alert('���ϳɹ���');
                                show_page(1);
                            } else {
                                alert("����ʧ��! ");
                            }
                        }
                    });
                }
            }
        }, {
            text: "�ύ����",
            icon: 'edit',
            showMenuFn: function (row) {
                if (row.needExpenseCheck == "1") {
                    return false;
                }
                return row.isNew == '1'
                    && (row.ExaStatus == '�༭' || row.ExaStatus == '���')
                    && row.Status != '���ż��';
            },
            action: function (row) {
                //��������ǹ��̱��������Һ�����Ŀ��Ϣ,��ȡ��Ŀ����
                var rangeId = '';
                if (row.projectId != "0") {
                    var projectOverspend = false; // ��֧
                    $.ajax({
                        type: "POST",
                        url: "?model=engineering_project_esmproject&action=ajaxGetProject",
                        data: {id: row.projectId},
                        dataType: 'json',
                        async: false,
                        success: function (data) {
                            if (data != null) {
                                if (data.feeAll * 1 > data.budgetAll * 1) {
                                    projectOverspend = true;
                                    alert('��ʾ������Ŀ���ѳ�֧���ݲ������ύ������������ϵ��Ŀ������������ĿԤ�㡣');
                                }
                            } else {
                                projectOverspend = true;
                                alert('��ʾ����Ŀƥ����������³���');
                            }
                        }
                    });
                    // �����֧������ʧ��
                    if (projectOverspend) {
                        return false;
                    }

                    $.ajax({
                        type: "POST",
                        url: "?model=engineering_project_esmproject&action=getRangeId",
                        data: {'projectId': row.projectId},
                        async: false,
                        success: function (data) {
                            rangeId = '&billArea=' + data;
                        }
                    });
                }

                // alert("�ύ����ǰ��Ҫ����Ƿ�����밢�������ص��ķ���������ĵȴ�, лл!");
                // ���������ڵİ��������Ƿ��Ѵ��ڶ�Ӧ�ķ�����
                var chkResult = $.ajax({
                    url : 'index1.php?model=finance_expense_expense&action=checkAliTripCostRecord',
                    data: {
                        type : 'byBillNo',
                        billNo : (row)? row.BillNo : ''
                    },
                    type : "POST",
                    async : false
                }).responseText;
                var result = eval("("+chkResult+")");
                if(result.result.length > 0){
                    console.log(result);
                    if(!confirm("�����������ڣ������밢�������ص��ķ������˲��Ƿ�����ύ��")){
                        return false;
                    }
                }

                if (row.isLate == "1") {
                    showThickboxWin('controller/finance/expense/ewf_indexlate.php?actTo=ewfSelect&billId='
                        + row.id + '&flowMoney=' + row.Amount
                        + '&billDept=' + row.CostBelongDeptId
                        + '&billCompany=' + row.CostBelongComId
                        + rangeId
                        + "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=750");
                } else {
                    showThickboxWin('controller/finance/expense/ewf_index.php?actTo=ewfSelect&billId='
                        + row.id + '&flowMoney=' + row.Amount
                        + '&billDept=' + row.CostBelongDeptId
                        + '&billCompany=' + row.CostBelongComId
                        + rangeId
                        + "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=750");
                }
            }
        }, {
            name: 'cancel',
            text: '��������',
            icon: 'edit',
            showMenuFn: function (row) {
                // ��һ������������ʾ������ť
                var result = $.ajax({
                    type: 'POST',
                    url: "index1.php?model=common_approvalView&action=checkHasAppAction",
                    data: {
                        pid: row.id,
                        itemtype: 'cost_summary_list'
                    },
                    async: false
                }).responseText;

                var hasAppAction = (result == 0) ? 0 : parseInt(result);

                if ((row.isNew == '1' && row.ExaStatus == '��������' && row.needExpenseCheck == '0') && hasAppAction <= 0) {
                    return 1;
                } else {
                    return 0;
                }
            },
            action: function (row) {
                if (row) {
                    var ewfurl = row.isLate == "1" ?
                        'controller/finance/expense/ewf_indexlate.php?actTo=delWork&billId=' :
                        'controller/finance/expense/ewf_index.php?actTo=delWork&billId=';
                    $.ajax({
                        type: "POST",
                        url: "?model=common_workflow_workflow&action=isAudited",
                        data: {
                            billId: row.id,
                            examCode: 'cost_summary_list'
                        },
                        success: function (msg) {
                            if (msg == '1') {
                                alert('�����Ѿ�����������Ϣ�����ܳ���������');
                                show_page();
                                return false;
                            } else {
                                if (confirm('ȷ��Ҫ����������')) {
                                    $.ajax({
                                        type: "GET",
                                        url: ewfurl,
                                        data: {"billId": row.id, "isLate": row.isLate},
                                        async: false,
                                        success: function (data) {
                                            //�����������ȥ����Ԥ����е��Ѿ���
                                            $.ajax({
                                                type: "GET",
                                                url: '?model=finance_budget_budget&action=subFinal',
                                                data: {"billId": row.id},
                                                async: false,
                                                success: function (dataTwo) {
                                                    alert(data);
                                                    show_page();
                                                }
                                            })
                                        }
                                    });
                                }
                            }
                        }
                    });
                } else {
                    alert("��ѡ��һ������");
                }
            }
        }, {
            text: "ɾ��",
            icon: 'delete',
            showMenuFn: function (row) {
                return row.isNew == '1' && (row.Status == '�༭' || row.Status == '���');
            },
            action: function (row) {
                if (window.confirm(("ȷ��Ҫɾ��?"))) {
                    $.ajax({
                        type: "POST",
                        url: "?model=finance_expense_expense&action=ajaxdeletes",
                        data: {
                            id: row.id
                        },
                        success: function (msg) {
                            if (msg == '1') {
                                alert('ɾ���ɹ���');
                                show_page(1);
                            } else {
                                alert("ɾ��ʧ��! ");
                                show_page(1);
                            }
                        }
                    });
                }
            }
        }, {
            text: "��",
            icon: 'print',
            showMenuFn: function (row) {
                if (row.isNew == "1") {
                    if (row.ExaStatus != "�༭" && row.ExaStatus != '���') {
                        return true;
                    }
                } else {
                    if (row.Status != '�༭' && row.Status != '���ż��') {
                        return true;
                    }
                }
                return false;
            },
            action: function (row) {
                showOpenWin("?model=cost_bill_billcheck&action=print_bill&billno=" + row.BillNo, 1);
            }
        }],
        //��������
        comboEx: [{
            text: '��Ŀʡ��',
            key: 'proProvinceSearch',
            data: provinceOpts
        },{
            text: '��������',
            key: 'DetailType',
            data: [{
                text: '���ŷ���',
                value: '1'
            }, {
                text: '��ͬ��Ŀ����',
                value: '2'
            }, {
                text: '�з�����',
                value: '3'
            }, {
                text: '��ǰ����',
                value: '4'
            }, {
                text: '�ۺ����',
                value: '5'
            }, {
                text: '�ɱ�����',
                value: '0'
            }]
        }, {
            text: '����״̬',
            key: 'Status',
            data: [{
                text: '�༭',
                value: '�༭'
            }, {
                text: '���ż��',
                value: '���ż��'
            }, {
                text: '�ȴ�ȷ��',
                value: '�ȴ�ȷ��'
            }, {
                text: '��������',
                value: '��������'
            }, {
                text: '�������',
                value: '�������'
            }, {
                text: '���ɸ���',
                value: '���ɸ���'
            }, {
                text: '���',
                value: '���'
            }, {
                text: '���',
                value: '���'
            }]
        }],
        searchitems: [{
            display: "��������",
            name: 'BillNoSearch'
        }, {
            display: "��Ŀ����",
            name: 'projectNameSearch'
        }, {
            display: "��Ŀ���",
            name: 'projectCodeSearch'
        }, {
            display: "�̻����",
            name: 'chanceCodeSearch'
        }, {
            display: "�̻�����",
            name: 'chanceNameSearch'
        }, {
            display: "��ͬ���",
            name: 'contractCodeSearch'
        }, {
            display: "��ͬ����",
            name: 'contractNameSearch'
        }, {
            display: "�������",
            name: 'Amount'
        }, {
            display: "������",
            name: 'CostManNameSearch'
        }, {
            display: "����",
            name: 'PurposeSearch'
        }],
        sortname: "c.UpdateDT"
    });
});