var show_page = function(page) {
	$("#MyApply").yxgrid("reload");
};
$(function() {
	// var proIdValue = parent.document.getElementById("proId").value;

	$("#MyApply").yxgrid({
		// 如果传入url，则用传入的url，否则使用model及action自动组装

		model : 'contract_sales_sales',
		action : 'myApplyPageJson',
		/**
		 * 是否显示查看按钮/菜单
		 */
		isViewAction : false,
		/**
		 * 是否显示修改按钮/菜单
		 */
		isEditAction : false,
		/**
		 * 是否显示删除按钮/菜单
		 */
		isDelAction : false,
		// 是否显示新增按钮
		isAddAction : false,
		// 是否显示工具栏
		isToolBar : false,
		// 是否显示checkbox
		showcheckbox : false,

		// 扩展右键菜单

		menusEx : [{
			text : '合同信息',
			icon : 'view',
			showMenuFn : function(row) {
				if (row.ExaStatus == '待提交') {
					return false;
				}
				return true;
			},
			action : function(row) {
				showOpenWin('?model=contract_sales_sales&action=readDetailedInfoNoedit&id='
					+ row.id );
			}
		}, {
			text : '合同历史',
			icon : 'view',
			showMenuFn : function(row) {
				if (row.ExaStatus == '待提交') {
					return false;
				}
				return true;
			},
			action : function(row) {
				showThickboxWin('?model=contract_sales_sales&action=versionShow&contNumber='
						+ row.contName
						+ '&placeValuesBefore&TB_iframe=true&modal=false&height=500&width=900');
			}
		}, {
			text : '提交审批',
			icon : 'add',
			showMenuFn : function(row) {
				if (row.ExaStatus == '待提交') {
					return true;
				}
				return false;
			},
			action : function(row) {

				parent.location = 'controller/contract/sales/ewf_index.php?actTo=ewfSelect&formName=销售合同审批&examCode=oa_contract_sales&billId='
						+ row.id

			}
		}, {
			text : '修改',
			icon : 'edit',
			showMenuFn : function(row) {
				if (row.ExaStatus == '待提交') {
					return true;
				}
				return false;
			},
			action : function(row) {
				showOpenWin('?model=contract_sales_sales&action=init&id='
						+ row.id );
			}
		}, {
			text : '重新编辑',
			icon : 'edit',
			showMenuFn : function(row) {
				if (row.ExaStatus == '打回') {
					return true;
				}
				return false;
			},
			action : function(row) {
				showOpenWin('?model=contract_sales_sales&action=editAfterBackAction&id='
						+ row.id );
			}
		}, {
			text : '删除',
			icon : 'delete',
			showMenuFn : function(row) {
				if (row.ExaStatus == '待提交') {
					return true;
				}
				return false;
			},
			action : function(row) {
				if (confirm('确定要删除？')) {
					$.ajax({
						type : "POST",
						url : '?model=contract_sales_sales&action=deletes&id='
								+ row.id,
						success : function(msg) {
							if (msg == 1) {
								alert('删除成功！');
								show_page()
							}
						}
					});
				}
			}
		}, {
			text : '删除',
			icon : 'delete',
			showMenuFn : function(row) {
				if (row.ExaStatus == '打回') {
					return true;
				}
				return false;
			},
			action : function(row) {
				if (confirm('确定要删除？')) {
					$.ajax({
						type : "POST",
						url : '?model=contract_sales_sales&action=deletes&id='
								+ row.id,
						success : function(msg) {
							if (msg == 1) {
								alert('删除成功！');
								show_page()
							}
						}
					});
				}
			}
		}],

		// 表单
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		}, {
			display : '鼎利合同号',
			name : 'contNumber',
			sortable : true,
			width : 150
			// }, {
			// display : '系统合同号',
			// name : 'contNumber',
			// sortable : true,
			//
			// width : 150
			}, {
				display : '合同名称',
				name : 'contName',
				sortable : true,
				width : 150
			}, {
				display : '客户名称',
				name : 'customerName',
				sortable : true,
				width : 150
			}, {
				display : '客户合同号',
				name : 'customerContNum',
				sortable : true
			}, {
				display : '客户类型',
				name : 'customerType',
				datacode : 'KHLX',
				sortable : true
			}, {
				display : '客户所属省份',
				name : 'province',
				sortable : true
			}, {
				display : '负责人名称',
				name : 'principalName',
				sortable : true
			}, {
				display : '合同状态',
				name : 'contStatus',
				sortable : true,
				process : function(v) {
					switch (v) {
						case '' :
							return '未启动';
							break;
						case '0' :
							return '未启动';
							break;
						case '1' :
							return '正执行';
							break;
						case '2' :
							return '变更待审批';
							break;
						case '3' :
							return '变更中';
							break;
						case '4' :
							return '打回关闭';
							break;
						case '5' :
							return '保留删除';
							break;
						case '6' :
							return '变更后关闭';
							break;
						case '9' :
							return '已关闭';
							break;
						default :
							return '未启动';
							break;
					}
				}
			}, {
				display : '审批状态',
				name : 'ExaStatus',
				sortable : true
			}],

		/**
		 * 快速搜索
		 */
		searchitems : [{
			display : '合同名称',
			name : 'contName'
		}],
		sortorder : "DESC",
		title : '我的合同申请'
	});
});