var show_page = function (page) {
    $("#requirementGrid").yxsubgrid("reload");
};

$(function () {
    var menusArr = [{
        text: '查看',
        icon: 'view',
        action: function (row) {
            showOpenWin('?model=asset_purchase_apply_apply&action=toViewTab&applyId='
                + row.id
                + '&placeValuesBefore&TB_iframe=true&modal=false&height=500&width=900');
        }
    }, {
        text: '提交需求',
        icon: 'edit',
        showMenuFn: function (row) {
            if (row.state == "已提交" || row.purchaseDept == '1') {
                return false;
            }
            return true;
        },
        action: function (row) {
            showThickboxWin('?model=asset_purchase_apply_apply&action=initRequire&id='
                + row.id +
                '&placeValuesBefore&TB_iframe=true&modal=false&height=500&width=1000');
        }
    }, {
        text: '填写验收单',
        icon: 'add',
        showMenuFn: function (row) {
            if (row.state == "已提交" && (row.purchaseDept == "0" || row.purchaseDept == "2")) {
                return true;
            }
            return false;
        },
        action: function (row, rows, grid) {
            if (row) {
                console.log(row);
                showThickboxWin("?model=asset_purchase_receive_receive&action=toRequireAdd"
                    + "&code="
                    + row.formCode
                    + "&applicantName="
                    + row.createName
                    + "&applicantId="
                    + row.createId
                    + "&applyId="
                    + row.id
                    + '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=800');
            } else {
                alert("请选中一条数据");
            }
        }
    }];
    // var attachment = {
    //     text: '分配采购',
    //     icon: 'edit',
    //     showMenuFn: function (row) {
    //         if (row.state == "已提交" || row.purchaseDept == '1') {
    //             return false;
    //         }
    //         return true;
    //     },
    //     action: function (row) {
    //         showThickboxWin('?model=asset_purchase_apply_apply&action=toAssignPurchaser&id='
    //             + row.id +
    //
    //             '&placeValuesBefore&TB_iframe=true&modal=false&height=500&width=1000');
    //     }
    // };
    // $.ajax({
    //     type: 'POST',
    //     url: '?model=asset_purchase_apply_apply&action=getLimits',
    //     data: {
    //         'limitName': '采购分配'
    //     },
    //     async: false,
    //     success: function (data) {
    //         if (data == 1) {
    //             menusArr.push(attachment);
    //         }
    //     }
    // });
    $("#requirementGrid").yxsubgrid({
        model: 'asset_purchase_apply_apply',
        action: "areaListPageJson",
        param: {
            "ExaStatus": '完成',
            "ifShow": '0'
        },
        title: '采购需求',
        showcheckbox: false,
        isAddAction: false,
        isEditAction: false,
        isDelAction: false,
        isViewAction: false,
        // 列信息
        colModel: [{
            display: 'id',
            name: 'id',
            sortable: true,
            hide: true
        }, {
            name: 'formCode',
            display: '单据编号',
            sortable: true,
            width: 100
        }, {
            name: 'relDocCode',
            display: '需求申请单号',
            sortable: true,
            width: 120
        }, {
            name: 'state',
            display: '单据状态',
            sortable: true,
            width: 60
        }, {
            name: 'applyTime',
            display: '申请日期',
            sortable: true,
            width: 80
        }, {
            name: 'applicantName',
            display: '申请人',
            sortable: true,
            width: 80
        }, {
            name: 'userName',
            display: '使用人',
            sortable: true,
            width: 80
        }, {
            name: 'useDetName',
            display: '使用部门',
            sortable: true,
            width: 80
        }, {
            name: 'assetUse',
            display: '资产用途',
            sortable: true,
            width: 80
        }, {
            name: 'purchaseDept',
            display: '采购部门',
            sortable: true,
            width: 80,
            process: function (v) {
                if (v == '0') {
                    return "行政部";
                } else if (v == '1') {
                    return "交付部";
                } else if (v == "2") {
                    return '动悉行政部';
                } else {
                    return "";
                }
            }
        }, {
            name: 'estimatPrice',
            display: '预估总价',
            sortable: true,
            // 列表格式化千分位
            process: function (v) {
                return moneyFormat2(v);
            },
            width: 80
        }, {
            name: 'moneyAll',
            display: '总金额',
            sortable: true,
            // 列表格式化千分位
            process: function (v) {
                return moneyFormat2(v);
            },
            width: 80
        }, {
            name: 'agencyName',
            display: '采购区域',
            width: 80
        }],
        // 主从表格设置
        subGridOptions: {
            url: '?model=asset_purchase_apply_applyItem&action=IsDelPageJson',
            param: [{
                paramId: 'applyId',
                colId: 'id'
            }],
            colModel: [{
                display: '物料名称',
                name: 'productName',
                process: function (v, row) {
                    if (v == '') {
                        return row.inputProductName;
                    }
                    return v;
                },
                tclass: 'readOnlyTxtItem'
            }, {
                display: '规格',
                name: 'pattem',
                tclass: 'readOnlyTxtItem'
            }, {
                display: '申请数量',
                name: 'applyAmount',
                tclass: 'txtshort'
            }, {
                display: '供应商',
                name: 'supplierName',
                tclass: 'txtmiddle'
            }, {
                display: '单位',
                name: 'unitName',
                tclass: 'readOnlyTxtItem'
            }, {
                display: '采购数量',
                name: 'purchAmount',
                tclass: 'txtshort'
            }, {
                display: '单价',
                name: 'price',
                tclass: 'txtshort',
                // 列表格式化千分位
                process: function (v) {
                    return moneyFormat2(v);
                }
            }, {
                display: '金额',
                name: 'moneyAll',
                tclass: 'txtshort',
                // 列表格式化千分位
                process: function (v) {
                    return moneyFormat2(v);
                }
            }, {
                display: '希望交货日期',
                name: 'dateHope',
                type: 'date'
            }, {
                display: '备注',
                name: 'remark',
                tclass: 'txt'
            }]
        },
        // 扩展右键菜单
        menusEx: menusArr,
        comboEx: [{
            text: '单据状态',
            key: 'state',
            data: [{
                text: '未提交',
                value: '未提交'
            }, {
                text: '已提交',
                value: '已提交'
            }]
        }],
        /**
         * 快速搜索
         */
        searchitems: [{
            display: '单据编号',
            name: 'formCode'
        }, {
            display: '需求申请单号',
            name: 'relDocCode'
        }, {
            display: '申请日期',
            name: 'applyTime'
        }, {
            display: '申请人',
            name: 'applicantName'
        }, {
            display: '使用人名称',
            name: 'userName'
        }, {
            display: '物料名称',
            name: 'productName'
        }]
    });
});