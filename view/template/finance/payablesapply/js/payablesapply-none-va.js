function checkform(){
	if($("#supplierName").val() == ""){
		alert('��ѡ��Ӧ��');
		return false;
	}
	if($("#supplierId").val() == ""){
		alert('�����������жԹ�Ӧ�̽���ѡ��,��û���ҵ���Ӧ��Ӧ��,����ϵ��ظ����˽������');
		return false;
	}

	innerPayType = $("#payType").find("option:selected").attr("e1");
	if(innerPayType == 1){
		if($("#bank").val() == ""){
			alert('����д��Ӧ�̿�������');
			return false;
		}

		if($("#account").val() == ""){
			alert('����д�տ������ʺ�');
			return false;
		}
	}

	if($("#deptName").val() == ""){
		alert('��ѡ�����벿��');
		return false;
	}

	if($("#salesman").val() == ""){
		alert('��ѡ��������');
		return false;
	}

	if($("#feeDeptName").val() == ""){
		alert('��ѡ����ù�������');
		return false;
	}
	if(strTrim($("#formDate").val()) == ""){
		alert('����д��������');
		return false;
	}

	if(strTrim($("#payDate").val()) == ""){
		alert('����д������������');
		return false;
	}

	if($("#payMoney").val() == "" || $("#payMoney").val()*1 == 0){
		alert('�������Ϊ0');
		return false;
	}

	var remark = strTrim($("#remark").val());
	if(remark == ""){
		alert('����д������;');
		return false;
	}else{
		if(remark.length > 10){
			alert('�뽫������;������Ϣ������10���ֻ�10��������,��ǰ����Ϊ'+ remark.length +" ����");
			return false;
		}
	}
	if($("#deptIsNeedProvince").val() ==1 && $("#provinceId").val() =="" ){
		alert('��ѡ��ʡ��!');
		return false;
	}
	//��ֹ�ظ��ύ
	$("input[type='submit']").attr('disabled',true);

	return true;
}