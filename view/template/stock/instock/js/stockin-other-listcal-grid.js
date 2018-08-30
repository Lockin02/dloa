var show_page = function () {
    $("#purhasestorageGrid").yxgrid("reload");
};

$(function () {
    // 缓存年月
    var thisYear = $("#thisYear").val();
    var thisMonth = $("#thisMonth").val();

    $("#purhasestorageGrid").yxgrid({
        model: 'stock_instock_stockin',
        action: 'calPageJson',
        title: "其他入库（" + thisYear + "." + thisMonth + "）",
        isAddAction: false,
        isViewAction: false,
        isEditAction: false,
        isDelAction: false,
        showcheckbox: false,
        isOpButton: false,
        param: {
            'docType': $('#docType').val(),
            'docStatus': 'YSH',
            'thisYear': thisYear,
            'thisMonth': thisMonth
        },
        usepager: false, // 是否分页
        // 列信息
        colModel: [{
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
                if (row.id == "nocheck") {
                    return "";
                }
                return row.docStatus == 'WSH' ? "未审核" : "已审核";
            }
        }, {
            name: 'docType',
            display: '单据类型',
            sortable: true,
            width: 60,
            process: function (v, row) {
                if (row.docStatus != undefined) {
                    if (v == 'RKPURCHASE') {
                        return '外购入库';
                    } else if (v == 'RKOTHER') {
                        return '其他入库';
                    } else {
                        return '产品入库';
                    }
                }
            }
        }, {
            name: 'docCode',
            display: '单据编号',
            sortable: true,
            width: 80
        }, {
            name: 'supplierName',
            display: '供应商名称',
            sortable: true,
            width: 140
        }, {
            name: 'inStockName',
            display: '收料仓库',
            sortable: true,
            width: 80
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
            width: 50
        }, {
            name: 'actNum',
            display: '数量',
            sortable: true,
            width: 50
        }, {
            name: 'price',
            display: '单价',
            sortable: true,
            align: 'right',
            process: function (v) {
                return moneyFormat2(v, 6);
            },
            width: 80
        }, {
            name: 'subPrice',
            display: '金额',
            sortable: true,
            align: 'right',
            process: function (v) {
                return moneyFormat2(v);
            },
            width: 80
        }],
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
                            url: "?model=stock_instock_stockin&action=updateProductPrice",
                            data: {
                                docStatus: 'YSH', thisYear: $("#thisYear").val(),
                                thisMonth: $("#thisMonth").val(), updateType: 0,
                                docType: $("#docType").val()
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
                text: '最新出库价',
                icon: 'edit',
                action: function () {
                    if (confirm("确认按照【最新出库价】更新吗？")) {
                        $.ajax({
                            type: "post",
                            url: "?model=stock_instock_stockin&action=updateProductPrice",
                            data: {
                                docStatus: 'YSH', thisYear: $("#thisYear").val(),
                                thisMonth: $("#thisMonth").val(), updateType: 1,
                                docType: $("#docType").val()
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
                            url: "?model=stock_instock_stockin&action=updateProductPrice",
                            data: {
                                docStatus: 'YSH', thisYear: $("#thisYear").val(),
                                thisMonth: $("#thisMonth").val(), updateType: 2,
                                docType: $("#docType").val()
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
        menusEx: [{
            name: 'edit',
            text: "修改",
            icon: 'edit',
            action: function (row, rows, grid) {
                showOpenWin("?model=stock_instock_stockin&action=toEditPrice&id="
                    + row.mainId
                    + "&docType="
                    + row.docType
                    + "&skey="
                    + row['skey_']);
            }
        }, {
            name: 'view',
            text: "查看",
            icon: 'view',
            action: function (row, rows, grid) {
                showThickboxWin("?model=stock_instock_stockin&action=toView&id="
                    + row.mainId
                    + "&docType="
                    + row.docType
                    + "&skey="
                    + row['skey_']
                    + "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=900");
            }
        }],
        searchitems: [{
            display: '单据编号',
            name: 'docCode'
        }, {
            display: '收料仓库',
            name: 'inStockName'
        }],
        sortorder: "DESC"
    });
});