

$(function() {
	// ����������Ϣ ѡ����������
	$("#proType").yxcombotree({
		hiddenId : 'proTypeId',
		treeOptions : {
			event : {
				"node_click" : function(event, treeId, treeNode) {
					// alert(treeId)
					$("#arrivalPeriod").val(treeNode.submitDay);
				},
				"node_change" : function(event, treeId, treeNode) {
					// alert(treeId)
				}
			},
			url : "?model=stock_productinfo_producttype&action=getTreeDataByParentId"
		}
	});
});