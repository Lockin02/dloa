//��ת���б�ҳ��
function toList(){
	if(checkform() == true){
		location='?model=stock_instock_stockin&action=detailList&beginYear=' + $("#beginYear").val()
			+ '&beginMonth=' + $("#beginMonth").val()
			+ '&endYear=' + $("#endYear").val()
			+ '&endMonth=' + $("#endMonth").val()
			+ '&docType=' + $("#docType").val()
			+ '&productId=' + $("#productId").val()
			+ '&docStatus=' + $("#docStatus").val()
			+ '&isRed=' + $("#isRed").val()
			;
	}
}

function checkform(){

	var beginYearVal = $("#beginYear").val() * 1; //��ʼ��

	if(beginYearVal == ""){
		alert('�����뿪ʼ���');
		return false;
	}
	if(isNaN(beginYearVal)){
		alert('��ʼ��ݲ�������');
		return false;
	}
	if( beginYearVal > 2100 || beginYearVal < 1980 ){
		alert('�����������1980 �� 2100 ');
		return false;
	}

	var beginMonthVal = $("#beginMonth").val() * 1 ;

	if( beginMonthVal == "" ){
		alert('�����뿪ʼ�·�');
		return false;
	}
	if(isNaN(beginMonthVal)){
		alert('��ʼ�·ݲ�������');
		return false;
	}
	if( beginMonthVal > 12 || beginMonthVal < 1 ){
		alert('�������·���1 �� 12 ');
		return false;
	}

	var endYearVal = $("#endYear").val() * 1; //������

	if(endYearVal == ""){
		alert('������������');
		return false;
	}
	if(isNaN(endYearVal)){
		alert('������ݲ�������');
		return false;
	}
	if( endYearVal > 2100 || endYearVal < 1980 ){
		alert('�����������1980 �� 2100 ');
		return false;
	}
	var endMonthVal = $("#endMonth").val() * 1 ;

	if( endMonthVal == "" ){
		alert('����������·�');
		return false;
	}
	if(isNaN(endMonthVal)){
		alert('�����·ݲ�������');
		return false;
	}
	if( endMonthVal > 12 || endMonthVal < 1 ){
		alert('�������·���1 �� 12 ');
		return false;
	}


	return true;
}

$(function() {
	// ��Ӧ��
	$("#productCode").yxcombogrid_product({
		hiddenId : 'productId',
		height : 400,
		width : 700,
		gridOptions : {
			showcheckbox : false
		}
	});
});