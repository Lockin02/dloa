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
        title: '采购发票',
        param: {"status": 1, "formDateYear": $("#thisYear").val(), "formDateMonth": $("#thisMonth").val()},
        action: 'pageJsonCacu',
        isEditAction: false,
        isAddAction: false,
        isDelAction: false,
        isViewAction: false,
        showcheckbox: false,
        isShowNum: false,
        //列信息
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
                display: '单据编号',
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
                display: '发票号码',
                sortable: true
            },
            {
                name: 'supplierName',
                display: '供应商名称',
                sortable: true,
                width: 150
            },
            {
                name: 'invType',
                display: '发票类型',
                sortable: true,
                width: 80,
                datacode: 'FPLX'
            },
            {
                name: 'taxRate',
                display: '税率(%)',
                sortable: true,
                width: 60
            },
            {
                name: 'formAssessment',
                display: '单据税额',
                sortable: true,
                process: function (v) {
                    return moneyFormat2(v);
                },
                width: 80
            },
            {
                name: 'amount',
                display: '总金额',
                sortable: true,
                process: function (v) {
                    return moneyFormat2(v);
                },
                width: 80
            },
            {
                name: 'formCount',
                display: '价税合计',
                sortable: true,
                process: function (v) {
                    return moneyFormat2(v);
                },
                width: 80
            },
            {
                name: 'formDate',
                display: '单据日期',
                sortable: true,
                width: 80
            },
            {
                name: 'payDate',
                display: '付款日期',
                sortable: true,
                width: 80
            }, {
                name: 'purcontCode',
                display: '采购订单编号',
                width: 130,
                hide: true
            },
            {
                name: 'departments',
                display: '部门',
                sortable: true,
                width: 80
            },
            {
                name: 'salesman',
                display: '业务员',
                sortable: true,
                width: 80
            },
            {
                name: 'ExaStatus',
                display: '审核状态',
                sortable: true,
                width: 60,
                process: function (v) {
                    if (v == 1) {
                        return '已审核';
                    } else {
                        return '未审核';
                    }
                }
            },
            {
                name: 'exaMan',
                display: '审核人',
                sortable: true,
                width: 80
            },
            {
                name: 'ExaDT',
                display: '审核日期',
                sortable: true,
                width: 80
            },
            {
                name: 'status',
                display: '钩稽状态',
                sortable: true,
                width: 60,
                process: function (v) {
                    if (v == 1) {
                        return '已钩稽';
                    } else {
                        return '未钩稽';
                    }
                }
            }, {
                name: 'createName',
                display: '创建人',
                width: 90,
                hide: true
            },
            {
                name: 'belongId',
                display: '所属原发票id',
                hide: true
            }
        ],

        // 主从表格设置
        subGridOptions: {
            url: '?model=finance_invpurchase_invpurdetail&action=pageJson',// 获取从表数据url
            // 传递到后台的参数设置数组
            param: [
                {
                    paramId: 'invPurId',// 传递给后台的参数名称
                    colId: 'id'// 获取主表行数据的列名称
                }
            ],
            // 显示的列
            colModel: [{
                name: 'productNo',
                display: '物料编号',
                width: 80
            }, {
                name: 'productName',
                display: '物料名称',
                width: 140
            }, {
                name: 'productModel',
                display: '规格型号'
            }, {
                name: 'unit',
                display: '单位',
                width: 60
            }, {
                name: 'number',
                display: '数量',
                width: 60
            }, {
                name: 'price',
                display: '单价',
                process: function (v) {
                    return moneyFormat2(v);
                },
                width: 80
            }, {
                name: 'taxPrice',
                display: '含税单价',
                process: function (v) {
                    return moneyFormat2(v);
                },
                width: 80
            }, {
                name: 'assessment',
                display: '税额',
                process: function (v) {
                    return moneyFormat2(v);
                },
                width: 80
            }, {
                name: 'amount',
                display: '金额',
                process: function (v) {
                    return moneyFormat2(v);
                },
                width: 80
            }, {
                name: 'allCount',
                display: '价税合计',
                process: function (v) {
                    return moneyFormat2(v);
                },
                width: 80
            }, {
                name: 'objCode',
                display: '关联编号',
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
                text: "查看",
                icon: 'view',
                action: function (row) {
                    showOpenWin('?model=finance_invpurchase_invpurchase&action=init&perm=view&id=' + row.id + "&skey=" + row.skey_);
                }
            },
            {
                text: "钩稽日志",
                icon: 'view',
                action: function (row) {
                    showOpenWin('?model=finance_related_baseinfo&action=toUnhook&invPurId=' + row.id);
                }
            }
        ],
        buttonsEx: [
            {
                text: "核算",
                icon: 'edit',
                action: function (row) {
                    if (hasUncheck() == 0) {
                        alert('存在未审核的入库单据,请现对入库单进行审核');
                        return false;
                    } else {
                        if (confirm("确定要进行核算吗?")) {
                            $.ajax({
                                type: "POST",
                                url: "?model=finance_stockbalance_stockbalance&action=calculate",
                                data: {
                                    "thisYear": $("#thisYear").val(),
                                    "thisMonth": $("#thisMonth").val()
                                },
                                success: function (msg) {
                                    if (msg == 1) {
                                        alert('核算成功！');
                                        show_page(1);
                                    } else {
                                        alert("核算失败! ");
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
                display: '发票号码',
                name: 'objNo'
            },
            {
                display: '供应商名称',
                name: 'supplierName'
            },
            {
                display: '单据编号',
                name: 'objCodeSearch'
            },
            {
                display: '源单编号',
                name: 'objCodeSearchDetail'
            },
            {
                display: '采购订单编号',
                name: 'contractCodeSearch'
            }
        ],
        sortname: 'updateTime'
    });
});