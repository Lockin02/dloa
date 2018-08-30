var show_page = function () {
    $("#incomeGrid").yxsubgrid("reload");
};

$(function () {
    $("#incomeGrid").yxsubgrid({
        model: 'finance_income_income',
        action: 'pageJsonList',
        param: {"formType": "YFLX-DKD"},
        title: '到款管理',
        isViewAction: false,
        isEditAction: false,
        isDelAction: false,
        showcheckbox: false,
        customCode: "incomeGrid",
        isOpButton: false,
        colModel: [{
            display: 'id',
            name: 'id',
            sortable: true,
            hide: true
        }, {
            display: '进账单号',
            name: 'inFormNum',
            sortable: true,
            width: 110,
            hide: true
        }, {
            display: '系统单据号',
            name: 'incomeNo',
            sortable: true,
            width: 120,
            process: function (v, row) {
                if (row.id == 'noId') return v;
                return "<a href='#' onclick='showOpenWin(\"?model=finance_income_income&action=toAllot&perm=view&id=" + row.id + '&skey=' + row.skey_ + "\")'>" + v + "</a>";
            }
        }, {
            display: '到款单位id',
            name: 'incomeUnitId',
            sortable: true,
            hide: true
        }, {
            display: '到款单位',
            name: 'incomeUnitName',
            sortable: true,
            width: 130
        }, {
            display: '到款单位类型',
            name: 'incomeUnitType',
            sortable: true,
            datacode: 'KHLX',
            hide: true
        }, {
            display: '合同单位id',
            name: 'contractUnitId',
            sortable: true,
            hide: true
        }, {
            display: '合同单位',
            name: 'contractUnitName',
            sortable: true,
            width: 130,
            hide: true
        }, {
            display: '省份',
            name: 'province',
            sortable: true,
            width: 70
        }, {
            display: '到款日期',
            name: 'incomeDate',
            sortable: true,
            width: 80
        }, {
            display: '结算类型',
            name: 'incomeType',
            datacode: 'DKFS',
            sortable: true,
            width: 60
        }, {
            display: '到款类型',
            name: 'sectionType',
            datacode: 'DKLX',
            sortable: true,
            width: 60
        }, {
            display: '到款金额',
            name: 'incomeMoney',
            sortable: true,
            process: function (v) {
                return moneyFormat2(v);
            },
            width: 90
        }, {
            name: 'businessBelongName',
            display: '归属公司',
            sortable: true,
            width: 80
        }, {
            display: '以前期间调整',
            name: 'isAdjust',
            sortable: true,
            width: 70,
            process: function (v) {
                if (v == 1) {
                    return '是';
                } else {
                    return '否';
                }
            }
        }, {
            display: '录入人',
            name: 'createName',
            sortable: true,
            width: 80
        }, {
            display: '状态',
            name: 'status',
            datacode: 'DKZT',
            sortable: true,
            width: 80
        }, {
            display: '已邮件',
            name: 'isSended',
            sortable: true,
            width: 60,
            process: function (v) {
                if (v == 1) {
                    return '是';
                } else {
                    return '否';
                }
            }
        }, {
            display: '录入时间',
            name: 'createTime',
            sortable: true,
            width: 120,
            hide: true
        }, {
            display: '备注',
            name: 'remark',
            width: 120
        }],
        buttonsEx: [
            {
                name: 'view',
                text: "高级查询",
                icon: 'view',
                action: function () {
                    showThickboxWin("?model=finance_income_income&action=toSearch&"
                        + '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=450&width=800');
                }
            }, {
                name: 'otherIncome',
                text: "其他到款",
                icon: 'add',
                action: function (row) {
                    showOpenWin("?model=finance_income_income&action=toAddOther");
                }
            }, {
                name: 'excelIn',
                text: "导入",
                icon: 'excel',
                action: function (row) {
                    showThickboxWin("?model=finance_income_income&action=toExcel"
                        + "&placeValuesBefore&TB_iframe=true&modal=false&height=400&width=600")
                }
            }, {
                name: 'excelOut',
                text: "导出",
                icon: 'excel',
                action: function () {
                    var $thisGrid = $("#incomeGrid").data('yxsubgrid');
                    console.log($thisGrid.options.searchParam);
                    var url = "?model=finance_income_income&action=toExcOut"
                        + '&beginYearMonth=' + filterUndefined($thisGrid.options.extParam.beginYearMonth)
                        + '&endYearMonth=' + filterUndefined($thisGrid.options.extParam.endYearMonth)

                        + '&province=' + filterUndefined($thisGrid.options.extParam.province)

                        + '&incomeDate=' + filterUndefined($thisGrid.options.extParam.incomeDate)
                        + '&incomeMoney=' + filterUndefined($thisGrid.options.extParam.incomeMoney)

                        + '&incomeUnitId=' + filterUndefined($thisGrid.options.extParam.incomeUnitId)
                        + '&incomeUnitName=' + filterUndefined($thisGrid.options.extParam.incomeUnitName)

                        + '&contractUnitId=' + filterUndefined($thisGrid.options.extParam.contractUnitId)
                        + '&contractUnitName=' + filterUndefined($thisGrid.options.extParam.contractUnitName)

                        + '&objCode=' + filterUndefined($thisGrid.options.extParam.objCode)

                        + '&incomeUnitType=' + filterUndefined($thisGrid.options.extParam.incomeUnitType)
                    ;
                    var status = $("#status").val();
                    if (status != "") {
                        url += "&status=" + status;
                    }
                    var isSended = $("#isSended").val();
                    if (isSended != "") {
                        url += "&isSended=" + isSended;
                    }
                    for (var k in $thisGrid.options.searchParam) {
                        if ($thisGrid.options.searchParam[k] != "") {
                            url += "&" + k + "=" + $thisGrid.options.searchParam[k];
                        }
                    }
                    window.open(url,"", "width=200,height=200,top=200,left=200");
                }
            }],
        // 主从表格设置
        subGridOptions: {
            url: '?model=finance_income_incomeAllot&action=pageJson',// 获取从表数据url
            // 传递到后台的参数设置数组
            param: [
                {
                    paramId: 'incomeId',// 传递给后台的参数名称
                    colId: 'id'// 获取主表行数据的列名称
                }
            ],
            // 显示的列
            colModel: [{
                name: 'objType',
                display: '源单类型',
                datacode: 'KPRK'
            }, {
                name: 'objCode',
                display: '源单编号',
                width: 180
            }, {
                name: 'areaName',
                display: '销售区域',
                width: 80
            }, {
                name: 'rObjCode',
                display: '业务编号',
                width: 150
            }, {
                name: 'money',
                display: '分配金额',
                process: function (v) {
                    return moneyFormat2(v);
                }
            }, {
                name: 'allotDate',
                display: '分配日期'
            }]
        },
        toAddConfig: {
            toAddFn: function (p) {
                showOpenWin("?model=finance_income_income&action=toAdd&formType=YFLX-DKD");
            }
        },

        // 扩展右键菜单
        menusEx: [{
            text: '编辑到款',
            icon: 'edit',
            showMenuFn: function (row) {
                if (row.status != 'DKZT-YFP' && row.status != 'DKZT-BFFP') {
                    return true;
                }
                return false;
            },
            action: function (row, rows, grid) {
                if (row)
                    showOpenWin("?model=finance_income_income"
                        + "&action=init"
                        + "&id="
                        + row.id
                        + '&skey=' + row['skey_']);
            }
        }, {
            text: '分配到款',
            icon: 'edit',
            showMenuFn: function (row) {
                if (row.status != 'DKZT-FHK' && row.incomeUnitId != '0') {
                    return true;
                }
                return false;
            },
            action: function (row, rows, grid) {
                if (row)
                    showOpenWin("?model=finance_income_income"
                        + "&action=toAllot"
                        + "&id="
                        + row.id
                        + '&skey=' + row['skey_'], 1, 720, 1000, row.incomeNo);
            }
        }, {
            text: '修改备注',
            icon: 'edit',
            action: function (row, rows, grid) {
                if (row) {
                    showThickboxWin("?model=finance_income_income&action=toEditRemark&id="
                        + row.id
                        + '&skey=' + row['skey_']
                        + "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=700");
                }
            }
        }, {
            text: '查看',
            icon: 'view',
            action: function (row, rows, grid) {
                if (row)
                    showOpenWin("?model=finance_income_income"
                        + "&action=toAllot"
                        + "&id="
                        + row.id
                        + '&skey=' + row['skey_']
                        + "&perm=view&placeValuesBefore&TB_iframe=true&modal=false&height=500"
                        + "&width=900");
            }
        }, {
            text: '生成退款单',
            icon: 'add',
            showMenuFn: function (row) {
                if (row.status != 'DKZT-WFP') {
                    return true;
                }
                return false;
            },
            action: function (row) {
                showOpenWin("?model=finance_income_income"
                    + "&action=addByPush"
                    + "&id="
                    + row.id + "&formType=YFLX-TKD"
                    + '&skey=' + row['skey_']);
            }
        }, {
            name: 'view',
            text: "操作日志",
            icon: 'view',
            action: function (row, rows, grid) {
                showThickboxWin("?model=syslog_operation_logoperation&action=businessView&pkValue="
                    + row.id
                    + "&tableName=oa_finance_income"
                    + "&productId=$rs[productId]&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=850");
            }
        }, {
            name: 'delete',
            text: '删除',
            icon: 'delete',
            showMenuFn: function (row) {
                if (row.status == 'DKZT-WFP' || row.status == 'DKZT-FHK' || row.incomeMoney == 0) {
                    return true;
                }
                return false;
            },
            action: function (row, rows, grid) {
                if (row) {
                    if (window.confirm(("确定要删除?"))) {
                        $.ajax({
                            type: "POST",
                            url: "?model=finance_income_income&action=ajaxdeletes",
                            data: {
                                id: row.id
                            },
                            success: function (msg) {
                                if (msg == 1) {
                                    // grid.reload();
                                    alert('删除成功！');
                                    show_page();
                                } else {
                                    alert('删除失败！');
                                    show_page();
                                }
                            }
                        });
                    }
                } else {
                    alert("请选中一条数据");
                }
            }
        }],
        // 过滤数据
        comboEx: [{
            text: '状态',
            key: 'status',
            datacode: 'DKZT',
            value: 'DKZT-WFP'
        }, {
            text: '已邮件',
            key: 'isSended',
            data: [{
                value: 0,
                text: '否'
            }, {
                value: 1,
                text: '是'
            }]
        }],
        searchitems: [{
            display: '客户名称',
            name: 'incomeUnitName'
        }, {
            display: '客户省份',
            name: 'province'
        }, {
            display: '系统单据号',
            name: 'incomeNo'
        }, {
            display: '到款金额',
            name: 'incomeMoney'
        }, {
            display: '进账单号',
            name: 'inFormNum'
        }, {
            display: '到款日期',
            name: 'incomeDateSearch'
        }],
        sortname: 'updateTime'
    });
});