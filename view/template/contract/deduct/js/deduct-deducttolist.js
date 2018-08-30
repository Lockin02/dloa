var show_page = function (page) {
    $("#deductGrid").yxgrid("reload");
};
$(function () {
    $("#deductGrid").yxgrid({
        model: 'contract_deduct_deduct',
        param: {'contractId': $("#contractId").val()},
        title: '�ۿ�����',
        isAddAction: false,
        isViewAction: true,
        isEditAction: false,
        isDelAction: false,
        showcheckbox: false,
        isAddAction: false,
        // ��չ�Ҽ��˵�

        menusEx: [
            {
                text: '�ύ��ͬ',
                icon: 'add',
                showMenuFn: function (row) {
                    if (row && (row.ExaStatus == 'δ����' || row.ExaStatus == '���')) {
                        return true;
                    }
                    return false;
                },
                action: function (row, rows, grid) {
                    showThickboxWin('controller/contract/deduct/ewf_index.php?actTo=ewfSelect&billId='
                        + row.id
                        + "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=750");
                }
            },
            {
                text: 'ɾ��',
                icon: 'delete',
                showMenuFn: function (row) {
                    if (row.ExaStatus == 'δ����' || row.ExaStatus == '���') {
                        return true;
                    }
                    return false;
                },
                action: function (row) {
                    if (window.confirm(("ȷ��Ҫɾ��?"))) {
                        $.ajax({
                            type: "POST",
                            url: "?model=contract_deduct_deduct&action=ajaxdeletes",
                            data: {
                                id: row.id
                            },
                            success: function (msg) {
                                if (msg == 1) {
                                    alert('ɾ���ɹ���');
                                    $("#deductGrid").yxgrid("reload");
                                }
                            }
                        });
                    }
                }

            },
            {
                text: '�������',
                icon: 'view',
                action: function (row) {
                    showThickboxWin('controller/contract/contract/readview.php?itemtype=oa_contract_deduct&pid='
                        + row.id
                        + '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=600');
                }
            }
        ],
        // ����Ϣ
        colModel: [
            {
                display: 'id',
                name: 'id',
                sortable: true,
                hide: true
            },
            {
                name: 'contractName',
                display: '��ͬ����',
                sortable: true
            },
            {
                name: 'contractCode',
                display: '��ͬ���',
                sortable: true,
                href: '?model=contract_contract_contract&action=init&perm=view&id=',
                hrefCol: 'contractId'
            },
            {
                name: 'contractMoney',
                display: '��ͬ���',
                sortable: true
            },
            {
                name: 'deductMoney',
                display: '������',
                sortable: true
            },
            {
                name: 'deductReason',
                display: '����ԭ��',
                sortable: true,
                width: 280
            },
            {
                name: 'dispose',
                display: '����ʽ',
                sortable: true,
                process: function (v) {
                    if (v == 'deductMoney') {
                        return "�ۿ�";
                    } else if (v == 'badMoney') {
                        return "����";
                    } else {
                        return "";
                    }
                },
                width: 60
            },
            {
                name: 'ExaStatus',
                display: '����״̬',
                sortable: true
            },
            {
                name: 'state',
                display: '״̬',
                sortable: true,
                process: function (v) {
                    if (v == '0') {
                        return "δ����";
                    } else if (v == '1') {
                        return "�Ѵ���";
                    }
                },
                width: 60
            },
            {
                name: 'createTime',
                display: '����ʱ��',
                sortable: true,
                width: 150
            },
            {
                name: 'createName',
                display: '����������',
                sortable: true
            }
        ],

        toEditConfig: {
            action: 'toEdit'
        },
        toViewConfig: {
            action: 'toView'
        },
        searchitems: {
            display: "��ͬ���",
            name: 'contractCode'
        }
    });
});