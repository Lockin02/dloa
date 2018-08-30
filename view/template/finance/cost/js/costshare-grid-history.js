var show_page = function () {
    $("#costshareGrid").yxgrid("reload");
};

$(function () {
    $("#costshareGrid").yxgrid({
        model: 'finance_cost_costshare',
        action: 'pageJsonHistory',
        param: {objId: $("#objId").val(), objType: $("#objType").val()},
        title: '分摊明细列表',
        isOpButton: false,
        isAddAction: false,
        isEditAction: false,
        isViewAction: false,
        isDelAction: false,
        showcheckbox: false,
        //列信息
        colModel: [{
            display: 'id',
            name: 'id',
            sortable: true,
            hide: true
        }, {
			name: 'submitStatus',
			display: '提交',
			width: 25,
			align: 'center',
			process: function (v, row) {
				if (row.auditStatus && $.inArray(parseInt(row.auditStatus), [1,2]) > -1) {
					return '<img title="已提交" src="images/icon/ok3.png" style="width:15px;height:15px;">';
				}
			}
		}, {
            name: 'auditStatus',
            display: '审核',
            width: 25,
            align: 'center',
            process: function (v, row) {
				if (v == "1") {
					return '<img title="审核人[' + row.auditor + ']\n审核日期[' + row.auditDate
						+ ']" src="images/icon/ok3.png" style="width:15px;height:15px;">';
				}
            }
        }, {
            name: 'hookStatus',
            display: '勾稽',
            align: 'center',
            process: function (v) {
                switch (v) {
                    case '1' :
                        return '<img src="images/icon/ok3.png" title="已勾稽" style="width:15px;height:15px;">';
                    case '2' :
                        return '<img src="images/icon/ok2.png" title="部分勾稽" style="width:15px;height:15px;">';
                    default :
                        return;
                }
            },
            width: 25
        }, {
            name: 'currency',
            display: '币种',
            width: 40
        }, {
            name: 'costMoney',
            display: '分摊金额',
            process: function (v) {
				if (v < 0) {
					return '<span class="red">' + moneyFormat2(v) + '</span>';
				} else {
					return moneyFormat2(v);
				}
            },
            width: 80,
            sortable: true
        }, {
			name: 'hookMoney',
			display: '已勾稽金额',
			process: function (v) {
				if (v < 0) {
					return '<span class="red">' + moneyFormat2(v) + '</span>';
				} else {
					return moneyFormat2(v);
				}
			},
			width: 80,
			sortable: true
		}, {
			name: 'unHookMoney',
			display: '未勾稽金额',
			process: function (v) {
				if (v < 0) {
					return '<span class="red">' + moneyFormat2(v) + '</span>';
				} else {
					return moneyFormat2(v);
				}
			},
			width: 80,
			sortable: true,
			hide: true
		}, {
            name: 'hookMoney',
            display: '累计勾稽金额',
            align: 'right',
            process: function (v) {
                if (v < 0) {
                    return '<span class="red">' + moneyFormat2(v) + '</span>';
                } else {
                    return moneyFormat2(v);
                }
            },
            width: 80,
            sortable: true,
            hide: true
        }, {
            name: 'unHookMoney',
            display: '未勾稽金额',
            align: 'right',
            process: function (v) {
                if (v < 0) {
                    return '<span class="red">' + moneyFormat2(v) + '</span>';
                } else {
                    return moneyFormat2(v);
                }
            },
            width: 80,
            sortable: true,
            hide: true
        }, {
            name: 'companyName',
            display: '公司主体',
            sortable: true,
            width: 60,
            hide: true
        }, {
            name: 'moduleName',
            display: '所属板块',
            sortable: true,
            width: 60
        }, {
            name: 'belongCompanyName',
            display: '归属公司',
            sortable: true,
            width: 60
        }, {
            name: 'objId',
            display: '源单id',
            sortable: true,
            hide: true
        }, {
            name: 'objType',
            display: '源单类型',
            sortable: true,
            width: 60,
            process: function (v) {
                switch (v) {
                    case '1' :
                        return '赔偿单';
                    case '2' :
                        return '其他合同';
                    default :
                        return v;
                }
            },
            hide: true
        }, {
            name: 'objCode',
            display: '源单编号',
            sortable: true,
            width: 120,
            process: function (v, row) {
                if (v != "") {
                    return "<a href='javascript:void(0)' onclick='viewInfo(\"" + row.objId + "\",\"" + row.objType
                    + "\")'>" + v + "</a>";
                }
            },
            hide: true
        }, {
            name: 'supplierName',
            display: '供应商',
            sortable: true,
            width: 120,
            hide: true
        }, {
            name: 'inPeriod',
            display: '入账期间',
            sortable: true,
            width: 60
        }, {
            name: 'belongPeriod',
            display: '归属期间',
            sortable: true,
            width: 60
        }, {
            name: 'detailType',
            display: '业务类型',
            sortable: true,
            width: 70,
            process: function (v) {
                switch (v) {
                    case '1' :
                        return '部门费用';
                    case '2' :
                        return '合同项目费用';
                    case '3' :
                        return '研发项目费用';
                    case '4' :
                        return '售前费用';
                    case '5' :
                        return '售后费用';
                    default :
                        return v;
                }
            }
        }, {
            name: 'parentTypeId',
            display: 'parentTypeId',
            hide: true
        }, {
            name: 'parentTypeName',
            display: '费用明细上级',
            hide: true
        }, {
            name: 'costTypeId',
            display: 'costTypeId',
            hide: true
        }, {
            name: 'costTypeName',
            display: '费用明细',
            width: 70
        }, {
            name: 'headDeptName',
            display: '二级部门',
            sortable: true,
            width: 80,
            hide: true
        }, {
            name: 'belongDeptName',
            display: '归属部门',
            sortable: true,
            width: 80
        }, {
            name: 'chanceCode',
            display: '商机编号',
            sortable: true
        }, {
            name: 'projectCode',
            display: '项目编号',
            sortable: true
        }, {
            name: 'projectName',
            display: '项目名称',
            sortable: true
        }, {
            name: 'contractCode',
            display: '合同编号',
            sortable: true
        }, {
            name: 'customerName',
            display: '客户名称',
            sortable: true,
            width: 150
        }, {
            name: 'customerType',
            display: '客户类型',
            sortable: true
        }, {
            name: 'province',
            display: '所属省份',
            sortable: true,
            width: 70
        }],
        menusEx: [{
            text: '勾稽记录',
            icon: 'edit',
            showMenuFn: function (row) {
                return row.hookStatus != '0';
            },
            action: function (row) {
                showOpenWin("?model=finance_cost_costHook&hookId="
                + row.id, 1, 700, 1100, row.id);
            }
        }],
        //过滤数据
        comboEx: [{
            text: '审核状态',
            key: 'auditStatus',
            data: [{
                text: '未审核',
                value: '2'
            }, {
                text: '已审核',
                value: '1'
            }]
        }, {
            text: '勾稽状态',
            key: 'hookStatusArr',
            data: [{
                text: '未勾稽',
                value: '0'
            }, {
                text: '已勾稽',
                value: '1'
            }, {
                text: '部分勾稽',
                value: '2'
            }]
        }],
        searchitems: [{
            display: "商机编号",
            name: 'chanceCodeSearch'
        }, {
            display: "项目名称",
            name: 'projectNameSearch'
        }, {
            display: "项目编号",
            name: 'projectCodeSearch'
        }, {
            display: "合同编号",
            name: 'contractCodeSearch'
        }]
    });
});