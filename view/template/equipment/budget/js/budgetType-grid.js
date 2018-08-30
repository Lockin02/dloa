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
		title : '�豸������Ϣ',
		// ����Ϣ
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		}, {
			name : 'budgetType',
			display : '�豸��������',
			sortable : true,
			width : 200
		}, {
			name : 'parentName',
			display : '�ϼ�����',
			sortable : true,
			width : 200
		}, {
			name : 'orderNum',
			display : '����',
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
			display : "�豸��������",
			name : 'budgetType'
		}, {
			display : "�ϼ�����",
			name : 'parentName'
		}]
	});
});