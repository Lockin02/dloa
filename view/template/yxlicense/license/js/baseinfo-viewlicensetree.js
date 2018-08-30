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


				//判断当前节点id是否在数组里面
				index = idArr.indexOf(treeNode.id);
				if(index != -1 ){
					//如果有,则删除数组中的该节点id
					idArr.splice(index, 1);
				}else{
					//如果没有,把id放入数组中
					if(treeNode.checked){
						idArr.push(treeNode.id);
					}
				}
				//如果当前节点有子节点,进行递归
				if(treeNode.nodes != undefined ){
					for(i = 0;i< treeNode.nodes.length ; i++){
						changeFn(treeNode.nodes[i]);
					}
				}
				//将数组转换成字符转
				var treeNodeStr = idArr.toString();
				$("#nodeIds").val(treeNodeStr);
			}
		}
    });
});

//递归调用树节点函数
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

