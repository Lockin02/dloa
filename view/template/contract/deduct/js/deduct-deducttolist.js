var show_page = function (page) {
    $("#deductGrid").yxgrid("reload");
};
$(function () {
    $("#deductGrid").yxgrid({
        model: 'contract_deduct_deduct',
        param: {'contractId': $("#contractId").val()},
        title: '扣款申请',
        isAddAction: false,
        isViewAction: true,
        isEditAction: false,
        isDelAction: false,
        showcheckbox: false,
        isAddAction: false,
        // 扩展右键菜单

        menusEx: [
            {
                text: '提交合同',
                icon: 'add',
                showMenuFn: function (row) {
                    if (row && (row.ExaStatus == '未审批' || row.ExaStatus == '打回')) {
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
                text: '删除',
                icon: 'delete',
                showMenuFn: function (row) {
                    if (row.ExaStatus == '未审批' || row.ExaStatus == '打回') {
                        return true;
                    }
                    return false;
                },
                action: function (row) {
                    if (window.confirm(("确定要删除?"))) {
                        $.ajax({
                            type: "POST",
                            url: "?model=contract_deduct_deduct&action=ajaxdeletes",
                            data: {
                                id: row.id
                            },
                            success: function (msg) {
                                if (msg == 1) {
                                    alert('删除成功！');
                                    $("#deductGrid").yxgrid("reload");
                                }
                            }
                        });
                    }
                }

            },
            {
                text: '审批情况',
                icon: 'view',
                action: function (row) {
                    showThickboxWin('controller/contract/contract/readview.php?itemtype=oa_contract_deduct&pid='
                        + row.id
                        + '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=600');
                }
            }
        ],
        // 列信息
        colModel: [
            {
                display: 'id',
                name: 'id',
                sortable: true,
                hide: true
            },
            {
                name: 'contractName',
                display: '合同名称',
                sortable: true
            },
            {
                name: 'contractCode',
                display: '合同编号',
                sortable: true,
                href: '?model=contract_contract_contract&action=init&perm=view&id=',
                hrefCol: 'contractId'
            },
            {
                name: 'contractMoney',
                display: '合同金额',
                sortable: true
            },
            {
                name: 'deductMoney',
                display: '处理金额',
                sortable: true
            },
            {
                name: 'deductReason',
                display: '处理原因',
                sortable: true,
                width: 280
            },
            {
                name: 'dispose',
                display: '处理方式',
                sortable: true,
                process: function (v) {
                    if (v == 'deductMoney') {
                        return "扣款";
                    } else if (v == 'badMoney') {
                        return "坏账";
                    } else {
                        return "";
                    }
                },
                width: 60
            },
            {
                name: 'ExaStatus',
                display: '审批状态',
                sortable: true
            },
            {
                name: 'state',
                display: '状态',
                sortable: true,
                process: function (v) {
                    if (v == '0') {
                        return "未处理";
                    } else if (v == '1') {
                        return "已处理";
                    }
                },
                width: 60
            },
            {
                name: 'createTime',
                display: '创建时间',
                sortable: true,
                width: 150
            },
            {
                name: 'createName',
                display: '创建人名称',
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
            display: "合同编号",
            name: 'contractCode'
        }
    });
});