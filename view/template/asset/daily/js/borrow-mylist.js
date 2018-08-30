// 用于新增/修改后回调刷新表格

var show_page = function(page) {
	$("#borrowGrid").yxsubgrid('reload');
};

$(function() {
	$("#borrowGrid").yxsubgrid({

		model : 'asset_daily_borrow',
		title : '我的固定资产借用信息',
		action : 'myPageJson',
		showcheckbox : false,
		// 列信息
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		}, {
			display : '需求单编号',
			name : 'requireCode',
			sortable : true,
			width : 120
		}, {
			display : '借用单编号',
			name : 'billNo',
			sortable : true,
			width : 120
		}, {
			display : '借用申请人id',
			name : 'chargeManId',
			sortable : true,
			hide : true
		}, {
			display : '借用申请人',
			name : 'chargeMan',
			sortable : true
		}, {
			display : '借用部门id',
			name : 'deptId',
			sortable : true,
			hide : true
		}, {
			display : '借用部门',
			name : 'deptName',
			sortable : true
		}, {
			display : '借用日期',
			name : 'borrowDate',
			sortable : true
		}, {
			display : '预计归还日期',
			name : 'predictDate',
			sortable : true
		}, {
			display : '责任人id',
			name : 'reposeManId',
			sortable : true,
			hide : true
		}, {
			display : '责任人',
			name : 'reposeMan',
			sortable : true
		}, {
			display : '备注',
			name : 'remark',
			sortable : true,
			hide : true
		}, {
			name : 'docStatus',
			display : '单据状态',
			process : function(v) {
				if (v == 'BFGH') {
					return '部分归还';
				} else if (v == 'YGH') {
					return '已归还';
				} else {
					return '未归还';
				}
			},
			sortable : true
		}, {
			name : 'isSign',
			display : '签收状态',
			process : function(v) {
				if (v == '0') {
					return '未签收';
				} else if (v == '1') {
					return '已签收';
				} else {
					return v;
				}
			},
			sortable : true
		}],
		// 列表页加上显示从表
		subGridOptions : {
			url : '?model=asset_daily_borrowitem&action=pageJson',
			param : [{
				paramId : 'borrowId',
				colId : 'id'
			}],
			colModel : [{
				display : '卡片编号',
				name : 'assetCode',
				width : 160
			}, {
				display : '资产名称',
				name : 'assetName',
				width : 150
			}, {
				display : '购入日期',
				name : 'buyDate',
				type : 'date',
				width : 80
			}, {
				display : '规格型号',
				name : 'spec',
				tclass : 'txtshort'
			}, {
				display : '预计使用期间数',
				name : 'estimateDay',
				tclass : 'txtshort',
				readonly : true
			}, {
				display : '已经使用期间数',
				name : 'alreadyDay',
				tclass : 'txtshort',
				readonly : true
			}, {
				display : '剩余使用期间数',
				name : 'residueYears',
				tclass : 'txtshort'
			}, {
				display : '备注',
				name : 'remark',
				tclass : 'txt',
				width : 150
			}]
		},

		// toDelConfig : {
		// showMenuFn : function(row) {
		// if (row.ExaStatus == '待提交' || row.ExaStatus == '打回') {
		// return true;
		// }
		// return false;
		// }
		// },
		isDelAction : false,
		isViewAction : false,
		isEditAction : false,
		isAddAction : false,
		toAddConfig : {
			formWidth : 900,
			/**
			 * 新增表单默认高度
			 */
			formHeight : 400
		},
		menusEx : [{
			name : 'view',
			text : '查看',
			icon : 'view',
			action : function(row, rows, grid) {
				if (row) {
					showThickboxWin("?model=asset_daily_borrow&action=init&id="
							+ row.id
							+ "&skey="
							+ row['skey_']
							+ "&perm=view&placeValuesBefore&TB_iframe=true&modal=false&height=400&width=900");
				} else {
					alert("请选中一条数据");
				}
			}
		}, {
			name : 'edit',
			text : '签收',
			icon : 'edit',
			showMenuFn : function(row) {
				if (row.isSign == '0' && row.reposeManId == $('#userId').val()) {
					return true;
				} else
					return false;
			},
			action : function(row) {
				if (confirm('确认是否要签收？')) {
					$.ajax({
						type : 'POST',
						url : '?model=asset_daily_borrow&action=toSign',
						data : {
							id : row.id
						},
						// async: false,
						success : function(data) {
							if (data == 1) {
								alert("签收成功");
								show_page();
							}else{
								alert("签收失败")
							}
							return false;
						}
					});
				}
			}
		}, {
			name : 'edit',
			text : '归还资产',
			icon : 'edit',
			showMenuFn : function(row) {
				if (row.isSign == '1' && row.docStatus != 'YGH'
						&& row.reposeManId == $('#userId').val()) {
					return true;
				} else
					return false;
			},
			action : function(row, rows, grid) {
				if (row) {
					showThickboxWin("?model=asset_daily_return&action=toReturnBorrow&borrowNo="
							+ row.billNo
							+ "&borrowId="
							+ row.id
							+ "&placeValuesBefore&TB_iframe=true&modal=false&height=400&&width=900");
				} else {
					alert("请选中一条数据");
				}
			}
		}],

		// 快速搜索
		searchitems : [{
			display : '需求单编号',
			name : 'requireCode'
		}, {
			display : '借用单编号',
			name : 'billNo'
		}, {
			display : '借用日期',
			name : 'borrowDate'
		}, {
			display : '借用部门',
			name : 'deptName'
		}, {
			display : "卡片编号",
			name : 'productCode'
		}, {
			display : "资产名称",
			name : 'productName'
//		}, {
//			display : '借用客户',
//			name : 'borrowCustome'
		}],
		comboEx : [{
			text : '签收状态',
			key : 'isSign',
			data : [{
				text : '未签收',
				value : '0'
			}, {
				text : '已签收',
				value : '1'
			}]
		}, {
			text : '单据状态',
			key : 'docStatus',
			data : [{
				text : '未归还',
				value : 'WGH'
			}, {
				text : '部分归还',
				value : 'BFGH'
			}, {
				text : '已归还',
				value : 'YGH'
			}]
		}],
		// 默认搜索字段名
		sortname : "id",
		// 默认搜索顺序
		sortorder : "DESC"

	});

});