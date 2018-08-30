//选择产品类型

$(function(){

     $("#parentName").yxcombotree({

     	hiddenId:'parentId',
 		treeOptions : {
		event : {
			"node_click" : function(event, treeId, treeNode) {
				//alert(treeId)
			},
			"node_change" : function(event, treeId, treeNode) {
				//alert(treeId)
					}
		},
		url : "?model=system_datadict_datadict&action=getChildren"
				}
			});
});


