var idArr = new Array();
var idStr = "";
var index = 1;
var i = 0;


$(function() {
    $("#baseinfoGrid").yxtree({
		checkable : true,
		paramOther : {"id" : $('#baseinfoId').val()},
		expandSpeed : "",
		url : '?model=yxlicense_license_baseinfo&action=getContent',
        nameCol : "describe",
        event : {
			"node_change" : function(event, treeId, treeNode) {


				//�жϵ�ǰ�ڵ�id�Ƿ�����������
				index = idArr.indexOf(treeNode.id);
				if(index != -1 ){
					//�����,��ɾ�������еĸýڵ�id
					idArr.splice(index, 1);
				}else{
					//���û��,��id����������
					if(treeNode.checked){
						idArr.push(treeNode.id);
					}
				}
				//�����ǰ�ڵ����ӽڵ�,���еݹ�
				if(treeNode.nodes != undefined ){
					for(i = 0;i< treeNode.nodes.length ; i++){
						changeFn(treeNode.nodes[i]);
					}
				}
				//������ת�����ַ�ת
				var treeNodeStr = idArr.toString();
				$("#nodeIds").val(treeNodeStr);
			}
		}
    });
});

//�ݹ�������ڵ㺯��
function changeFn(object){

	index = idArr.indexOf(object.id);
	if(index != -1){
		idArr.splice(index, 1);
	}else{
		if(object.checked){
			idArr.push(object.id);
		}
	}
	if(object.nodes != undefined ){
		for(var i = 0;i< object.nodes.length ; i++){
			changeFn(object.nodes[i]);
		}
	}
}

