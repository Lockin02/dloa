var show_page = function () {
    $("#allocationGrid").yxsubgrid("reload");
};
$(function () {
    $("#allocationGrid").yxsubgrid({
        model: 'stock_allocation_allocation',
        action: 'pageListGridJson',
        title: '调拨单管理',
        isAddAction: true,
        isViewAction: false,
        isEditAction: false,
        isDelAction: false,
        showcheckbox: false,
        // 列信息
        colModel: [
            {
                display: 'id',
                name: 'id',
                sortable: true,
                hide: true
            },
            {
                name: 'docCode',
                display: '单据编号',
                sortable: true
            },
            {
                name: 'auditDate',
                display: '单据日期',
                sortable: true
            },
            {
                name: 'toUse',
                display: '出库用途',
                sortable: true,
                datacode: 'CHUKUYT',
                width: 70
            },
            {
                name: 'docType',
                display: '调拨类型',
                sortable: true,
                hide: true
            },
            {
                name: 'relDocId',
                display: '源单id',
                sortable: true,
                hide: true
            },
            {
                name: 'relDocCode',
                display: '源单编号',
                sortable: true,
                hide: true
            },
            {
                name: 'relDocName',
                display: '源单名称',
                sortable: true,
                hide: true
            },
            {
                name: 'relDocType',
                display: '源单类型',
                sortable: true,
                hide: true
            },
            {
                name: 'contractId',
                display: '合同id',
                sortable: true,
                hide: true
            },
            {
                name: 'contractName',
                display: '合同名称',
                sortable: true,
                hide: true
            },
            {
                name: 'contractCode',
                display: '申请单编号',
                sortable: true,
                hide: true
            },
            {
                name: 'outStartDate',
                display: '借出开始日期',
                sortable: true
            },
            {
                name: 'outEndDate',
                display: '借出结束日期',
                sortable: true

            },
            {
                name: 'customerName',
                display: '客户(单位)名称',
                sortable: true
            },
            {
                name: 'customerId',
                display: '客户(单位)id',
                sortable: true,
                hide: true
            },
            {
                name: 'linkmanId',
                display: '客户联系人id',
                sortable: true,
                hide: true
            },
            {
                name: 'linkmanName',
                display: '客户联系人',
                sortable: true
            },
            {
                name: 'exportStockId',
                display: '调出仓库id',
                sortable: true,
                hide: true
            },
            {
                name: 'exportStockCode',
                display: '调出仓库代码',
                sortable: true,
                hide: true
            },
            {
                name: 'exportStockName',
                display: '调出仓库名称',
                sortable: true
            },
            {
                name: 'importStockId',
                display: '调入仓库id',
                sortable: true,
                hide: true
            },
            {
                name: 'importStockCode',
                display: '调入仓库代码',
                sortable: true,
                hide: true
            },
            {
                name: 'importStockName',
                display: '调入仓库名称',
                sortable: true
            },
            {
                name: 'deptName',
                display: '部门',
                sortable: true,
                hide: true
            },
            {
                name: 'deptCode',
                display: '部门编码',
                sortable: true,
                hide: true
            },
            {
                name: 'pickName',
                display: '领料人(职员)名称',
                sortable: true,
                hide: true
            },
            {
                name: 'docStatus',
                display: '单据状态',
                sortable: true,
                process: function (v, row) {
                    if (v == "WSH") {
                        return "未审核";
                    } else {
                        return "已审核";
                    }
                },
                width: 50
            },
            {
                name: 'remark',
                display: '备注',
                sortable: true,
                hide: true

            },
            {
                name: 'pickCode',
                display: '领料人(职员)编码',
                sortable: true,
                hide: true

            },
            {
                name: 'auditerCode',
                display: '审核人编号',
                sortable: true,
                hide: true
            },
            {
                name: 'custosCode',
                display: '保管人编号',
                sortable: true,
                hide: true
            },
            {
                name: 'custosName',
                display: '保管人名称',
                sortable: true,
                hide: true
            },
            {
                name: 'chargeCode',
                display: '负责人编号',
                sortable: true,
                hide: true
            },
            {
                name: 'chargeName',
                display: '负责人名称',
                sortable: true,
                hide: true
            },
            {
                name: 'acceptorCode',
                display: '验收人编号',
                sortable: true,
                hide: true
            },
            {
                name: 'acceptorName',
                display: '验收人名称',
                sortable: true,
                hide: true
            },
            {
                name: 'accounterCode',
                display: '记账人编号',
                sortable: true,
                hide: true
            },
            {
                name: 'accounterName',
                display: '记账人名称',
                sortable: true,
                hide: true
            },
            {
                name: 'updateId',
                display: '修改人',
                sortable: true,
                hide: true
            },
            {
                name: 'updateTime',
                display: '修改时间',
                sortable: true,
                hide: true
            },
            {
                name: 'updateName',
                display: '修改人名称',
                sortable: true,
                hide: true
            },
            {
                name: 'createId',
                display: '创建人',
                sortable: true,
                hide: true
            },
            {
                name: 'createName',
                display: '制单',
                sortable: true
            },
            {
                name: 'createTime',
                display: '创建时间',
                sortable: true,
                hide: true
            },
            {
                name: 'auditerName',
                display: '审核人',
                sortable: true
            }
        ],
        // 主从表格设置
        //主从表中加了个字段   规格型号   2013.7.5
        subGridOptions: {
            url: '?model=stock_allocation_allocationitem&action=pageJson',
            param: [
                {
                    paramId: 'mainId',
                    colId: 'id'
                }
            ],
            colModel: [
                {
                    name: 'productCode',
                    display: '物料编码'
                },
                {
                    name: 'k3Code',
                    display: 'K3编码'
                },
                {
                    name: 'productName',
                    width: 200,
                    display: '物料名称'
                },
                {
                    name: 'pattern',
                    width: 200,
                    display: '规格型号'
                },
                {
                    name: 'allocatNum',
                    display: "调拨数量"
                },
                {
                    name: 'serialnoName',
                    display: "序列号",
                    width: '500'
                }
            ]
        },
        toViewConfig: {
            formWidth: 1170,
            formHeight: 500
        },
        comboEx: [
            {
                text: '单据状态',
                key: 'docStatus',
                data: [
                    {
                        text: '未审核',
                        value: 'WSH'
                    },
                    {
                        text: '已审核',
                        value: 'YSH'
                    }
                ]
            }
        ],
        buttonsEx: [
            {
                name: 'advancedsearch',
                text: "高级搜索",
                icon: 'search',
                action: function (row) {
                    showThickboxWin("?model=stock_allocation_allocation&action=toAdvancedSearch"
                        + "&placeValuesBefore&TB_iframe=true&modal=false&height=300&width=600")
                }
            },
            {
                name: 'export',
                text: "导出未归还物料",
                icon: 'excel',
                action: function (row) {
                    window
                        .open(
                            "?model=stock_allocation_allocation&action=exportExcel",
                            "", "width=200,height=200,top=200,left=200");
                }
            }
        ],
        searchitems: [
            {
                display: "序列号",
                name: 'serialnoName'
            },
            {
                display: '单据编号',
                name: 'docCode'
            },
            {
                name: 'customerName',
                display: '客户(单位)名称'
            },
            {
                name: 'pickName',
                display: '职员名称'
            },
            {
                display: '物料编码',
                name: 'productCode'
            },
            {
                display: '物料K3编码',
                name: 'k3Code'
            },
            {
                display: '物料名称',
                name: 'productName'
            },
            {
                display: '物料规格型号',
                name: 'pattern'
            },
            {
                display: '源单编号',
                name: 'relDocCode'
            },
            {
                display: '借试用申请编号',
                name: 'contractCodeLike'
            }
        ],
        toAddConfig: {
            formWidth: 880,
            formHeight: 600,
            toAddFn: function (p) {
                action : showModalWin("?model=stock_allocation_allocation&action=toAdd")
            }
        },
        sortorder: "DESC",
        menusEx: [
            {
                name: 'view',
                text: "查看",
                icon: 'view',
                action: function (row, rows, grid) {
                    showThickboxWin("?model=stock_allocation_allocation&action=toView&id="
                        + row.id
                        + "&skey="
                        + row['skey_']
                        + "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=900");
                }
            },
            {
                name: 'edit',
                text: "修改",
                icon: 'edit',
                showMenuFn: function (row) {
                    return row.docStatus == "WSH";
                },
                action: function (row, rows, grid) {
                    showModalWin("?model=stock_allocation_allocation&action=toEdit&id="
                        + row.id + "&skey=" + row['skey_']);
                }
            },
            {
                name: 'copy',
                text: "复制",
                icon: 'copy',
                showMenuFn: function (row) {
                    return row.docStatus == "YSH";
                },
                action: function (row, rows, grid) {
                    showModalWin("?model=stock_allocation_allocation&action=toCopy&id="
                        + row.id + "&skey=" + row['skey_']);
                }
            },
            {
                name: 'unlock',
                text: "反审核",
                icon: 'unlock',
                showMenuFn: function (row) {
                    if (row.docStatus == "YSH") {
                        var cancelAudit = false;
                        $.ajax({
                            type: "POST",
                            async: false,
                            url: "?model=stock_allocation_allocation&action=cancelAuditLimit",
                            data: {},
                            success: function (result) {
                                if (result == 1)
                                    cancelAudit = true;
                            }
                        })
                        return cancelAudit;
                    } else {
                        return false;
                    }

                },
                action: function (row, rows, grid) {
                    var canAudit = true;// 是否已结账
                    var closedAudit = true;// 是否已关账
                    $.ajax({
                        type: "POST",
                        async: false,
                        url: "?model=finance_period_period&action=isLaterPeriod",
                        data: {
                            thisDate: row.auditDate
                        },
                        success: function (result) {
                            if (result == 0)
                                canAudit = false;
                        }
                    });
                    $.ajax({
                        type: "POST",
                        async: false,
                        url: "?model=finance_period_period&action=isClosed",
                        data: {},
                        success: function (result) {
                            if (result == 1)
                                closedAudit = false;
                        }
                    })
                    if (closedAudit) {
                        if (canAudit) {
                            if (window.confirm("确认进行反审核吗?")) {
                                $.ajax({
                                    type: "POST",
                                    url: "?model=stock_allocation_allocation&action=cancelAudit",
                                    data: {
                                        id: row.id
                                    },
                                    success: function (result) {
                                        show_page();
                                        if (result == 1) {
                                            alert('单据反审核成功！');
                                        } else {
                                            alert(result);
                                        }
                                    }
                                });

                            }
                        } else {
                            alert("单据所在财务周期已经结账，不能进行反审核，请联系财务人员进行反结账！")
                        }
                    } else {
                        alert("财务已关账，不能进行反审核，请联系财务人员进行反关账！")
                    }
                }
            },
            {
                name: 'view',
                text: "操作日志",
                icon: 'view',
                action: function (row) {
                    showThickboxWin("?model=syslog_operation_logoperation&action=businessView&pkValue="
                        + row.id
                        + "&tableName=oa_stock_allocation"
                        + "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=850");
                }
            },
            {
                name: 'view',
                text: "打印",
                icon: 'print',
                action: function (row, rows, grid) {
                    showThickboxWin("?model=stock_allocation_allocation&action=toPrint&id="
                        + row.id
                        + "&skey="
                        + row['skey_']
                        + "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=900");
                }
            },
            {
                text: '删除',
                icon: 'delete',
                showMenuFn: function (row) {
                    return row.docStatus == "WSH";
                },
                action: function (row, rows, grid) {
                    if (window.confirm("确认要删除?")) {
                        $.ajax({
                            type: "POST",
                            url: "?model=stock_allocation_allocation&action=ajaxdeletes",
                            data: {
                                id: row.id
                            },
                            success: function (msg) {
                                if (msg == 1) {
                                    show_page();
                                    alert('删除成功！');
                                } else {
                                    alert('删除失败，该对象可能已经被引用!');
                                }
                            }
                        });
                    }
                }
            }
        ]
    });
});