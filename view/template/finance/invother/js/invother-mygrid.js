var show_page = function(page) {
	$("#myinvotherGrid").yxsubgrid("reload");
};
$(function() {
	$("#myinvotherGrid").yxsubgrid({
		model: 'finance_invother_invother',
		action: 'myInvotherListPageJson',
		title: '应付其他发票',
		isDelAction: false,
		param: {
			createId: $("#createId").val()
		},
		//列信息
		colModel: [{
			display: 'id',
			name: 'id',
			sortable: true,
			hide: true
		}, {
			name: 'invoiceCode',
			display: '发票编号',
			sortable: true,
			width: 140,
			hide: true
		}, {
			name: 'invoiceNo',
			display: '发票号码',
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
			process: function(v) {
				return moneyFormat2(v);
			}
		}, {
			name: 'formAssessment',
			display: '单据税额',
			sortable: true,
			width: 80,
			process: function(v) {
				return moneyFormat2(v);
			}
		}, {
			name: 'formCount',
			display: '价税合计',
			sortable: true,
			width: 80,
			process: function(v) {
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
			name: 'ExaStatus',
			display: '审核状态',
			sortable: true,
			width: 80,
			process: function(v) {
				if (v == 1) {
					return '已审核';
				} else {
					return '未审核';
				}
			}
		}, {
			name: 'ExaDT',
			display: '审核日期',
			sortable: true,
			hide: true
		}, {
			name: 'exaMan',
			display: '审核人',
			sortable: true,
			width: 80
		}, {
			name: 'belongId',
			display: '所属发票id',
			sortable: true,
			hide: true
		}, {
			name: 'updateTime',
			display: '最近更新',
			sortable: true,
			width: 130
		}
		],
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
				process: function(v, row, parentRow) {
					return moneyFormat2(v, 6, 6);
				}
			}, {
				name: 'taxPrice',
				display: '含税单价',
				process: function(v) {
					return moneyFormat2(v, 6, 6);
				}
			}, {
				name: 'assessment',
				display: '税额',
				process: function(v) {
					return moneyFormat2(v);
				},
				width: 70
			}, {
				name: 'amount',
				display: '金额',
				process: function(v) {
					return moneyFormat2(v);
				},
				width: 80
			}, {
				name: 'allCount',
				display: '价税合计',
				process: function(v) {
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
			toAddFn: function() {
				showModalWin("?model=finance_invother_invother&action=toAdd");
			}
		},
		toEditConfig: {
			showMenuFn: function(row) {
				return row.ExaStatus == "0";
			},
			toEditFn: function(p, g) {
				var rowObj = g.getSelectedRow();
				var rowData = rowObj.data('data');
				showModalWin("?model=finance_invother_invother&action=toEdit&id=" + rowData[p.keyField]);
			}
		},
		toViewConfig: {
			toViewFn: function(p, g) {
				var rowObj = g.getSelectedRow();
				var rowData = rowObj.data('data');
				showModalWin("?model=finance_invother_invother&action=toView&id=" + rowData[p.keyField]);
			}
		},
		menusEx: [{
			text: "删除",
			icon: 'delete',
			showMenuFn: function(row) {
				return row.ExaStatus;
			},
			action: function(row) {
				if (window.confirm(("确定要删除?"))) {
					$.ajax({
						type: "POST",
						url: "?model=finance_invother_invother&action=ajaxdeletes",
						data: {
							id: row.id
						},
						success: function(msg) {
							if (msg == 1) {
								alert('删除成功！');
								show_page(1);
							} else {
								alert("删除失败! ");
							}
						}
					});
				}
			}
		}],
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
		}],
		comboEx: [{
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