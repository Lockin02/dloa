function checkform(){
	if($("#supplierName").val() == ""){
		alert('��ѡ��Ӧ��');
		return false;
	}
	if($("#supplierId").val() == ""){
		alert('�����������жԹ�Ӧ�̽���ѡ��,��û���ҵ���Ӧ��Ӧ��,����ϵ��ظ����˽������');
		return false;
	}

	if($("#remark").val() == ""){
		alert('����д������;');
		return false;
	}

	var coutNumb = $("#coutNumb").val();
	for(var i = 1;i<=coutNumb;i++){
		if($("#money" + i).val() == "" || $("#money" + i).val()*1 == 0){
			alert("�������Ϊ0���߿�");
			return false;
		}
		if($("#objType" + i).length > 0){
			if($("#objType" + i).val() != "" && $("#objId" + i).val() == ""){
				alert("��ѡ���Ӧ�ĵ���");
				return false;
			}
		}
	}

	$("input[type='submit']").attr('disabled',true);

	return true;
}

function checkMax(thisI){
	if($("#objId" + thisI).val() == "" || $("#objId" + thisI).val() == 0 ){
		return false;
	}
	if($("#money"+ thisI).val()*1 > $("#oldMoney"+ thisI).val()*1){
		alert('������ܳ����ɹ������������');
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