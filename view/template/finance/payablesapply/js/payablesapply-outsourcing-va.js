function checkform(){
	var payFor = $("#payFor").val();

	var innerPayType = $("#payType").find("option:selected").attr("e1");

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
	
	if($("#supplierName").val() == ""){
		alert('����д��Ӧ������');
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

	var remark = strTrim($("#remark").val());
	if(remark == ""){
        if(payFor == 'FKLX-03'){
            alert('����д�˿�˵��');
        }else{
            alert('����д������;');
        }
        return false;
	}else{
		if(remark.length > 10){
            if(payFor == 'FKLX-03'){
                alert('�뽫�˿�˵��������Ϣ������10���ֻ�10��������,��ǰ����Ϊ'+ remark.length +" ����");
                return false;
            }else{
                alert('�뽫������;������Ϣ������10���ֻ�10��������,��ǰ����Ϊ'+ remark.length +" ����");
                return false;
            }
		}
	}

	//����
	if($("#currency").val() == ""){
		alert('����д�������');
		return false;
	}

	//����ص�
	if($("#place").val() == ""){
		alert('����д����ص�(ʡ��)');
		return false;
	}

	if(payFor != 'FKLX-03'){
		//����˵��
		if($("#payDesc").val() == ""){
			alert('����д����˵��');
			return false;
		}
	}

	var coutNumb = $("#coutNumb").val();
	for(var i = 1;i<=coutNumb;i++){
		if($("#money" + i).val() == "" || $("#money" + i).val()*1 == 0){
			alert("�������Ϊ0���߿�");
			return false;
		}
	}

    // �⳵ʱ����֤
    if ($("#sourceType").val() == 'YFRK-06' && $("#period").val() == ""){
        alert('����д�����·�');
        return false;
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
        var payFor = $("#payFor").val();
        var sourceType=$("input[name='payablesapply[sourceType]']").val();
        if('FKLX-03'==payFor&&'YFRK-06'==sourceType){
            alert('������ܳ����Ѹ���������');
        }else{
            alert('������ܳ���Դ�����');
        }
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