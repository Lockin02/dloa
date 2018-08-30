function checkform(){
	var checkedObj = $('#invoiceType');

	if($('#docId').val() == ""){
		alert('请选择需要邮寄的发票');
		return false;
	}

	if($('#customerId').val() == ""){
		alert('请选择客户');
		return false;
	}

	if($('#receiver').val() == ""){
		alert('收件人需要填写');
		return false;
	}

	if($('#mailNo').val() == ""){
		alert('邮寄单号需要填写');
		return false;
	}

	if($('#logisticsId').val() == ""){
		alert('请选择物流公司');
		return false;
	}

	if($('#address').val() == ""){
		alert('邮寄地址需要填写');
		return false;
	}

	return true;
}