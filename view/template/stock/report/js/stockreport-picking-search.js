
$(function(){
	setSelect('beginYear');
	setSelect('endYear');
	setSelect('beginMonth');
	setSelect('endMonth');

	// ��Ӧ��
	$("#productCode").yxcombogrid_product({
		hiddenId : 'productId',
		gridOptions : {
			showcheckbox : false
		}
	});

	//������
	$("#pickName").yxselect_user({
		hiddenId : 'pickCode',
		formCode : 'stockreportPicking'
	});

	//���ϲ���
	$("#deptName").yxselect_dept({
		hiddenId : 'deptCode'
	});
});

function confirm() {

	var beginYear=$("#beginYear").val();
	var beginMonth=$("#beginMonth").val();
	var endYear=$("#endYear").val();
	var endMonth=$("#endMonth").val();
	var productId=$("#productId").val();
	var productCode=$("#productCode").val();
	var deptCode=$("#deptCode").val();
	var deptName=$("#deptName").val();
	var pickCode=$("#pickCode").val();
	var pickName=$("#pickName").val();

	this.opener.location= "?model=stock_report_stockreport&action=toStockoutPicking&beginMonth="+beginMonth
		+ "&beginYear="+beginYear
		+ "&endMonth="+endMonth+"&endYear="+endYear
		+ "&productId="+productId
		+ "&productCode="+productCode
		+ "&deptCode="+deptCode
		+ "&deptName="+deptName
		+ "&pickCode="+pickCode
		+ "&pickName="+pickName
	;
	this.close();
}

function refresh(){
	$(".clear").val("");
}