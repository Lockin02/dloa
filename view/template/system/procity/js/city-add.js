$(document).ready(function(){
	$("#provinceName").yxcombotree({
		hiddenId : 'provinceId',//���ؿؼ�id
		nameCol:'name',
		treeOptions : {
			checkable : false,//��ѡ
			event : {
				"node_click" : function(event, treeId, treeNode) {
					$("#provinceCode").val(treeNode.code);
				}
			},
			url : "index1.php?model=system_procity_province&action=getChildren"//��ȡ����url
		}
	});


});
