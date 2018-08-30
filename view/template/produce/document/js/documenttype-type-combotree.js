$(function() {
	// 新增分类信息 选择物料类型
	$("#parentName").yxcombotree({
		hiddenId : 'parentId',
		treeOptions : {
			event : {
				"node_click" : function(event, treeId, treeNode) {

				},
				"node_change" : function(event, treeId, treeNode) {

				}
			},
			url : "?model=produce_document_documenttype&action=getTreeDataByParentId"
		}
	});
});