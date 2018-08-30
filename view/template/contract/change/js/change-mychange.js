var show_page = function(page) {
	$("#MyChange").yxgrid("reload");
};

$(function() {
	$("#MyChange").yxgrid({
		// 如果传入url，则用传入的url，否则使用model及action自动组装
		model : 'contract_change_change',
		action : 'pageJsonMyChange',
		title : '我的变更申请',
		isViewAction : false,
		isEditAction : false,
		isDelAction : false,
		isAddAction : false,
		showcheckbox : false,
		isToolBar : false,
		sortorder : "DESC",

		// 扩展右键菜单
		menusEx : [{
			text : '查看',
			icon : 'view',
			action : function(row) {
				showOpenWin('?model=contract_change_change&action=showAction&id='
						+ row.id
						+ '&placeValuesBefore&TB_iframe=true&modal=false&height=800&width=900');
			}
		}, {
			text : '修改',
			icon : 'edit',
			showMenuFn : function(row) {
				if (row.ExaStatus == '变更待审批') {
					return true;
				}
				return false;
			},
			action : function(row) {
				location = '?model=contract_change_change&action=editChangeForm&id='
						+ row.id;
			}
		}, {
			text : '审批情况',
			icon : 'view',
			showMenuFn : function(row) {
				if (row.ExaStatus == '变更待审批') {
					return false;
				}
				return true;
			},
			action : function(row) {
				showThickboxWin('controller/common/readview.php?itemtype=oa_contract_change&pid='
		             +row.id
		             + '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=600');
			}
		}, {
			text : '提交审批',
			icon : 'add',
			showMenuFn : function(row) {
				if (row.ExaStatus == '变更待审批') {
					return true;
				}
				return false;
			},
			action : function(row) {
				parent.location = 'controller/contract/sales/ewf_index.php?actTo=ewfSelect&formName=合同变更&examCode=oa_contract_change&billId='
						+ row.id
			}
		}, {
			text : '删除',
			icon : 'delete',
			showMenuFn : function(row) {
				if (row.ExaStatus == '变更待审批') {
					return true;
				}
				return false;
			},
			action : function(row) {
				if(confirm('确认删除？')){
					$.ajax({
						type : "POST",
						url : "?model=contract_change_change&action=delT",
						data : {
							id : row.id
						},
						success : function(msg) {
							if (msg == 1) {
								alert('删除成功！');
								$("#MyChange").yxgrid("reload");
							}else{
								alert('删除失败!');
							}
						}
					});
				}
			}
		}, {
			text : '启动变更',
			icon : 'add',
			showMenuFn : function(row) {
				if (row.ExaStatus == '完成') {
					return true;
				}
				return false;
			},
			action : function(row) {
				if(confirm('确定启动?')){
					$.ajax({
						type : "POST",
						url : "?model=contract_change_change&action=beginChange",
						data : {
							id : row.id
						},
						success : function(msg) {
							if (msg == 1) {
								alert('启动成功！');
								$("#MyChange").yxgrid("reload");
							}else{
								alert('启动失败!');
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
				if(confirm('确认删除？')){
					$.ajax({
						type : "POST",
						url : "?model=contract_change_change&action=notdel",
						data : {
							id : row.id
						},
						success : function(msg) {
							if (msg == 1) {
								alert('删除成功！');
								$("#MyChange").yxgrid("reload");
							}else{
								alert('删除失败!');
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
			display : '变更申请单号',
			name : 'formNumber',
			sortable : true,
			width : 150
		}, {
			display : '鼎利合同号',
			name : 'contNumber',
			sortable : true,
			width : 150
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
			display : '客户类型',
			name : 'customerType',
			datacode : 'KHLX',
			sortable : true
		}, {
			display : '客户所属省份',
			name : 'province',
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
			display : '申请日期',
			name : 'applyTime',
			sortable : true
		}, {
			display : '申请状态',
			name : 'ExaStatus',
			sortable : true
		}],
		/**
		 * 快速搜索
		 */
		searchitems : [{
			display : '申请单号',
			name : 'formNumber'
		}]
	});
});