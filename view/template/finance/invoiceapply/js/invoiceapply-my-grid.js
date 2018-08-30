var show_page = function () {
    $("#invoiceapplyMyGrid").yxgrid("reload");
};

$(function () {
    $("#invoiceapplyMyGrid").yxgrid({
        model: 'finance_invoiceapply_invoiceapply',
        action: 'myPageJson',
        title: '�ҵĿ�Ʊ����',
        isEditAction: false,
        isAddAction: ($("#addLimit").val() == "y"),
        isViewAction: false,
        isDelAction: false,
        showcheckbox: false,
        colModel: [
            {
                display: 'id',
                name: 'id',
                sortable: true,
                hide: true
            },
            {
                display: '���뵥��',
                name: 'applyNo',
                sortable: true,
                width: 135,
                process: function (v, row) {
                    if (row.isOffSite == '1') {
                        return "<span class='green' title='��ؿ�Ʊ����'>" + v + "</span>";
                    } else {
                        return v;
                    }
                }
            },
            {
                display: 'ҵ����',
                name: 'objCode',
                width: 140
            },
            {
                display: 'ҵ������',
                name: 'objTypeName',
                sortable: true,
                width: 70
            },
            {
                display: '�ͻ���λ',
                name: 'customerName',
                width: 150
            },
            {
                display: '������˾',
                name: 'businessBelongName',
                width: 70
            },
            {
                display: '������',
                sortable: true,
                name: 'createName',
                width: 90,
                hide: true
            },
            {
                display: '��������',
                name: 'invoiceTypeName',
                sortable: true,
                width: 80
            },
            {
                display : '����ұ�',
                name: 'currency',
                sortable: true,
                width: 60,
                process: function (v) {
                    return v == '�����' ? v : '<span class="red">'+ v +'</span>';
                }
            },
            {
                display: '������',
                name: 'invoiceMoney',
                sortable: true,
                process: function (v) {
                    return moneyFormat2(v);
                },
                width: 90
            },
            {
                display: '�ѿ����',
                name: 'payedAmount',
                sortable: true,
                process: function (v) {
                    return moneyFormat2(v);
                },
                width: 90
            },
            {
                display: '��ͬ���',
                name: 'contAmount',
                sortable: true,
                process: function (v) {
                    return moneyFormat2(v);
                },
                width: 90
            },
            {
                display: '����״̬',
                sortable: true,
                name: 'ExaStatus',
                width: 60
            },
            {
                display: '����ʱ��',
                sortable: true,
                name: 'applyDate',
                width: 80
            }
        ],
        toAddConfig: {
            toAddFn: function (p) {
                showModalWin("?model=finance_invoiceapply_invoiceapply&action=toAddIndep");
            }
        },

        //��������
        comboEx: [
            {
                text: '����״̬',
                key: 'ExaStatus',
                type: 'workFlow'
            }

        ],
        //��չ�Ҽ��˵�
        menusEx: [
            {
                text: '�鿴',
                icon: 'view',
                action: function (row, rows, grid) {
                    showModalWin('?model=finance_invoiceapply_invoiceapply&action=init'
                        + '&id=' + row.id + '&skey=' + row['skey_']
                        + '&perm=view', 1, row.id);
                }
            },
            {
                text: '�޸�',
                icon: 'edit',
                showMenuFn: function (row) {
                    if (row.ExaStatus == '���ύ') {
                        return true;
                    }
                    return false;
                },
                action: function (row, rows, grid) {
                    showModalWin('?model=finance_invoiceapply_invoiceapply&action=init'
                        + '&id=' + row.id + '&skey=' + row['skey_'], 1, row.id
                    );
                }
            },
            {
                text: '�ύ����',
                icon: 'add',
                showMenuFn: function (row) {
                    if (row.ExaStatus == '���ύ') {
                        return true;
                    }
                    return false;
                },
                action: function (row, rows, grid) {
                    if (row.isOffSite == '1') {
                        showThickboxWin('controller/finance/invoiceapply/ewf_index.php?actTo=ewfSelect&billId='
                            + row.id + "&formName=��ؿ�Ʊ����"
                            + '&placeValuesBefore&TB_iframe=true&modal=false&height=400&width=600');
                    } else {
                        showThickboxWin('controller/finance/invoiceapply/ewf_index.php?actTo=ewfSelect&billId='
                            + row.id
                            + '&placeValuesBefore&TB_iframe=true&modal=false&height=400&width=600');
                    }
                }
            }
            ,
            {
                text: 'ɾ��',
                icon: 'delete',
                showMenuFn: function (row) {
                    if (row.ExaStatus == '���ύ') {
                        return true;
                    }
                    return false;
                },
                action: function (row, rows, grid) {
                    if (window.confirm(("ȷ��Ҫɾ��?"))) {
                        $.ajax({
                            type: "POST",
                            url: "?model=finance_invoiceapply_invoiceapply&action=ajaxdeletes",
                            data: {
                                id: row.id
                            },
                            success: function (msg) {
                                if (msg == 1) {
                                    alert('ɾ���ɹ���');
                                    $("#invoiceapplyMyGrid").yxgrid("reload");
                                }
                            }
                        });
                    }
                }
            },
            {
                text: '�鿴��Ʊ��¼',
                icon: 'view',
                showMenuFn: function (row) {
                    if (row.ExaStatus == '���') {
                        return true;
                    }
                    return false;
                },
                action: function (row, rows, grid) {
                    showOpenWin('?model=finance_invoice_invoice&action=pageByInvoiceapply&applyId=' + row.id
                        + '&applyNo=' + row.applyNo);
                }
            },
            {
                text: '�������',
                icon: 'view',
                showMenuFn: function (row) {
                    if (row.ExaStatus != '���ύ') {
                        return true;
                    }
                    return false;
                },
                action: function (row) {
                    showThickboxWin('controller/common/readview.php?itemtype=oa_finance_invoiceapply&pid='
                        + row.id
                        + '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=600');
                }
            },
            {
                text: '��ӡ',
                icon: 'print',
                action: function (row) {
                    showModalWin('?model=finance_invoiceapply_invoiceapply&action=printInvoiceApply'
                        + '&id=' + row.id + '&skey=' + row['skey_']);
                }
            },
            {
                text: '��������',
                icon: 'edit',
                showMenuFn: function (row) {
                    if (row.ExaStatus == '��������') {
                        return true;
                    }
                    return false;
                },
                action: function (row, rows, grid) {
                    if (row) {
                        var ewfurl = 'controller/finance/invoiceapply/ewf_index.php?actTo=delWork&billId=';

                        $.ajax({
                            type: "POST",
                            url: "?model=common_workflow_workflow&action=isAudited",
                            data: {
                                billId: row.id,
                                examCode: 'oa_finance_invoiceapply'
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
                                            data: {"billId": row.id },
                                            async: false,
                                            success: function (data) {
                                                alert(data)
                                                show_page();
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
            }
        ],
        // ��������
        searchitems: [
            {
                display: '�ͻ���λ',
                name: 'customerNameSearch'
            },
            {
                display: 'Դ�����',
                name: 'objCodeSearch'
            },
            {
                display: 'ҵ����',
                name: 'rObjCodeSearch'
            },
            {
                display: '������',
                name: 'createName'
            },
            {
                display: '��Ʊ���뵥��',
                name: 'applyNo'
            },
            {
                display: '��������',
                name: 'applyDateSearch'
            }
        ],
        sortname: 'c.updateTime'
    });
});