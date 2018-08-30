/**
 * 资产归还信息列表
 *
 * @linzx
 */
var show_page = function(page) {
	$("#datadictList").yxsubgrid("reload");
};
$(function() {
	$("#datadictList").yxsubgrid({
		model : 'asset_daily_return',
		title : '资产归还',
		isToolBar : true,
		// isViewAction : false,
		isEditAction : false,
		isAddAction : false,
		isDelAction : false,
		showcheckbox : false,

		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		}, {
			display : '归还单编号',
			name : 'billNo',
			sortable : true,
			width : 120
		}, {
			display : '借/领单Id',
			name : 'borrowId',
			sortable : true,
			hide : true
		}, {
			display : '借/领单编号',
			name : 'borrowNo',
			width : 110,
			sortable : true
		}, {
			display : '归还类型',
			name : 'returnType',
			sortable : true,
			process : function(val) {
				if (val == "other") {
					return "其它";
				}
				if (val == "oa_asset_borrow") {
					return "借用";
				}
				if (val == "oa_asset_charge") {
					return "领用";
				}
				if (val == "asset") {
					return "独立归还";
				}
			}

		}, {
			display : '归还部门id',
			name : 'deptId',
			sortable : true,
			hide : true
		}, {
			display : '归还部门名称',
			name : 'deptName',
			sortable : true
		}, {
			display : '归还人Id',
			name : 'returnManId',
			sortable : true,
			hide : true
		}, {
			display : '是否签收',
			name : 'isSign',
			sortable : true,
			process : function(val) {
				if (val == "1") {
					return "是";
				} else {
					return "否"
				}
			}
		}, {
			display : '归还人',
			name : 'returnMan',
			sortable : true
		}, {
			display : '归还日期',
			name : 'returnDate',
			sortable : true
		}, {
			display : '审批状态',
			name : 'ExaStatus',
			sortable : true
		}, {
			display : '备注',
			name : 'remark',
			sortable : true
		}],
		// 列表页加上显示从表
		subGridOptions : {
			url : '?model=asset_daily_returnitem&action=pageJson',
			param : [{
				paramId : 'allocateID',
				colId : 'id'
			}],
			colModel : [{
				display : '卡片编号',
				name : 'assetCode',
				width : 130
			}, {
				display : '资产名称',
				name : 'assetName'
			}, {
				display : '规格型号',
				name : 'spec',
				tclass : 'txtshort',
				readonly : true
			}, {
				display : '购置日期',
				name : 'buyDate',
				// type : 'date',
				tclass : 'txtshort',
				readonly : true
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
				tclass : 'txtshort',
				readonly : true
			}, {
				display : '备注',
				name : 'remark',
				tclass : 'txt'
			}]
		},
		toAddConfig : {
			formWidth : 900,
			formHeight : 400
		},
		toEditConfig : {
			formWidth : 900,
			formHeight : 400,
			showMenuFn : function(row) {
				if (row.ExaStatus == "待提交" || row.ExaStatus == "打回") {
					return true;
				}
				return false;
			}
		},
		toViewConfig : {
			formWidth : 900,
			formHeight : 300
		},
		// toDelConfig : {
		// showMenuFn : function(row) {
		// if (row.ExaStatus == '待提交' || row.ExaStatus == '打回') {
		// return true;
		// }
		// return false;
		// }
		// },
		// 扩展右键菜单
		menusEx : [{
			// text : '提交审批',
			// icon : 'add',
			// showMenuFn : function(row) {
			// if (row.ExaStatus == "待提交" || row.ExaStatus == "打回") {
			// return true;
			// }
			// return false;
			// },
			// action : function(row, rows, grid) {
			// if (row) {
			// showThickboxWin('controller/asset/daily/ewf_index_return.php?actTo=ewfSelect&billId='
			// + row.id
			// +
			// "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=750");
			// } else {
			// alert("请选中一条数据");
			// }
			// }
			//
			// }, {
			// name : 'aduit',
			// text : '审批情况',
			// icon : 'view',
			// showMenuFn : function(row) {
			// if ((row.ExaStatus == "完成" || row.ExaStatus == "打回" ||
			// row.ExaStatus == "部门审批")) {
			// return true;
			// }
			// return false;
			// },
			// action : function(row, rows, grid) {
			// if (row) {
			// showThickboxWin("controller/common/readview.php?itemtype=oa_asset_return&pid="
			// + row.id
			// +
			// "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=300&width=800");
			// }
			// }
			// }, {
			name : 'edit',
			text : '签收',
			icon : 'edit',
			showMenuFn : function(row) {
				if (row.isSign == '0') {
					return true;
				} else
					return false;
			},
			action : function(row) {
				if (confirm('确认是否要签收？')) {
					$.ajax({
						type : 'POST',
						url : '?model=asset_daily_return&action=toSign',
						data : {
							id : row.id
						},
						// async: false,
						success : function(data) {
							if (data == 1) {
								alert("签收成功");
								show_page();
							} else {
								alert("签收失败")
							}
							return false;
						}
					});
				}
			}
		}, {
			text : '删除',
			icon : 'delete',
			showMenuFn : function(row) {
				if (row.ExaStatus == '待提交' || row.ExaStatus == '打回') {
					return true;
				}
				return false;
			},
			action : function(row) {
				if (window.confirm(("确定删除吗？"))) {
					$.ajax({
						type : "POST",
						url : "?model=asset_daily_return&action=deletes&id="
								+ row.id,
						success : function(msg) {
							$("#datadictList").yxsubgrid("reload");
						}
					});
				}
			}
		}],

		searchitems : [{
			display : '借/领单编号',
			name : 'borrowNo'
		}, {
			display : '归还单编号',
			name : 'billNo'
		}, {
			display : '归还人',
			name : 'applicat'
		}, {
			display : '归还部门',
			name : 'deptName'
		}, {
			display : "卡片编号",
			name : 'productCode'
		}, {
			display : "资产名称",
			name : 'productName'
		}],
		// comboEx : [{
		// text : '审批状态',
		// key : 'ExaStatus',
		// data : [{
		// text : '部门审批',
		// value : '部门审批'
		// }, {
		// text : '待提交',
		// value : '待提交'
		// }, {
		// text : '完成',
		// value : '完成'
		// }, {
		// text : '打回',
		// value : '打回'
		//			}]
		//		}],
		// 业务对象名称
		//	boName : '全部',
		// 默认搜索字段名
		sortname : "id",
		// 默认搜索顺序 降序DESC 升序ASC
		sortorder : "DESC"

	});
});
