//�߼���ѯ
function searchFun(){
	window.open("?model=finance_invother_invother&action=toListInfoSearch"
		+ "&formDateBegin=" + $("#formDateBegin").val()
		+ "&formDateEnd=" + $("#formDateEnd").val()
		+ "&supplierName=" + $("#supplierName").val()
		+ "&invoiceNo=" + $("#invoiceNo").val()
		+ "&salesmanId=" + $("#salesmanId").val()
		+ "&salesman=" + $("#salesman").val()
		+ "&exaManId=" + $("#exaManId").val()
		+ "&exaMan=" + $("#exaMan").val()
		+ "&ExaStatus=" + $("#ExaStatus").val()
		+ "&invType=" + $("#invType").val()
		+ "&productName=" + $("#productName").val()
	,'newwindow1','height=500,width=800');
}

//��ղ�ѯ
function clearFun(){
	this.location='?model=finance_invother_invother&action=listinfo';
}

//�ر��б�
function closeFun(){
	this.close();
}