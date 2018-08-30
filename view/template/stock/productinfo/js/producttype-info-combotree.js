

$(function() {
	// 新增分类信息 选择物料类型
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