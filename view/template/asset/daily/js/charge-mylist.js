/**
 * 资产领用信息列表
 *
 * @linzx
 */
var show_page = function(page) {
	$("#datadictList").yxsubgrid("reload");
};
$(function() {
	$("#datadictList").yxsubgrid({
		model : 'asset_daily_charge',
		title : '我的资产领用',
		action : 'myPageJson',
		isToolBar : true,
		// isViewAction : false,
		isEditAction : false,
		isAddAction : false,
		isDelAction : false,
		isViewAction : false,
		showcheckbox : false,
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		}, {
			display : '需求单编号',
			name : 'requireCode',
			sortable : true,
			width : 140
		}, {
			display : '领用单编号',
			name : 'billNo',
			sortable : true,
			width : 140
		}, {
			display : '领用日期',
			name : 'chargeDate',
			sortable : true
		}, {
			display : '领用部门id',
			name : 'deptId',
			sortable : true,
			hide : true
		}, {
			display : '领用部门名称',
			name : 'deptName',
			sortable : true
		}, {
			display : '领用人Id',
			name : 'chargeManId',
			sortable : true,
			hide : true
		}, {
			display : '领用人',
			name : 'chargeMan',
			sortable : true
		}, {
			display : '申请人',
			name : 'createName',
			sortable : true
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
			url : '?model=asset_daily_chargeitem&action=pageJson',
			param : [{
				paramId : 'allocateID',
				colId : 'id'
			}],
			colModel : [
					// {
					// display:'序号',
					// name : 'sequence'
					// },
					{
						display : '卡片编号',
						name : 'assetCode',
						width : 160
					}, {
						display : '资产名称',
						name : 'assetName',
						width : 150
					}, {
						display : '规格型号',
						name : 'spec',
						tclass : 'txtshort',
						readonly : true,
						width : 100
					}, {
						display : '购置日期',
						name : 'buyDate',
						// type : 'date',
						tclass : 'txtshort',
						readonly : true,
						width : 80
					}, {
//						display : '预计使用期间数',
//						name : 'estimateDay',
//						tclass : 'txtshort',
//						readonly : true
//					}, {
//						display : '已经使用期间数',
//						name : 'alreadyDay',
//						tclass : 'txtshort',
//						readonly : true
//					}, {
						display : '剩余使用期间数',// 等于卡片的预计使用期间数减去已使用期间数
						name : 'residueYears',
						tclass : 'txtshort',
						readonly : true
					}, {
						display : '备注',
						name : 'remark',
						tclass : 'txt',
						readonly : true,
						width : 180
					}]
		},
		// toAddConfig : {
		// formWidth : 900,
		// formHeight : 400,
		// showMenuFn : function(row) {
		// if (false) {
		// return true;
		// }
		// return false;
		// }
		// },
		// toEditConfig : {
		// formWidth : 900,
		// formHeight : 400,
		// showMenuFn : function(row) {
		// if (false) {
		// return true;
		// }
		// return false;
		// }
		// },
		// toViewConfig : {
		// formWidth : 900,
		// formHeight : 400
		// },

		// 扩展右键菜单
		menusEx : [{
			text : '查看',
			icon : 'view',
			action : function(row, rows, grid) {
				if (row) {
					showThickboxWin("?model=asset_daily_charge&action=init&id="
							+ row.id
							+ '&perm=view'
							+ "&placeValuesBefore&TB_iframe=true&modal=false&height=400&&width=900");
				} else {
					alert("请选中一条数据");
				}
			}
		}, {
			name : 'edit',
			text : '归还资产',
			icon : 'edit',
			showMenuFn : function(row) {
				if (row.isSign == '1' && row.docStatus != 'YGH'
						&& row.chargeManId == $('#userId').val()) {
					return true;
				} else
					return false;
			},
			action : function(row, rows, grid) {
				if (row) {
					showThickboxWin("?model=asset_daily_return&action=toReturnCharge&borrowNo="
							+ row.billNo
							+ "&borrowId="
							+ row.id
							+ "&placeValuesBefore&TB_iframe=true&modal=false&height=400&&width=900");
				} else {
					alert("请选中一条数据");
				}
			}
		}, {
			text : '签收',
			icon : 'edit',
			showMenuFn : function(row) {
				if (row.isSign == "0" && row.chargeManId == $('#userId').val()) {
					return true;
				} else {
					return false;
				}

			},
			action : function(row) {
				if (window.confirm(("确定签收吗？"))) {

					$.ajax({
						type : "POST",
						url : "?model=asset_daily_charge&action=toSign&id="
								+ row.id,
						success : function(msg) {
							if (msg == 1) {
								alert('签收成功！');
								$("#datadictList").yxsubgrid("reload");
							} else {
								alert('签收失败！');
							}
						}
					});
				}
			}
				// }, {
				// text : '删除',
				// icon : 'delete',
				// showMenuFn : function(row) {
				// if (row.ExaStatus == '待提交' || row.ExaStatus == '打回') {
				// return true;
				// }
				// return false;
				// },
				// action : function(row) {
				// if (window.confirm(("确定删除吗？"))) {
				// $.ajax({
				// type : "GET",
				// url : "?model=asset_daily_charge&action=deletes&id="
				// + row.id,
				// success : function(msg) {
				// $("#datadictList").yxsubgrid("reload");
				// }
				// });
				// }
				// }
		}],

		searchitems : [{
			display : '需求单编号',
			name : 'requireCode'
		}, {
			display : '领用单编号',
			name : 'billNo'
		}, {
			display : "卡片编号",
			name : 'productCode'
		}, {
			display : "资产名称",
			name : 'productName'
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
		// 业务对象名称
		// boName : '全部',
		// 默认搜索字段名
		sortname : "id",
		// 默认搜索顺序 降序DESC 升序ASC
		sortorder : "DESC"

	});
});
