//����֤����
function checkform(){
	var payFor = $("#payFor").val();

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

	if($("#payCondition").val() == ""){
		alert('����д��������');
		return false;
	}

	if($("#payMoney").val()*1 == 0){
		alert('�������Ϊ0');
		return false;
	}

	if($("#deptName").val() == ""){
		alert('��ѡ�����벿��');
		return false;
	}

	if($("#salesman").val() == ""){
		alert('��ѡ��������');
		return false;
	}

	//������������
	if($("#payDate").val() == ""){
		alert('����д������������');
		return false;
	}

	//����ص�
	if($("#place").val() == ""){
		alert('����д����ص�(ʡ��)');
		return false;
	}

	//����
	if($("#currency").val() == ""){
		if(payFor == 'FKLX-03'){
			alert('����д�˿����');
		}else{
			alert('����д�������');
		}
		return false;
	}

	// ��ǰ����ԭ��
	if($("#needPayEarlyReason").val() == '1' && $("#payEarlyReason").val() == ''){
		alert('����д��ǰ����ԭ��');
		$("#payEarlyReason").focus();
		return false;
	}

	var remark = strTrim($("#remark").val());
	if(remark == ""){
		if(payFor == 'FKLX-03'){
			alert('����д�˿�˵��');
		}else{
			alert('����д������;');
		}
		return false;
	}else{
		if(remark.length > 10 && payFor != 'FKLX-03'){
			alert('�뽫������;������Ϣ������10���ֻ�10��������,��ǰ����Ϊ'+ remark.length +" ����");
			return false;
		}
	}

	if(payFor != 'FKLX-03'){
		//����˵��
		if($("#payDesc").val() == ""){
			alert('����д����˵��');
			return false;
		}
	}


	//��ǰ����ֵ��ʼ��
	var isAdvPayObj = $("#isAdvPay");
	if(isAdvPayObj.length != 0){
		if(isAdvPayObj.val() == 1){

			//��ǰ����ԭ��
			if($("#advPayReason").val() == ""){
				alert('����д��ǰԭ��');
				return false;
			}
		}
	}

	if($("#canApplyAll").length == 1){
		canApplyAll = $("#canApplyAll").val()*1;
		payMoney = $("#payMoney").val()*1;
		if(canApplyAll < payMoney){
			alert('�����������Ϊ:' + payMoney + ' ���ܳ����ý������������:' + canApplyAll );
			return false;
		}
	}
	//��ֹ�ظ��ύ
	$("input[type='submit']").attr('disabled',true);

	return true;
}

function checkMax(thisI){
	if($("#objId" + thisI).val() == "" || $("#objId" + thisI).val() == 0 ){
		return false;
	}
	if($("#money"+ thisI).val()*1 > $("#oldMoney"+ thisI).val()*1){
		alert('�������ѳ�����������');
		if($("#orgMoney" + thisI ).length == 0){
			$("#money"+ thisI + "_v").val(moneyFormat2($("#oldMoney"+ thisI).val())) ;
			$("#money"+ thisI).val($("#oldMoney"+ thisI).val()) ;
		}else{
			$("#money"+ thisI + "_v").val(moneyFormat2($("#orgMoney"+ thisI).val())) ;
			$("#money"+ thisI).val($("#orgMoney"+ thisI).val()) ;
		}
		return false;
	}
}