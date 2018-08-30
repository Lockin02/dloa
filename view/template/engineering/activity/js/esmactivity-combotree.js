
$(document).ready(function(){
	var projectId=$("#projectId").val();
     $("#parentName").yxcombotree({
     	hiddenId:'parentId',
 		treeOptions : {
			event : {
				"node_click" : function(event, treeId, treeNode) {
					$("#parentCode").val(treeNode.code);
				}
			},
			url : "?model=engineering_activity_esmactivity&action=getChildren&projectId="+projectId
		}
	});
});
