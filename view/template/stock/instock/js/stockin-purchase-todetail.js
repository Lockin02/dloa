//��ת���б�ҳ��
function toList(){
	if(checkform() == true){
		location='?model=stock_instock_stockin&action=detailList&beginYear=' + $("#beginYear").val()
			+ '&beginMonth=' + $("#beginMonth").val()
			+ '&endYear=' + $("#endYear").val()
			+ '&endMonth=' + $("#endMonth").val()
			+ '&docType=' + $("#docType").val()
			+ '&supplierId=' + $("#supplierId").val()
			+ '&productId=' + $("#productId").val()
			+ '&catchStatus=' + $("#catchStatus").val()
			+ '&isRed=' + $("#isRed").val()
			+ '&ifshow=' + $("input:radio[name='ifshow']:checked").val()
			+ '&docCode=' + $("#docCode").val()
			+ '&inStockId=' + $("#inStockId").val()
			+ '&pattern=' + $("#pattern").val()
			+ '&actNum=' + $("#actNum").val()
			+ '&price=' + $("#price").val()
			+ '&subPrice=' + $("#subPrice").val()
			+ '&createId=' + $("#createId").val()
			+ '&purchaserCode=' + $("#purchaserCode").val()
			+ '&auditerCode=' + $("#auditerCode").val()
			+ '&auditDate=' + $("#auditDate").val()
			;
	}
}

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

	if(beginMonthVal != "" ){
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

	if( endMonthVal != "" ){
		if(isNaN(endMonthVal)){
			alert('�����·ݲ�������');
			return false;
		}
		if( endMonthVal > 12 || endMonthVal < 1 ){
			alert('�������·���1 �� 12 ');
			return false;
		}
	}

	if($("#supplierName").val() != ""){
		if($("#supplierId").val() == ""){
			alert("��ͨ���������Թ�Ӧ�̽���ѡ��");
			return false;
		}
	}

	if($("#objCode").val() != ""){
		if($("#objId").val() == ""){
			alert("��ͨ���������Ժ�ͬ����ѡ��");
			return false;
		}
	}

	return true;
}

$(function() {
	// ��Ӧ��
	$("#supplierName").yxcombogrid_supplier({
		hiddenId : 'supplierId',
		height : 400,
		width : 600,
		isShowButton : false,
		gridOptions : {
			showcheckbox : false
		}
	});
	// �ƶ�����
	$("#productCode").yxcombogrid_product({
		hiddenId : 'productId',
		height : 400,
		width : 700,
		gridOptions : {
			showcheckbox : false
		}
	});
	// �Ƶ�
    $("#createName").yxselect_user({
        hiddenId: 'createId'
    });
	// �ɹ�Ա
    $("#purchaserName").yxselect_user({
        hiddenId: 'purchaserCode'
    });
	// �����
    $("#auditerName").yxselect_user({
        hiddenId: 'auditerCode'
    });
});