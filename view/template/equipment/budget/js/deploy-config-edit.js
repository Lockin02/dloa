var show_page = function(page) {
	$("#deployEditGrid").yxgrid("reload");
};
$(function() {
	$("#deployEditGrid").yxgrid({
		model : 'equipment_budget_deploy',
		param : {
			equId : $("#equId").val()
		},
		showcheckbox : false,
		isDelAction : false,
		isViewAction : false,
		title : '配置管理',
		// 列信息
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		}, {
			name : 'name',
			display : '设备名称',
			sortable : true,
			width : 200
		}, {
			name : 'info',
			display : '详细描述',
			sortable : true,
			width : 300
		}, {
			name : 'price',
			display : '单价',
			sortable : true,
			width : 200,
			process : function(v) {
				return moneyFormat2(v);
			}
		}
//		, {
//			name : 'remark',
//			display : '备注',
//			sortable : true
//		}
		],
		menusEx : [ {
			text : '删除',
			icon : 'delete',
			action : function(row, rows, grid) {
				if (window.confirm("确认要删除?")) {
					$.ajax({
						type : "POST",
						url : "?model=equipment_budget_deploy&action=ajaxdeletes",
						data : {
							id : row.id
						},
						success : function(msg) {
							if (msg == 1) {
								show_page();
								alert('删除成功！');
							} else {
								alert('删除失败!');
							}
						}
					});
				}
			}
		}],
		toAddConfig : {
			toAddFn : function(p) {
				showThickboxWin("?model=equipment_budget_deploy&action=toAdd&equId="
						+ $("#equId").val()
						+ "&placeValuesBefore&TB_iframe=true&modal=false&height=400&width=750");
			}
//			action : 'toAdd',
//			formHeight : 400,
//			formWidth : 750
		},
		toEditConfig : {
			action : 'toEdit',
			formHeight : 400,
			formWidth : 750
		},
		toViewConfig : {
			action : 'toView',
			formHeight : 400,
			formWidth : 750
		},
		searchitems : [{
			display : "设备分类名称",
			name : 'budgetType'
		}, {
			display : "上级类型",
			name : 'parentName'
		}]
	});
});