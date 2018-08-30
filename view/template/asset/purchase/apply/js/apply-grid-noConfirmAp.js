var show_page = function (page) {
    $("#applyGrid").yxsubgrid("reload");
};
$(function () {
    $("#applyGrid").yxsubgrid({
        model: 'asset_purchase_apply_apply',
        title: '资产采购申请确认列表',
        showcheckbox: false,
        param: {
            "totalManagerId": $("#userId").val(),
            "isSetMyList": 'true',
            // purchaseDepts: "0,2",
            stateNoIn: "未提交"
        },
        isDelAction: false,
        isAddAction: false,
        isEditAction: false,
        // 列信息
        colModel: [{
            display: 'id',
            name: 'id',
            sortable: true,
            hide: true
        }, {
            name: 'applyTime',
            display: '申请日期',
            sortable: true,
            width: 80
        }, {
            name: 'formCode',
            display: '单据编号',
            sortable: true,
            width: 110
        }, {
            name: 'purchaseDept',
            display: '采购部门',
            sortable: true,
            process: function (v) {
                if (v == "1") {
                    return '交付部';
                } else if (v == "2") {
                    return '动悉行政部';
                } else {
                    return '行政部';
                }
            },
            width: 80
        }, {
            name: 'applicantName',
            display: '申请人名称',
            sortable: true,
            hide: true
        }, {
            name: 'userName',
            display: '使用人名称',
            sortable: true
        }, {
            name: 'useDetName',
            display: '使用部门',
            sortable: true,
            width: 80
        }, {
//			name : 'purchCategory',
//			display : '采购种类',
//			sortable : true,
//			datacode : 'CGZL'
//		}, {
            name: 'assetUse',
            display: '资产用途',
            sortable: true
        }, {
            name: 'state',
            display: '单据状态',
            sortable: true,
            width: 80
        }, {
            name: 'ExaStatus',
            display: '审批状态',
            sortable: true,
            width: 90
        }, {
            name: 'ExaDT',
            display: '审批时间',
            sortable: true
        }],
        // 主从表格设置
        subGridOptions: {
            url: '?model=asset_purchase_apply_applyItem&action=pageJson',
            param: [{
                paramId: 'applyId',
                colId: 'id'
            }],
            colModel: [{
                name: 'inputProductName',
                width: 200,
                display: '物料名称'
            }, {
                name: 'pattem',
                display: "规格"
            }, {
                name: 'unitName',
                display: "单位",
                width: 50
            }, {
                name: 'applyAmount',
                display: "申请数量",
                width: 70
            }, {
                name: 'issuedAmount',
                display: "下达数量",
                width: 70,
                process: function (v) {
                    if (v == "") {
                        return 0;
                    } else {
                        return v;
                    }

                }
            }, {
                name: 'dateHope',
                display: "希望交货日期"
            }, {
                name: 'remark',
                display: "备注"
            }]
        },
        comboEx: [{
            text: '单据状态',
            key: 'state',
            value: '未确认',
            data: [{
                text: '未确认',
                value: '未确认'
            }, {
                text: '已确认',
                value: '已确认'
            }, {
            //    text: '已提交',
            //    value: '已提交'
            //}, {
                text: '打回',
                value: '打回'
            }]
        }],
        /**
         * 快速搜索
         */
        searchitems: [{
            display: '单据编号',
            name: 'formCode'
        }, {
            display: '使用部门',
            name: 'useDetName'
        }, {
            display: '物料名称',
            name: 'productName'
        }],
        toAddConfig: {
            formWidth: 900,
            /**
             * 新增表单默认高度
             */
            formHeight: 600
        },
        toViewConfig: {
            /**
             * 查看表单默认宽度
             */
            formWidth: 900,
            /**
             * 查看表单默认高度
             */
            formHeight: 600
        },
        // 扩展右键菜单
        menusEx: [{
            text: '确认申请',
            icon: 'edit',
            showMenuFn: function (row) {
                if (row.state == '未确认' && row.ExaStatus == "已提交") {
                    return true;
                }
                return false;
            },
            action: function (row, rows, grid) {
                if (row) {
                    showThickboxWin('index1.php?model=asset_purchase_apply_apply&action=init&perm=toConfirm&id=' + row.id
                        + "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=600&width=850");
                } else {
                    alert("请选中一条数据");
                }
            }
        }, {
            text: '打回',
            icon: 'edit',
            showMenuFn: function (row) {
                if (row.state == '未确认' && row.ExaStatus == "已提交") {
                    return true;
                }
                return false;
            },
            action: function (row, rows, grid) {
                var id = row.id;
                if (confirm("确定要打回该申请单吗?")) {
                    $.ajax({
                        type: "POST",
                        url: '?model=asset_purchase_apply_apply&action=dispassApply',
                        data: {"id": id},
                        success: function (result) {
                            var resultObj = eval("(" + result + ")");
                            if (resultObj.result == 'ok') {
                                alert("打回成功!");
                            } else {
                                alert("打回失败!");
                            }
                            show_page();
                            tb_remove();
                        }
                    });
                }
            }
        }]
    });
});