var show_page = function () {
    $("#arrivalGrid").yxsubgrid("reload");
};
$(function () {
    $("#arrivalGrid").yxsubgrid({
        model: 'purchase_arrival_arrival',
        action: 'stockPageJson',
        title: '待入库收料通知单',
        showcheckbox: false,
        isEditAction: false,
        isViewAction: false,
        isDelAction: false,
        isAddAction: false,
        param: {
            'stateArr': "0,4", "arrivalType": "ARRIVALTYPE1", "isCanInstock": "1"
        },
        // 列信息
        colModel: [
            {
                display: 'id',
                name: 'id',
                sortable: true,
                hide: true
            },
            {
                name: 'arrivalCode',
                display: '收料单号',
                sortable: true,
                width: 90
            },
            {
                name: 'state',
                display: '收料通知单状态',
                sortable: true,
                process: function (v, row) {
                    if (row.state == '0') {
                        return "未执行";
                    } else if (row.state == '4') {
                        return "部分执行";
                    } else if (row.state == '2') {
                        return "已执行";
                    }
                }
            },
            {
                name: 'arrivalDate',
                display: '收料日期',
                sortable: true,
                width: 80
            },
            {
                name: 'purchaseCode',
                display: '采购订单编号',
                sortable: true,
                width: 150
            },
            {
                name: 'arrivalType',
                display: '收料类型',
                sortable: true,
                datacode: 'ARRIVALTYPE'
            },
            {
                name: 'supplierName',
                display: '供应商名称',
                sortable: true,
                width: 150
            },
            {
                name: 'purchManName',
                display: '采购员',
                sortable: true,
                width: 70
            },
            {
                name: 'purchMode',
                display: '采购方式',
                hide: true,
                datacode: 'cgfs'
            },
            {
                name: 'stockName',
                display: '收料仓库名称',
                sortable: true
            },
            {
                name: 'completionTime',
                display: "质检完成时间",
                width: '80'
            }
        ],
        // 主从表格设置
        subGridOptions: {
            url: '?model=purchase_arrival_equipment&action=pageJson',
            param: [
                {
                    paramId: 'arrivalId',
                    colId: 'id'
                }
            ],
            colModel: [
                {
                    name: 'sequence',
                    display: '物料编号',
                    width: 80
                },
                {
                    name: 'productName',
                    width: 200,
                    display: '物料名称'
                },
                {
                    name: 'pattem',
                    display: "规格型号"
                },
                {
                    name: 'batchNum',
                    display: "批次号"
                },
                {
                    name: 'month',
                    display: "月份",
                    width: '50'
                },
                {
                    name: 'arrivalNum',
                    display: "收料数量",
                    width: '70'
                },
                {
                    name: 'storageNum',
                    display: "已入库数量",
                    width: '70'
                },
                {
                    name: 'qualityName',
                    display: "采购属性",
                    width: '60'
                },
                {
                    name: 'qualityPassNum',
                    display: "质检合格数量",
                    width: '80'
                },
                {
                    name: 'completionTime',
                    display: "质检完成时间",
                    width: '80'
                }
            ]
        },
        // 扩展右键菜单
        menusEx: [
            {
                name: 'view',
                text: '查看',
                icon: 'view',
                action: function (row, rows, grid) {
                    if (row) {
                        showThickboxWin("?model=purchase_arrival_arrival&action=init&perm=view&id="
                            + row.id
                            + "&skey="
                            + row['skey_']
                            + "&placeValuesBefore&TB_iframe=true&modal=false&height=550&width=900");
                    } else {
                        alert("请选中一条数据");
                    }
                }
            },
            {
                name: 'bluepush',
                text: '下推入库单',
                icon: 'business',
                action: function (row, rows, grid) {
                    if (row) {
                        // alert()
                        showModalWin("index1.php?model=stock_instock_stockin&action=toBluePush&docType=RKPURCHASE&relDocType=RSLTZD&relDocId="
                            + row.id + "&relDocCode=" + row.arrivalCode);
                    } else {
                        alert("请选中一条数据");
                    }
                }
            },
            {
                text: '关闭',
                icon: 'delete',
                action: function (row, rows, grid) {
                    if (row) {
                        if (window.confirm("确认要关闭?")) {
                            $.ajax({
                                type: "POST",
                                url: "?model=purchase_arrival_arrival&action=changStateClose",
                                data: {
                                    id: row.id
                                },
                                success: function (msg) {
                                    if (msg == 1) {
                                        alert('关闭成功!');
                                        show_page();
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
                display: '收料单号',
                name: 'arrivalCode'
            },
            {
                display: '采购员',
                name: 'purchManName'
            },
            {
                display: '供应商',
                name: 'supplierName'
            },
            {
                display: '物料名称',
                name: 'productName'
            },
            {
                display: '物料编号',
                name: 'sequence'
            },
            {
                display: '采购订单编号',
                name: 'purchaseCodeSearch'
            }
        ],
        // 默认搜索顺序
        sortorder: "DESC",
        sortname: "updateTime"
    });
});