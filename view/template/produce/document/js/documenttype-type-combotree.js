$(function() {
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
			url : "?model=produce_document_documenttype&action=getTreeDataByParentId"
		}
	});
});