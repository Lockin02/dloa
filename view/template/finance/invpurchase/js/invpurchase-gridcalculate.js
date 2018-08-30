var show_page = function () {
    $("#invpurchaseGrid").yxsubgrid("reload");
};

function hasUncheck() {
    var hasUnCheck = $.ajax({
        type: "POST",
        url: "?model=stock_instock_stockin&action=isExsitWsh",
        async: false,
        data: {
            "year": $("#thisYear").val(),
            "month": $("#thisMonth").val()
        }
    }).responseText;

    return hasUnCheck;
}
$(function () {
    $("#invpurchaseGrid").yxsubgrid({
        model: 'finance_invpurchase_invpurchase',
        title: '�ɹ���Ʊ',
        param: {"status": 1, "formDateYear": $("#thisYear").val(), "formDateMonth": $("#thisMonth").val()},
        action: 'pageJsonCacu',
        isEditAction: false,
        isAddAction: false,
        isDelAction: false,
        isViewAction: false,
        showcheckbox: false,
        isShowNum: false,
        //����Ϣ
        colModel: [{
            display: 'id',
            name: 'id',
            sortable: true,
            hide: true,
            process: function (v, row) {
                return v + "<input type='hidden' id='isBreak" + row.id + "' value='unde'>";
            }
        },
            {
                name: 'objCode',
                display: '���ݱ��',
                sortable: true,
                width: 130,
                process: function (v, row) {
                    if (row.formType == "blue") {
                        return v;
                    } else {
                        return "<span class='red'>" + v + "</span>";
                    }
                }
            },
            {
                name: 'objNo',
                display: '��Ʊ����',
                sortable: true
            },
            {
                name: 'supplierName',
                display: '��Ӧ������',
                sortable: true,
                width: 150
            },
            {
                name: 'invType',
                display: '��Ʊ����',
                sortable: true,
                width: 80,
                datacode: 'FPLX'
            },
            {
                name: 'taxRate',
                display: '˰��(%)',
                sortable: true,
                width: 60
            },
            {
                name: 'formAssessment',
                display: '����˰��',
                sortable: true,
                process: function (v) {
                    return moneyFormat2(v);
                },
                width: 80
            },
            {
                name: 'amount',
                display: '�ܽ��',
                sortable: true,
                process: function (v) {
                    return moneyFormat2(v);
                },
                width: 80
            },
            {
                name: 'formCount',
                display: '��˰�ϼ�',
                sortable: true,
                process: function (v) {
                    return moneyFormat2(v);
                },
                width: 80
            },
            {
                name: 'formDate',
                display: '��������',
                sortable: true,
                width: 80
            },
            {
                name: 'payDate',
                display: '��������',
                sortable: true,
                width: 80
            }, {
                name: 'purcontCode',
                display: '�ɹ��������',
                width: 130,
                hide: true
            },
            {
                name: 'departments',
                display: '����',
                sortable: true,
                width: 80
            },
            {
                name: 'salesman',
                display: 'ҵ��Ա',
                sortable: true,
                width: 80
            },
            {
                name: 'ExaStatus',
                display: '���״̬',
                sortable: true,
                width: 60,
                process: function (v) {
                    if (v == 1) {
                        return '�����';
                    } else {
                        return 'δ���';
                    }
                }
            },
            {
                name: 'exaMan',
                display: '�����',
                sortable: true,
                width: 80
            },
            {
                name: 'ExaDT',
                display: '�������',
                sortable: true,
                width: 80
            },
            {
                name: 'status',
                display: '����״̬',
                sortable: true,
                width: 60,
                process: function (v) {
                    if (v == 1) {
                        return '�ѹ���';
                    } else {
                        return 'δ����';
                    }
                }
            }, {
                name: 'createName',
                display: '������',
                width: 90,
                hide: true
            },
            {
                name: 'belongId',
                display: '����ԭ��Ʊid',
                hide: true
            }
        ],

        // ���ӱ������
        subGridOptions: {
            url: '?model=finance_invpurchase_invpurdetail&action=pageJson',// ��ȡ�ӱ�����url
            // ���ݵ���̨�Ĳ�����������
            param: [
                {
                    paramId: 'invPurId',// ���ݸ���̨�Ĳ�������
                    colId: 'id'// ��ȡ���������ݵ�������
                }
            ],
            // ��ʾ����
            colModel: [{
                name: 'productNo',
                display: '���ϱ��',
                width: 80
            }, {
                name: 'productName',
                display: '��������',
                width: 140
            }, {
                name: 'productModel',
                display: '����ͺ�'
            }, {
                name: 'unit',
                display: '��λ',
                width: 60
            }, {
                name: 'number',
                display: '����',
                width: 60
            }, {
                name: 'price',
                display: '����',
                process: function (v) {
                    return moneyFormat2(v);
                },
                width: 80
            }, {
                name: 'taxPrice',
                display: '��˰����',
                process: function (v) {
                    return moneyFormat2(v);
                },
                width: 80
            }, {
                name: 'assessment',
                display: '˰��',
                process: function (v) {
                    return moneyFormat2(v);
                },
                width: 80
            }, {
                name: 'amount',
                display: '���',
                process: function (v) {
                    return moneyFormat2(v);
                },
                width: 80
            }, {
                name: 'allCount',
                display: '��˰�ϼ�',
                process: function (v) {
                    return moneyFormat2(v);
                },
                width: 80
            }, {
                name: 'objCode',
                display: '�������',
                width: 120
            }
            ]
        },
        toAddConfig: {
            toAddFn: function (p) {
                showOpenWin("?model=finance_invpurchase_invpurchase&action=toAdd");
            }
        },
        menusEx: [
            {
                text: "�鿴",
                icon: 'view',
                action: function (row) {
                    showOpenWin('?model=finance_invpurchase_invpurchase&action=init&perm=view&id=' + row.id + "&skey=" + row.skey_);
                }
            },
            {
                text: "������־",
                icon: 'view',
                action: function (row) {
                    showOpenWin('?model=finance_related_baseinfo&action=toUnhook&invPurId=' + row.id);
                }
            }
        ],
        buttonsEx: [
            {
                text: "����",
                icon: 'edit',
                action: function (row) {
                    if (hasUncheck() == 0) {
                        alert('����δ��˵���ⵥ��,���ֶ���ⵥ�������');
                        return false;
                    } else {
                        if (confirm("ȷ��Ҫ���к�����?")) {
                            $.ajax({
                                type: "POST",
                                url: "?model=finance_stockbalance_stockbalance&action=calculate",
                                data: {
                                    "thisYear": $("#thisYear").val(),
                                    "thisMonth": $("#thisMonth").val()
                                },
                                success: function (msg) {
                                    if (msg == 1) {
                                        alert('����ɹ���');
                                        show_page(1);
                                    } else {
                                        alert("����ʧ��! ");
                                    }
                                }
                            });
                        }
                    }
                }
            }
        ],
        searchitems: [
            {
                display: '��Ʊ����',
                name: 'objNo'
            },
            {
                display: '��Ӧ������',
                name: 'supplierName'
            },
            {
                display: '���ݱ��',
                name: 'objCodeSearch'
            },
            {
                display: 'Դ�����',
                name: 'objCodeSearchDetail'
            },
            {
                display: '�ɹ��������',
                name: 'contractCodeSearch'
            }
        ],
        sortname: 'updateTime'
    });
});