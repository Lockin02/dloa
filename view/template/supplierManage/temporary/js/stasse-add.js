//����������Ϣ
function saveSupplier(){
	document.getElementById('form1').action = "index1.php?model=supplierManage_temporary_stasse&action=stsAdd&act=save";

}

//���ز�Ʒע��ҳ�棬���ɶ�ע���Ʒ���б༭
function backToEditProd(){
	var parentId = document.getElementById('parentId').value;
	location="?model=supplierManage_temporary_stproduct&action=toEditProd&parentId="+parentId;
//	alert(parentId);
}

