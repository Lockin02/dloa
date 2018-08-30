var show_page = function(page) {
	$("#requirementGrid").yxsubgrid("reload");
};
$(function() {
	$("#requirementGrid").yxsubgrid({
		model : 'asset_purchase_apply_apply',
		action : 'deliJson',
		param : {
			"ExaStatus" : '完成',
			"state" : '已提交'
		},
		title : '资产采购申请',
		showcheckbox : false,
		isAddAction : false,
		isEditAction : false,
		isDelAction : false,
		isViewAction : false,
		// 列信息
		colModel : [{
					display : 'id',
					name : 'id',
					sortable : true,
					hide : true
				}, {
					name : 'formCode',
					display : '单据编号',
					sortable : true,
					width : 120
				}, {
					name : 'applyTime',
					display : '申请日期',
					sortable : true
				}, {
					name : 'applicantName',
					display : '申请人名称',
					sortable : true,
					width : 120
				}, {
					name : 'userName',
					display : '使用人名称',
					sortable : true,
					width : 120
				}, {
					name : 'useDetName',
					display : '使用部门',
					sortable : true
				}, {
//					name : 'purchCategory',
//					display : '采购种类',
//					sortable : true,
//					datacode : 'CGZL',
//					width : 120
//				}, {
					display : '确认状态',
					name : 'productSureStatus',
					process : function(v, data) {
						if (v == 0) {
							return "未确认";
						} else if (v == 1) {
							return "已确认";
						} else {
							return "部分确认";
						}
					}
				}, {
					name : 'assetUse',
					display : '资产用途',
					sortable : true,
					width : 120
				}],
		// 主从表格设置
		subGridOptions : {
			subgridcheck : true,
			/**
			 * 显示多选行条件
			 */
			checkShowFn : function(rowData) {
				if (rowData.productName) {
					return true;
				}
				return false;
			},
			url : '?model=asset_purchase_apply_applyItem&action=delPageJson',
			param : [{
						paramId : 'applyId',
						colId : 'id'
					}],
			colModel : [{
						display : '物料名称',
						name : 'productName',
						tclass : 'readOnlyTxtItem'
						,
						process : function(v,row){
							if( v=='' ){
								return row.inputProductName;
							}else{
								return v;
							}
						}
					}, {
						display : '规格',
						name : 'pattem',
						tclass : 'readOnlyTxtItem'
					}, {
						display : '单位',
						name : 'unitName',
						tclass : 'readOnlyTxtItem'
					}, {
						display : '申请数量',
						name : 'purchAmount',
						tclass : 'txtshort'
					}, {
						display : '下达任务数量',
						name : 'issuedAmount',
						tclass : 'txtshort'
					}, {
						display : '希望交货日期',
						name : 'dateHope',
						type : 'date'
					}, {
						display : '备注',
						name : 'remark',
						tclass : 'txt'
					}, {
						display : '采购部门',
						name : 'purchDept',
						tclass : 'txt',
						process : function($input, rowData) {
							if (rowData.purchDept == '0') {
								return '行政部';
							} else if (rowData.purchDept == '1') {
								return '交付部';
							}
						}
					}]
		},
		// 扩展右键菜单

		menusEx : [{
			text : '查看',
			icon : 'view',
			action : function(row) {
				showThickboxWin('?model=asset_purchase_apply_apply&action=initDeliRequire&id='
						+ row.id
						+ '&placeValuesBefore&TB_iframe=true&modal=false&height=900&width=1000');
			}
		}, {
//			text : '拆分采购',
//			icon : 'add',
//			action : function(row) {
//				showThickboxWin('?model=asset_purchase_apply_apply&action=initDelAssign&id='
//						+ row.id
//						+ '&placeValuesBefore&TB_iframe=true&modal=false&height=1000&width=1100');
//			}
//		}, {
			text : '分配人员',
			icon : 'add',
			showMenuFn : function(row) {
				if (row.productSureStatus != '1') {
					return true;
				}
				return false;
			},
			action : function(row) {
				var id = $(this).attr('id');
				var skeyVal = $("#check" + id).val();
				location = '?model=asset_purchase_apply_apply&action=toConfirmUser&id='
						+ row.id
						+ '&placeValuesBefore&TB_iframe=true&modal=false&height=1000&width=1100';
			}
		}, {
			text : '下达采购任务',
			icon : 'add',
			showMenuFn : function(row) {
				if (row.productSureStatus == '1') {
					return true;
				}
				return false;
			},
			action : function(row) {
				location = '?model=purchase_task_basic&action=toAddTask&id='
						+ row.id
						+ '&placeValuesBefore&TB_iframe=true&modal=false&height=1000&width=1100';
			}
		}],
		buttonsEx : [{
			text : '下达采购任务',
			icon:'add',
			action : function() {

				var ids = $("#requirementGrid")
						.yxsubgrid("getAllSubSelectRowCheckIds");
				location = '?model=purchase_task_basic&action=toAddTaskByIds&idArr='
						+ ids
						+ '&placeValuesBefore&TB_iframe=true&modal=false&height=1000&width=1100';
			}
		}],
		/**
		 * 快速搜索
		 */
		searchitems : [{
					display : '单据编号',
					name : 'formCode'
				}, {
					display : '申请日期',
					name : 'applyTime'
				}, {
					display : '申请人',
					name : 'applicantName'
				}, {
					display : '使用人名称',
					name : 'userName'
				}, {
					display : '物料名称',
					name : 'productName'
				}]
	});
});