//保存评估信息
function saveSupplier(){
	document.getElementById('form1').action = "index1.php?model=supplierManage_temporary_stasse&action=stsAdd&act=save";

}

//返回产品注册页面，并可对注册产品进行编辑
function backToEditProd(){
	var parentId = document.getElementById('parentId').value;
	location="?model=supplierManage_temporary_stproduct&action=toEditProd&parentId="+parentId;
//	alert(parentId);
}

