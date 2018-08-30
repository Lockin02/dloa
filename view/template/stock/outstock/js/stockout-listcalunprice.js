var show_page = function () {
    $("#stockbalanceGrid").yxgrid("reload");
};

$(function () {
    $("#stockbalanceGrid").yxgrid({
        model: 'stock_outstock_stockout',
        action: 'calPageJson',
        title: '不确定单价单据',
        param: {
            docStatus: 'YSH',
            thisYear: $("#thisYear").val(),
            thisMonth: $("#thisMonth").val(),
            prodcutNoInSB: '1'
        },
        sortname: 'auditDate ASC,productCode',
        sortorder: 'ASC',
        isDelAction: false,
        isEditAction: false,
        isAddAction: false,
        isViewAction: false,
        isShowNum: false,
        showcheckbox: false,
        usepager: false, // 是否分页
        // 列信息
        colModel: [
            {
                display: 'id',
                name: 'id',
                sortable: true,
                hide: true
            }, {
                display: '表单id',
                name: 'mainId',
                sortable: true,
                hide: true
            }, {
                name: 'auditDate',
                display: '单据日期',
                sortable: true,
                width: 80
            }, {
                name: 'docStatus',
                display: '单据状态',
                sortable: true,
                width: 50,
                process: function (v, row) {
                    if (row.docStatus == 'WSH') {
                        return "未审核";
                    } else if (row.docStatus == 'YSH') {
                        return "已审核";
                    }
                }
            }, {
                name: 'docType',
                display: '单据类型',
                sortable: true,
                width: 70,
                process: function (v, row) {
                    if (row.docStatus != undefined) {
                        if (v == 'CKSALES') {
                            return '销售出库';
                        } else if (v == 'CKOTHER') {
                            return '其他出库';
                        } else {
                            return '领料出库';
                        }
                    }
                }
            }, {
                name: 'docCode',
                display: '单据编号',
                sortable: true,
                width: 80
            }, {
                name: 'relDocName',
                display: '源单名称',
                sortable: true,
                hide: true

            }, {
                name: 'customerName',
                display: '客户名称',
                sortable: true,
                width: 140
            }, {
                name: 'stockName',
                display: '发料仓库',
                sortable: true
            }, {
                name: 'catchStatus',
                display: '钩稽状态',
                sortable: true,
                hide: true,
                datacode: 'CGFPZT'
            }, {
                name: 'productCode',
                display: '物料编号',
                sortable: true,
                width: 80
            }, {
                name: 'productName',
                display: '物料名称',
                sortable: true,
                width: 110
            }, {
                name: 'unitName',
                display: '单位',
                sortable: true,
                width: 70
            }, {
                name: 'actOutNum',
                display: '数量',
                sortable: true,
                width: 70
            }, {
                name: 'cost',
                display: '单价',
                sortable: true,
                process: function (v) {
                    return moneyFormat2(v);
                },
                width: 70
            }, {
                name: 'subCost',
                display: '金额',
                sortable: true,
                process: function (v) {
                    return moneyFormat2(v);
                },
                width: 70
            }
        ],
        buttonsEx: [{
            name: 'edit',
            text: "更新单价",
            icon: 'edit',
            items: [{
                text: '最初余额加权平均价',
                icon: 'edit',
                action: function () {
                    if (confirm("确认按照【最初余额加权平均价】更新吗？")) {
                        $.ajax({
                            type: "post",
                            url: "?model=stock_outstock_stockout&action=updateProductPrice",
                            data: {
                                docStatus: 'YSH', thisYear: $("#thisYear").val(),
                                thisMonth: $("#thisMonth").val(), updateType: 0
                            },
                            success: function (msg) {
                                if (msg != "0") {
                                    alert("更新成功，更新记录【" + msg + "】条");
                                } else {
                                    alert("无更新数据");
                                }
                                show_page();
                            }
                        });
                    }
                }
            }, {
                text: '上期最新出库价',
                icon: 'edit',
                action: function () {
                    if (confirm("确认按照【上期最新出库价】更新吗？")) {
                        $.ajax({
                            type: "post",
                            url: "?model=stock_outstock_stockout&action=updateProductPrice",
                            data: {
                                docStatus: 'YSH', thisYear: $("#thisYear").val(),
                                thisMonth: $("#thisMonth").val(), updateType: 1
                            },
                            success: function (msg) {
                                if (msg != "0") {
                                    alert("更新成功，更新记录【" + msg + "】条");
                                } else {
                                    alert("无更新数据");
                                }
                                show_page();
                            }
                        });
                    }
                }
            }, {
                text: '最新入库价',
                icon: 'edit',
                action: function () {
                    if (confirm("确认按照【最新入库价】更新吗？")) {
                        $.ajax({
                            type: "post",
                            url: "?model=stock_outstock_stockout&action=updateProductPrice",
                            data: {
                                docStatus: 'YSH', thisYear: $("#thisYear").val(),
                                thisMonth: $("#thisMonth").val(), updateType: 2
                            },
                            success: function (msg) {
                                if (msg != "0") {
                                    alert("更新成功，更新记录【" + msg + "】条");
                                } else {
                                    alert("无更新数据");
                                }
                                show_page();
                            }
                        });
                    }
                }
            }]
        }],
        menusEx: [
            {
                name: 'edit',
                text: "修改",
                icon: 'edit',
                action: function (row, rows, grid) {
                    showOpenWin("?model=stock_outstock_stockout&action=toEditCost&id="
                        + row.mainId
                        + "&docType="
                        + row.docType
                        + "&skey="
                        + row.skey_);
                }
            }, {
                name: 'view',
                text: "查看",
                icon: 'view',
                action: function (row, rows, grid) {
                    showThickboxWin("?model=stock_outstock_stockout&action=toView&id="
                        + row.mainId
                        + "&docType="
                        + row.docType
                        + "&skey="
                        + row.skey_
                        + "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=900");
                }
            }
        ],
        searchitems: [{
            display: '单据编号',
            name: 'docCode'
        }, {
            display: '仓库名称',
            name: 'stockNameLike'
        }, {
            display: '物料代码',
            name: 'productCode'
        }, {
            display: '物料名称',
            name: 'productName'
        }]
    });
});