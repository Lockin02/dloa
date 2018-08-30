//��ת���б�ҳ��
function toList(){
	if(checkform() == true){
		location='?model=stock_outstock_stockout&action=detailList&beginYear=' + $("#beginYear").val()
			+ '&beginMonth=' + $("#beginMonth").val()
			+ '&endYear=' + $("#endYear").val()
			+ '&endMonth=' + $("#endMonth").val()
			+ '&docType=' + $("#docType").val()
			+ '&pickCode=' + $("#pickCode").val()
			+ '&pickName=' + $("#pickName").val()
			+ '&productId=' + $("#productId").val()
			+ '&productCode=' + $("#productCode").val()
			+ '&deptCode=' + $("#deptCode").val()
			+ '&deptName=' + $("#deptName").val()
			+ '&customerId=' + $("#customerId").val()
			+ '&customerName=' + $("#customerName").val()
			+ '&isRed=' + $("#isRed").val()
			+ '&ifshow=' + $("input:radio[name='ifshow']:checked").val()
			+ '&docCode=' + $("#docCode").val()
			+ '&toUse=' + $("#toUse").val()
			+ '&pattern=' + $("#pattern").val()
			+ '&serialnoName=' + $("#serialnoName").val()
			+ '&actOutNum=' + $("#actOutNum").val()
			+ '&cost=' + $("#cost").val()
			+ '&subCost=' + $("#subCost").val()
			;
	}
}

$(function() {
	$("#pickName").yxselect_user({
		hiddenId : 'pickCode'
	});
	$("#deptName").yxselect_dept({
		hiddenId : 'deptCode'
	});


	//����
	$("#productCode").yxcombogrid_product({
		hiddenId : 'productId',
		height : 400,
		width : 700,
		gridOptions : {
			showcheckbox : false
		}
	});

	//�ͻ�����
	$("#customerName").yxcombogrid_customer({
		hiddenId : 'customerId',
		height : 400,
		width : 700,
		isShowButton : false,
		gridOptions : {
			showcheckbox : false
		}
	});
});

function checkform(){

	var beginYearVal = $("#beginYear").val() * 1; //��ʼ��

	if(beginYearVal != ""){
		if(isNaN(beginYearVal)){
			alert('��ʼ��ݲ�������');
			return false;
		}
		if( beginYearVal > 2100 || beginYearVal < 1980 ){
			alert('�����������1980 �� 2100 ');
			return false;
		}
	}

	var beginMonthVal = $("#beginMonth").val() * 1 ; //��ʼ��

	if( beginMonthVal != "" ){
		if(isNaN(beginMonthVal)){
			alert('��ʼ�·ݲ�������');
			return false;
		}
		if( beginMonthVal > 12 || beginMonthVal < 1 ){
			alert('�������·���1 �� 12 ');
			return false;
		}
	}

	var endYearVal = $("#endYear").val() * 1; //������

	if(endYearVal != ""){
		if(isNaN(endYearVal)){
			alert('������ݲ�������');
			return false;
		}
		if( endYearVal > 2100 || endYearVal < 1980 ){
			alert('�����������1980 �� 2100 ');
			return false;
		}
	}

	var endMonthVal = $("#endMonth").val() * 1 ; //������

	if(endMonthVal != "" ){
		if(isNaN(endMonthVal)){
			alert('�����·ݲ�������');
			return false;
		}
		if( endMonthVal > 12 || endMonthVal < 1 ){
			alert('�������·���1 �� 12 ');
			return false;
		}
	}

	if($("#customerName").val() != ""){
		if($("#customerId").val() == ""){
			alert("��ͨ���������Կͻ�����ѡ��");
			return false;
		}
	}

	if($("#productCode").val() != ""){
		if($("#productId").val() == ""){
			alert("��ͨ�������������Ͻ���ѡ��");
			return false;
		}
	}
	return true;
}