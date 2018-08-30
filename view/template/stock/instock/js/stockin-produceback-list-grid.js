var show_page = function (page) {
    $("#purhasestorageGrid").yxsubgrid("reload");
};
$(function () {
    $("#purhasestorageGrid").yxsubgrid({
        model: 'stock_instock_stockin',
        action: 'pageListGridJson',
        title: "生产退料入库单列表",
        isAddAction: false,
        isViewAction: false,
        isEditAction: false,
        isDelAction: false,
        showcheckbox: false,
        isOpButton: false,
        toAddConfig: {
            toAddFn: function (p) {
                action : showModalWin("?model=stock_instock_stockin&action=toAdd&docType=RKPRODUCEBACK")
            }
        },
//        buttonsEx: [{
//            name: 'Add',
//            text: "高级搜索",
//            icon: 'search',
//            action: function (row) {
//                showThickboxWin("?model=stock_instock_stockin&action=toAdvancedSearch&docType=RKPRODUCEBACK"
//                + "&placeValuesBefore&TB_iframe=true&modal=false&height=300&width=600")
//            }
//        }],
        menusEx: [{
            name: 'view',
            text: "查看",
            icon: 'view',
            action: function (row, rows, grid) {
                showModalWin("?model=stock_instock_stockin&action=toView&id="
                + row.id
                + "&docType="
                + row.docType
                + "&skey="
                + row['skey_'],1,row.id);
            }
        }, {
            name: 'edit',
            text: "修改",
            icon: 'edit',
            showMenuFn: function (row) {
                if (row.docStatus == "WSH") {
                    return true;
                }
                return false;
            },
            action: function (row, rows, grid) {
                showModalWin("?model=stock_instock_stockin&action=toEdit&id="
                + row.id + "&docType=" + row.docType + "&skey="
                + row['skey_'],1,row.id);
            }
        }, {
            name: 'addred',
            text: "下推红色单",
            icon: 'business',
            showMenuFn: function (row) {
                if (row.docStatus == "YSH" && row.isRed == "0") {
                    return true;
                } else {
                    return false;
                }
            },
            action: function (row, rows, grid) {
                showModalWin("?model=stock_instock_stockin&action=toAddRed&id="
                + row.id + "&docType=" + row.docType + "&skey="
                + row['skey_']);
            }
        }, {
            name: 'unlock',
            text: "反审核",
            icon: 'unlock',
            showMenuFn: function (row) {
                if (row.docStatus == "YSH") {
                    var cancelAudit = false;
                    $.ajax({
                        type: "POST",
                        async: false,
                        url: "?model=stock_instock_stockin&action=cancelAuditLimit",
                        data: {
                            docType: row.docType
                        },
                        success: function (result) {
                            if (result == 1)
                                cancelAudit = true;
                        }
                    });
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
                });
                if (closedAudit) {
                    if (canAudit) {
                        if (window.confirm("确认进行反审核吗?")) {
                            $.ajax({
                                type: "POST",
                                url: "?model=stock_instock_stockin&action=cancelAudit",
                                data: {
                                    id: row.id,
                                    docType: row.docType
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
        }, {
            text: '删除',
            icon: 'delete',
            showMenuFn: function (row) {
                if (row.docStatus == "WSH") {
                    return true;
                }
                return false;
            },
            action: function (row, rows, grid) {
                if (window.confirm("确认要删除?")) {
                    $.ajax({
                        type: "POST",
                        url: "?model=stock_instock_stockin&action=ajaxdeletes",
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
        }, {
            name: 'view',
            text: "打印",
            icon: 'view',
            action: function (row, rows, grid) {
                showThickboxWin("?model=stock_instock_stockin&action=toPrint&id="
                + row.id
                + "&docType="
                + row.docType
                + "&skey="
                + row['skey_']
                + "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=900");
            }
        }],
        param: {
            'docType': 'RKPRODUCEBACK'
        },
        // 列信息
        colModel: [{
            display: 'id',
            name: 'id',
            sortable: true,
            hide: true
        }, {
            name: 'isRed',
            display: '红蓝色',
            sortable: true,
            align: 'center',
            width: '35',
            process: function (v, row) {
                if (row.isRed == '0') {
                    return "<img src='images/icon/hblue.gif' />";
                } else {
                    return "<img src='images/icon/hred.gif' />";
                }
            }
        }, {
            name: 'docCode',
            display: '单据编号',
            sortable: true
        }, {
            name: 'auditDate',
            display: '单据日期',
            sortable: true
        }, {
            name: 'docType',
            display: '入库单类型',
            sortable: true,
            hide: true
        }, {
            name: 'relDocId',
            display: '源单id',
            sortable: true,
            hide: true
        }, {
            name: 'relDocCode',
            display: '源单编号',
            sortable: true
        }, {
            name: 'relDocName',
            display: '源单名称',
            sortable: true,
            hide: true
        }, {
            name: 'inStockId',
            display: '收料仓库id',
            sortable: true,
            hide: true
        }, {
            name: 'inStockCode',
            display: '收料仓库代码',
            sortable: true
        }, {
            name: 'inStockName',
            display: '收料仓库名称',
            sortable: true
        }, {
            name: 'docStatus',
            display: '单据状态',
            sortable: true,
            process: function (v, row) {
                if (row.docStatus == 'WSH') {
                    return "未审核";
                } else {
                    return "已审核";
                }
            }
        }, {
            name: 'catchStatus',
            display: '钩稽状态',
            sortable: true,
            hide: true
        }, {
            name: 'remark',
            display: '备注',
            sortable: true,
            hide: true

        }, {
            name: 'purchaserCode',
            display: '采购员编号',
            sortable: true,
            hide: true
        }, {
            name: 'acceptorName',
            display: '验收人名称',
            sortable: true,
            hide: true
        }, {
            name: 'acceptorCode',
            display: '验收人编号',
            sortable: true,
            hide: true
        }, {
            name: 'chargeName',
            display: '负责人名称',
            sortable: true,
            hide: true
        }, {
            name: 'chargeCode',
            display: '负责人编号',
            sortable: true,
            hide: true
        }, {
            name: 'custosName',
            display: '保管人名称',
            sortable: true,
            hide: true
        }, {
            name: 'custosCode',
            display: '保管人编号',
            sortable: true,
            hide: true
        }, {
            name: 'auditerName',
            display: '审核人名称',
            sortable: true,
            hide: true
        }, {
            name: 'auditerCode',
            display: '审核人编号',
            sortable: true,
            hide: true
        }, {
            name: 'auditDate',
            display: '审核日期',
            sortable: true,
            hide: true
        }, {
            name: 'accounterName',
            display: '记账人',
            sortable: true,
            hide: true
        }, {
            name: 'accounterCode',
            display: '记账人编号',
            sortable: true,
            hide: true
        }, {
            name: 'createName',
            display: '制单',
            sortable: true
        }, {
            name: 'auditerName',
            display: '审核人',
            sortable: true
        }],
        // 主从表格设置
        //主从表中加了个字段   规格型号   2013.7.5
        subGridOptions: {
            url: '?model=stock_instock_stockinitem&action=pageJson',
            param: [{
                paramId: 'mainId',
                colId: 'id'
            }],
            colModel: [{
                name: 'productCode',
                width: 80,
                display: '物料编号'
            }, {
                name: 'k3Code',
                width: 80,
                display: 'k3编号'
            }, {
                name: 'productName',
                width: 140,
                display: '物料名称'
            }, {
                name: 'pattern',
                width: 140,
                display: '规格型号'
            }, {
                name: 'actNum',
                display: "实收数量",
                width: 80,
                process: function (v, row, prow) {
                    if (prow['isRed'] == "1") {
                        return -v;
                    } else {
                        return v;
                    }
                }
            }, {
                name: 'serialnoName',
                display: "序列号",
                width: '350'
            }, {
                name: 'batchNum',
                display: "批次号"
            }]
        },
        comboEx: [{
            text: '单据状态',
            key: 'docStatus',
            data: [{
                text: '未审核',
                value: 'WSH'
            }, {
                text: '已审核',
                value: 'YSH'
            }]
        }, {
            text: '红蓝字',
            key: 'isRed',
            data: [{
                text: '蓝字',
                value: '0'
            }, {
                text: '红字',
                value: '1'
            }]
        }],
        searchitems: [{
            display: "序列号",
            name: 'serialnoName'
        }, {
            display: '批次号',
            name: 'batchNum'
        }, {
            display: '单据编号',
            name: 'docCode'
        }, {
			display : '源单编号',
			name : 'relDocCode'
		}, {
            display: '收料仓库名称',
            name: 'inStockName'
        }, {
            display: '物料编号',
            name: 'productCode'
        }, {
            display: '物料名称',
            name: 'productName'
        }, {
            display: '物料规格型号',
            name: 'pattern'
        }, {
            display: '备注',
            name: 'remark'
        }],
        sortorder: "DESC"
    });
});