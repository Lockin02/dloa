var show_page = function () {
    $("#invotherGrid").yxsubgrid("reload");
};

$(function () {

	// 源单类型获取
	var sourceTypeArr = [];
	var o = {
		value: 'none',
		text: '无源单'
	};
	sourceTypeArr.push(o);

	var datadictData = getData('YFQTYD');
	if (datadictData) {
		for (var i = 0; i < datadictData.length; i++) {
			var o = {
				value: datadictData[i].dataCode,
				text: datadictData[i].dataName
			};
			sourceTypeArr.push(o);
		}
	}

    $("#invotherGrid").yxsubgrid({
        model: 'finance_invother_invother',
        title: '应付其他发票',
        isDelAction: false,
        customCode: 'invotherGrid',
        isOpButton: false,
        //列信息
        colModel: [{
            display: 'id',
            name: 'id',
            sortable: true,
            hide: true
        }, {
            name: 'status',
            display: '提交',
            align: 'center',
            process: function (v) {
                switch (v) {
                    case '1' :
                        return '<img src="images/icon/ok3.png" title="已提交" style="width:15px;height:15px;">';
                    case '2' :
                        return '<img src="images/icon/ok1.png" title="已打回" style="width:15px;height:15px;">';
                    default :
                        return;
                }
            },
            width: 25
        }, {
            name: 'ExaStatus',
            display: '审核',
            width: 25,
            align: 'center',
            process: function (v, row) {
                switch (v) {
                    case '1' :
                        return '<img title="审核人[' + row.exaMan + ']\n审核日期[' + row.ExaDT
                        + ']" src="images/icon/ok3.png" style="width:15px;height:15px;">';
                    default :
                }
            }
        }, {
            name: 'invoiceCode',
            display: '发票编号',
            sortable: true,
            width: 140,
            process: function (v, row) {
                if (row.isRed == "1") {
                    return "<a href='#' style='color:red' onclick='showModalWin(\"?model=finance_invother_invother&action=toView&id=" +
                        row.id + '&skey=' + row.skey_ + "\")'>" + v + "</a>";
                } else {
                    return "<a href='#' onclick='showModalWin(\"?model=finance_invother_invother&action=toView&id=" +
                        row.id + '&skey=' + row.skey_ + "\")'>" + v + "</a>";
                }
            },
            hide: true
        }, {
            name: 'invoiceNo',
            display: '发票号码',
            sortable: true,
            process: function (v, row) {
                if (row.isRed == "1") {
                    return "<a href='#' style='color:red' onclick='showModalWin(\"?model=finance_invother_invother&action=toView&id=" +
                        row.id + '&skey=' + row.skey_ + "\")'>" + v + "</a>";
                } else {
                    return "<a href='#' onclick='showModalWin(\"?model=finance_invother_invother&action=toView&id=" +
                        row.id + '&skey=' + row.skey_ + "\")'>" + v + "</a>";
                }
            }
        }, {
            name: 'sourceType',
            display: '源单类型',
            sortable: true,
            width: 80,
            datacode: 'YFQTYD'
        }, {
            name: 'menuNo',
            display: '源单编号',
            sortable: true
        }, {
            name: 'supplierName',
            display: '供应商名称',
            sortable: true,
            width: 120
        }, {
            name: 'formDate',
            display: '单据日期',
            sortable: true,
            width: 80
        }, {
            name: 'payDate',
            display: '付款日期',
            sortable: true,
            width: 80
        }, {
            name: 'isRed',
            display: '是否红字',
            sortable: true,
            hide: true
        }, {
            name: 'taxRate',
            display: '税率(%)',
            sortable: true,
            width: 60
        }, {
            name: 'invType',
            display: '发票类型',
            sortable: true,
            datacode: 'FPLX',
            width: 80
        }, {
            name: 'formNumber',
            display: '总数量',
            sortable: true,
            width: 80
        }, {
            name: 'amount',
            display: '总金额',
            sortable: true,
            width: 80,
            process: function (v) {
                return moneyFormat2(v);
            }
        }, {
            name: 'formAssessment',
            display: '单据税额',
            sortable: true,
            width: 80,
            process: function (v) {
                return moneyFormat2(v);
            }
        }, {
            name: 'formCount',
            display: '价税合计',
            sortable: true,
            width: 80,
            process: function (v) {
                return moneyFormat2(v);
            }
        }, {
            name: 'hookMoney',
            display: '勾稽金额',
            sortable: true,
            width: 80,
            process: function (v) {
                return moneyFormat2(v);
            }
        }, {
            name: 'departments',
            display: '部门',
            sortable: true,
            width: 80
        }, {
            name: 'salesman',
            display: '业务员',
            sortable: true,
            width: 80
        }, {
            name: 'businessBelongName',
            display: '归属公司',
            sortable: true,
            width: 80
        }, {
            name: 'ExaDT',
            display: '审核日期',
            sortable: true,
            hide: true
        }, {
            name: 'exaMan',
            display: '审核人',
            sortable: true,
            width: 80,
            hide: true
        }, {
            name: 'belongId',
            display: '所属发票id',
            sortable: true,
            hide: true
        }, {
            name: 'updateTime',
            display: '最近更新',
            sortable: true,
            width: 130,
            hide: true
        }],
        // 主从表格设置
        subGridOptions: {
            url: '?model=finance_invother_invotherdetail&action=pageJson',
            param: [
                {
                    paramId: 'mainId',// 传递给后台的参数名称
                    colId: 'id'// 获取主表行数据的列名称
                }
            ],
            colModel: [{
                name: 'productName',
                display: '发票名目',
                width: 140
            }, {
                display: '数量',
                name: 'number'
            }, {
                name: 'price',
                display: '单价',
                process: function (v) {
                    return moneyFormat2(v, 6, 6);
                }
            }, {
                name: 'taxPrice',
                display: '含税单价',
                process: function (v) {
                    return moneyFormat2(v, 6, 6);
                }
            }, {
                name: 'assessment',
                display: '税额',
                process: function (v) {
                    return moneyFormat2(v);
                },
                width: 70
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
                display: '源单编号',
                width: 120
            }]
        },
        toAddConfig: {
            toAddFn: function () {
                showModalWin("?model=finance_invother_invother&action=toAdd");
            }
        },
        toEditConfig: {
            showMenuFn: function (row) {
                return row.status == 0 || row.status == 2;
            },
            toEditFn: function (p, g) {
                var rowObj = g.getSelectedRow();
                var rowData = rowObj.data('data');
                showModalWin("?model=finance_invother_invother&action=toEdit&id=" + rowData[p.keyField]);
            }
        },
        toViewConfig: {
            toViewFn: function (p, g) {
                var rowObj = g.getSelectedRow();
                var rowData = rowObj.data('data');
                showModalWin("?model=finance_invother_invother&action=toView&id=" + rowData[p.keyField]);
            }
        },
        buttonsEx: [{
            name: 'add',
            text: "导出EXCEL",
            icon: 'excel',
            action: function () {
                showThickboxWin('?model=finance_invother_invother&action=toExportExcel'
                + "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=300&width=600");
            }
        }, {
            name: 'add',
            text: "打开列表",
            icon: 'search',
            action: function () {
                showModalWin('?model=finance_invother_invother&action=listInfo', 1);
            }
        }],
        menusEx: [
            {
                text: '审核',
                icon: 'edit',
                showMenuFn: function (row) {
                    return row.ExaStatus == 0 && row.status == 1;
                },
                action: function (row, rows, grid) {
                    showModalWin('?model=finance_invother_invother&action=toVerify&id=' + row.id + "&skey=" + row.skey_);
                }
            },
            {
                text: "删除",
                icon: 'delete',
                showMenuFn: function (row) {
                    return row.status == 0 || row.status == 2;
                },
                action: function (row) {
                    if (window.confirm(("确定要删除?"))) {
                        $.ajax({
                            type: "POST",
                            url: "?model=finance_invother_invother&action=ajaxdeletes",
                            data: {
                                id: row.id
                            },
                            success: function (msg) {
                                if (msg == 1) {
                                    alert('删除成功！');
                                    show_page();
                                } else {
                                    alert("删除失败! ");
                                }
                            }
                        });
                    }
                }
            },
            {
                text: "反审核",
                icon: 'edit',
                showMenuFn: function (row) {
                    return row.ExaStatus == 1;
                },
                action: function (row) {
                    if (window.confirm(("确定要反审核?"))) {
                        $.ajax({
                            type: "POST",
                            url: "?model=finance_invother_invother&action=unaudit",
                            data: {
                                "id": row.id
                            },
                            success: function (msg) {
                                if (msg == 1) {
                                    alert('反审核成功！');
                                    show_page();
                                } else {
                                    alert('反审核失败!');
                                }
                            }
                        });
                    }
                }
            }
        ],
        searchitems: [{
            display: "发票号码",
            name: 'invoiceNoSearch'

        }, {
            display: "发票编号",
            name: 'invoiceCodeSearch'

        }, {
            display: "供应商",
            name: 'supplierName'

        }, {
            display: "部门名称",
            name: 'departments'

        }, {
            display: "业务人员",
            name: 'salesman'

        }, {
            display: "审核人",
            name: 'exaMan'
        }, {
            display: "源单编号",
            name: 'menuNoSearch'
        }],
        comboEx: [{
			text: "源单类型",
			key: 'sourceType',
			data: sourceTypeArr
		}, {
            text: "单据状态",
            key: 'status',
            value: '1',
            data: [{
                text: '保存',
                value: '0'
            }, {
                text: '已提交',
                value: '1'
            }, {
                text: '已打回',
                value: '2'
            }]
        }, {
			text: "审核状态",
			key: 'ExaStatus',
			value: '0',
			data: [{
				text: '未审核',
				value: '0'
			}, {
				text: '已审核',
				value: '1'
			}]
		}]
    });
});