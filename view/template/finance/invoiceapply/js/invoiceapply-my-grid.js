var show_page = function () {
    $("#invoiceapplyMyGrid").yxgrid("reload");
};

$(function () {
    $("#invoiceapplyMyGrid").yxgrid({
        model: 'finance_invoiceapply_invoiceapply',
        action: 'myPageJson',
        title: '我的开票申请',
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
                display: '申请单号',
                name: 'applyNo',
                sortable: true,
                width: 135,
                process: function (v, row) {
                    if (row.isOffSite == '1') {
                        return "<span class='green' title='异地开票申请'>" + v + "</span>";
                    } else {
                        return v;
                    }
                }
            },
            {
                display: '业务编号',
                name: 'objCode',
                width: 140
            },
            {
                display: '业务类型',
                name: 'objTypeName',
                sortable: true,
                width: 70
            },
            {
                display: '客户单位',
                name: 'customerName',
                width: 150
            },
            {
                display: '归属公司',
                name: 'businessBelongName',
                width: 70
            },
            {
                display: '申请人',
                sortable: true,
                name: 'createName',
                width: 90,
                hide: true
            },
            {
                display: '申请类型',
                name: 'invoiceTypeName',
                sortable: true,
                width: 80
            },
            {
                display : '申请币别',
                name: 'currency',
                sortable: true,
                width: 60,
                process: function (v) {
                    return v == '人民币' ? v : '<span class="red">'+ v +'</span>';
                }
            },
            {
                display: '申请金额',
                name: 'invoiceMoney',
                sortable: true,
                process: function (v) {
                    return moneyFormat2(v);
                },
                width: 90
            },
            {
                display: '已开金额',
                name: 'payedAmount',
                sortable: true,
                process: function (v) {
                    return moneyFormat2(v);
                },
                width: 90
            },
            {
                display: '合同金额',
                name: 'contAmount',
                sortable: true,
                process: function (v) {
                    return moneyFormat2(v);
                },
                width: 90
            },
            {
                display: '审批状态',
                sortable: true,
                name: 'ExaStatus',
                width: 60
            },
            {
                display: '申请时间',
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

        //过滤数据
        comboEx: [
            {
                text: '审批状态',
                key: 'ExaStatus',
                type: 'workFlow'
            }

        ],
        //扩展右键菜单
        menusEx: [
            {
                text: '查看',
                icon: 'view',
                action: function (row, rows, grid) {
                    showModalWin('?model=finance_invoiceapply_invoiceapply&action=init'
                        + '&id=' + row.id + '&skey=' + row['skey_']
                        + '&perm=view', 1, row.id);
                }
            },
            {
                text: '修改',
                icon: 'edit',
                showMenuFn: function (row) {
                    if (row.ExaStatus == '待提交') {
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
                text: '提交审批',
                icon: 'add',
                showMenuFn: function (row) {
                    if (row.ExaStatus == '待提交') {
                        return true;
                    }
                    return false;
                },
                action: function (row, rows, grid) {
                    if (row.isOffSite == '1') {
                        showThickboxWin('controller/finance/invoiceapply/ewf_index.php?actTo=ewfSelect&billId='
                            + row.id + "&formName=异地开票申请"
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
                text: '删除',
                icon: 'delete',
                showMenuFn: function (row) {
                    if (row.ExaStatus == '待提交') {
                        return true;
                    }
                    return false;
                },
                action: function (row, rows, grid) {
                    if (window.confirm(("确定要删除?"))) {
                        $.ajax({
                            type: "POST",
                            url: "?model=finance_invoiceapply_invoiceapply&action=ajaxdeletes",
                            data: {
                                id: row.id
                            },
                            success: function (msg) {
                                if (msg == 1) {
                                    alert('删除成功！');
                                    $("#invoiceapplyMyGrid").yxgrid("reload");
                                }
                            }
                        });
                    }
                }
            },
            {
                text: '查看开票记录',
                icon: 'view',
                showMenuFn: function (row) {
                    if (row.ExaStatus == '完成') {
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
                text: '审批情况',
                icon: 'view',
                showMenuFn: function (row) {
                    if (row.ExaStatus != '待提交') {
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
                text: '打印',
                icon: 'print',
                action: function (row) {
                    showModalWin('?model=finance_invoiceapply_invoiceapply&action=printInvoiceApply'
                        + '&id=' + row.id + '&skey=' + row['skey_']);
                }
            },
            {
                text: '撤销审批',
                icon: 'edit',
                showMenuFn: function (row) {
                    if (row.ExaStatus == '部门审批') {
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
                                    alert('单据已经存在审批信息，不能撤销审批！');
                                    show_page();
                                    return false;
                                } else {
                                    if (confirm('确定要撤消审批吗？')) {
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
                        alert("请选中一条数据");
                    }
                }
            }
        ],
        // 快速搜索
        searchitems: [
            {
                display: '客户单位',
                name: 'customerNameSearch'
            },
            {
                display: '源单编号',
                name: 'objCodeSearch'
            },
            {
                display: '业务编号',
                name: 'rObjCodeSearch'
            },
            {
                display: '申请人',
                name: 'createName'
            },
            {
                display: '开票申请单号',
                name: 'applyNo'
            },
            {
                display: '申请日期',
                name: 'applyDateSearch'
            }
        ],
        sortname: 'c.updateTime'
    });
});