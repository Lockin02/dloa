$(document).ready(function() {

	// ����������Ϣ ѡ����������
	$("#parentName").yxcombotree({
		hiddenId : 'parentId',
		treeOptions : {
			event : {
				"node_click" : function(event, treeId, treeNode) {
				},
				"node_change" : function(event, treeId, treeNode) {

				}
			},
			url : "?model=equipment_budget_budgetType&action=getTreeData"
		}
	});
	validate({
		"budgetType" : {
			required : true

		}
	})
})