//�߼���ѯ
function searchFun(){
	window.open("?model=finance_adjust_adjust&action=toListInfoSearch"
		+ "&formDateBegin=" + $("#formDateBegin").val()
		+ "&formDateEnd=" + $("#formDateEnd").val()
		+ "&supplierName=" + $("#supplierName").val()
		+ "&supplierId=" + $("#supplierId").val()
		+ "&productNo=" + $("#productNo").val()
		+ "&productId=" + $("#productId").val()
	,'newwindow1','height=500,width=800');
}

//��ղ�ѯ
function clearFun(){
	this.location='?model=finance_adjust_adjust&action=listinfo';
}

//�ر��б�
function closeFun(){
	this.close();
}