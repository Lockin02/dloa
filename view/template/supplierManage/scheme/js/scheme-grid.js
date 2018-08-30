var show_page = function(page) {
	$("#schemeGrid").yxgrid("reload");
};
$(function() {
	$("#schemeGrid").yxgrid({
		model : 'supplierManage_scheme_scheme',
		action : 'pageJson',
		title : '评估方案',
		showcheckbox : false,
		isDelAction : false,
		isAddAction : false,
		isEditAction : false,
		// 列信息
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		}, {
			name : 'schemeCode',
			display : '方案编码',
			sortable : true
		}, {
			name : 'schemeName',
			display : '方案名称',
			sortable : true
		}, {
			name : 'schemeTypeName',
			display : '方案类型',
			sortable : true,
			width : 150
		}, {
			name : 'formManName',
			display : '制单人',
			sortable : true
		}, {
			name : 'formDate',
			display : '单据时间',
			sortable : true
		}],
//		comboEx : [{
//			text : '审批状态',
//			key : 'ExaStatus',
//			data : [{
//				text : '部门审批',
//				value : '部门审批'
//			}, {
//				text : '待提交',
//				value : '待提交'
//			}, {
//				text : '完成',
//				value : '完成'
//			}, {
//				text : '打回',
//				value : '打回'
//			}]
//		}],
		toAddConfig : {
			formWidth : 900,
			/**
			 * 新增表单默认高度
			 */
			formHeight : 600
		},
		toViewConfig : {
			/**
			 * 查看表单默认宽度
			 */
			formWidth : 900,
			/**
			 * 查看表单默认高度
			 */
			formHeight : 600
		},
		toEditConfig : {
			/**
			 * 编辑表单默认宽度
			 */
			formWidth : 900,
			/**
			 * 编辑表单默认高度
			 */
			formHeight : 600,
			showMenuFn : function(row) {
				if (row.ExaStatus == "待提交" || row.ExaStatus == "打回") {
					return true;
				}
				return false;
			}
		},

		//扩展按钮
		buttonsEx : [{
			name : 'add',
			text : '新增',
			icon : 'add',
			action : function(row, rows, grid) {
					location="index1.php?model=supplierManage_scheme_scheme&action=toAdd";
			}
		}],
		// 扩展右键菜单
		menusEx : [
//			{
//			text : '提交审批',
//			icon : 'add',
//			showMenuFn : function(row) {
//				if (row.ExaStatus == "待提交" || row.ExaStatus == "打回") {
//					return true;
//				}
//				return false;
//			},
//			action : function(row, rows, grid) {
//				if (row) {
//					showThickboxWin('controller/supplierManage/scheme/ewf_index.php?actTo=ewfSelect&billId='
//							+ row.id
//							+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=750");
//				} else {
//					alert("请选中一条数据");
//				}
//			}
//
//		}, {
//			name : 'aduit',
//			text : '审批情况',
//			icon : 'view',
//			showMenuFn : function(row) {
//				if ((row.ExaStatus == "完成" || row.ExaStatus == "打回")) {
//					return true;
//				}
//				return false;
//			},
//			action : function(row, rows, grid) {
//				if (row) {
//					showThickboxWin("controller/common/readview.php?itemtype=oa_supp_scheme&pid="
//							+ row.id
//							+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=300&width=800");
//				}
//			}
//		},
			{
			text : '编辑',
			icon : 'edit',
//			showMenuFn : function(row) {
//				if (row.ExaStatus == '待提交' || row.ExaStatus == '打回') {
//					return true;
//				}
//				return false;
//			},
			action : function(row) {
				if(row){
				  location="index1.php?model=supplierManage_scheme_scheme&action=init&id="+row.id;
				}
			}
		},
			{
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
						url : "?model=supplierManage_scheme_scheme&action=deletes&id="
								+ row.id,
						success : function(msg) {
							$("#schemeGrid").yxgrid("reload");
						}
					});
				}
			}
		}],
		/**
		 * 快速搜索
		 */
		searchitems : [{
			display : '方案编码',
			name : 'schemeCode'
		}, {
			display : '方案名称',
			name : 'schemeName'
		}]
	});
});
