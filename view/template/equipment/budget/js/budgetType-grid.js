var show_page = function(page) {
	$("#budgetTypeGrid").yxgrid("reload");
	$("#budgetTypeTree").yxtree("reload");
};
$(function() {
	$("#budgetTypeTree").yxtree({
		url : '?model=equipment_budget_budgetType&action=getTreeData',
		event : {
			"node_click" : function(event, treeId, treeNode) {
				var budgettypeGrid = $("#budgetTypeGrid").data('yxgrid');
				budgettypeGrid.options.param['parentId'] = treeNode.id;
				$("#parentName").val(treeNode.name);
				$("#parentId").val(treeNode.id);
				budgettypeGrid.reload();
			}
		}
	});
	$("#budgetTypeGrid").yxgrid({
		model : 'equipment_budget_budgetType',
		title : '设备分类信息',
		// 列信息
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		}, {
			name : 'budgetType',
			display : '设备分类名称',
			sortable : true,
			width : 200
		}, {
			name : 'parentName',
			display : '上级类型',
			sortable : true,
			width : 200
		}, {
			name : 'orderNum',
			display : '排序',
			sortable : true
		}],

		toAddConfig : {
			action : 'toAdd',
			formHeight : 300,
			formWidth : 500
		},
		toEditConfig : {
			action : 'toEdit',
			formHeight : 300,
			formWidth : 500
		},
		toViewConfig : {
			action : 'toView',
			formHeight : 300,
			formWidth : 500
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