var show_page = function(page) {
	$("#budgetGrid").yxgrid("reload");
};
$(function() {
	buttonsArr = [{
		name : 'add',
		text : "预算表说明维护",
		icon : 'add',
		action : function(row) {
				showModalWin("?model=equipment_budget_budget&action=explain"
						+ "&placeValuesBefore&TB_iframe=true&modal=false&height=500&width=900");
			}
	}],
	$("#budgetGrid").yxgrid({
		model : 'equipment_budget_budget',
		title : '设备预算表',
		event : {
			'row_dblclick' : function(e, row, data) {
				showModalWin("?model=equipment_budget_budget&action=toView&id="
						+ data.id
						+ "&placeValuesBefore&TB_iframe=true&modal=false&height=500&width=900");
			}
		},
		isViewAction : false,
		isEditAction : false,
		isDelAction : false,
		showcheckbox : false,
		isAddAction : false,
		customCode : 'budgetGrid',
		//列信息
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		}, {
			name : 'budgetCode',
			display : '编号',
			sortable : true
		}, {
			name : 'budgetTypeName',
			display : '类型',
			sortable : true
		}, {
			name : 'equName',
			display : '设备名称',
			sortable : true,
			width : 200
		}, {
			name : 'equRemark',
			display : '设备备注',
			sortable : true,
			hide : true
		}, {
			name : 'allMoney',
			display : '总金额',
			sortable : true,
			process : function(v, row) {
				if (v == '') {
					return "0.00";
				} else {
					return moneyFormat2(v);
				}
			}
		}, {
			name : 'status',
			display : '状态',
			sortable : true,
			hide : true
		}, {
			name : 'createName',
			display : '申请人',
			sortable : true
		}, {
			name : 'createTime',
			display : '申请时间',
			sortable : true
		}, {
			name : 'useEndDate',
			display : '有效截止日期',
			sortable : true
		}],
        menusEx : [{
			text : '查看',
			icon : 'view',
			action : function(row, rows, grid) {
				showModalWin("?model=equipment_budget_budget&action=toView&id="
						+ row.id
						+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=800");
			}
		}
//		, {
//			text : '删除',
//			icon : 'delete',
//			action : function(row, rows, grid) {
//				if (window.confirm("确认要删除?")) {
//					$.ajax({
//						type : "POST",
//						url : "?model=equipment_budget_budgetbaseinfo&action=ajaxdeletes",
//						data : {
//							id : row.id
//						},
//						success : function(msg) {
//							if (msg == 1) {
//								show_page();
//								alert('删除成功！');
//							} else {
//								alert('删除失败!');
//							}
//						}
//					});
//				}
//			}
//		}
		],
		buttonsEx : buttonsArr,
		// 快速搜索
		searchitems : [{
			display : '编号',
			name : 'budgetCode'
		}, {
			display : '类型',
			name : 'budgetTypeName'
		}, {
			display : '设备名称',
			name : 'equName'
		}]
	});
});