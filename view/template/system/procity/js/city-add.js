$(document).ready(function(){
	$("#provinceName").yxcombotree({
		hiddenId : 'provinceId',//隐藏控件id
		nameCol:'name',
		treeOptions : {
			checkable : false,//多选
			event : {
				"node_click" : function(event, treeId, treeNode) {
					$("#provinceCode").val(treeNode.code);
				}
			},
			url : "index1.php?model=system_procity_province&action=getChildren"//获取数据url
		}
	});


});
