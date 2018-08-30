function checkform(){
	if($('#customerName').val() == ""){
		alert('��Ʊ��λ��Ҫ��д');
		return false;
	}

	if($('#objCode').val() == ""){
		alert('Դ�������Ҫ��д');
		return false;
	}

	if($('#invoiceNo').val() == ""){
		alert('��Ʊ������Ҫ��д');
		return false;
	}

    var managerNameObj = $('#managerName');
    if(managerNameObj.length == 1){
        if(managerNameObj.val() == ""){
            alert('��ѡ������');
            return false;
        }
    }

    if($('#salesmanId').val() == ""){
        alert('��ѡ��ҵ��Ա');
        return false;
    }

	if($('#deptId').val() == ""){
		alert('��ѡ����');
		return false;
	}

    if($('#invoiceUnitType').val() == ""){
        alert('��ѡ��ͻ�����');
        return false;
    }

    // ��ȡѡ�з�Ʊ����������
	var innerInvType = $('#invoiceType').find("option:selected").attr("e2");
	if(innerInvType == 'ZLHTFP'){//���޺�ͬ��ֵ��Ʊ
		var rentBeginDate = $("#rentBeginDate");
		if(strTrim(rentBeginDate.val()) == ""){
			alert('���޿�ʼ���ڱ�����д');
			rentBeginDate.val("").focus();
			return false;
		}
		
		var rentEndDate = $("#rentEndDate");
		if(strTrim(rentEndDate.val()) == ""){
			alert('���޽������ڱ�����д');
			rentEndDate.val("").focus();
			return false;
		}
		
		if(rentBeginDate.val() > rentEndDate.val()){
			alert("���޽������ڲ����������޿�ʼ����");
			return false;
		}
	}else if(innerInvType == 'ZLHTPT'){//���޺�ͬ��ͨ��Ʊ
		var rentBeginDate = $("#rentBeginDate").val();
		var rentEndDate = $("#rentEndDate").val();
		if(rentBeginDate != "" && rentEndDate != ""){
			if(rentBeginDate > rentEndDate){
				alert("���޽������ڲ����������޿�ʼ����");
				return false;
			}
		}
	}
	
	var invoiceContentsArr = [];
	var invoiceEqu ;//��Ʊ��Ŀ

	var psTypeArr = [];
	var psType ;//��Ʒ����

	var rowsNumber = $("#invnumber").val()*1;
	var rowMoney = 0;
	for(var i = 1;i<= rowsNumber ; i++){
		//�����¼�����ڣ������ѭ��
		if($("#invoiceEquName" + i).length == 0){
			continue;
		}

		invoiceEqu = $("#invoiceEquName" + i).val();
		//ѭ������Ʊ��Ŀ
		if (invoiceContentsArr.indexOf(invoiceEqu) == -1){
			invoiceContentsArr.push(invoiceEqu);
		}

		psType = $("#psType" + i).find("option:selected").attr("title");
		//ѭ������Ʊ��Ŀ
		if (psTypeArr.indexOf(psType) == -1){
			psTypeArr.push(psType);
		}

		if(strTrim(invoiceEqu) == "" ){
			alert('������ϸ�еĻ�Ʒ����/������Ŀ���ܴ��ڿ���');
			$("#invoiceEquName" + i).val("");
			return false;
		}
		if(strTrim($("#amount" + i).val()) == "" || $("#amount" + i).val()*1 == "" ){
			alert('������ϸ�д�������Ϊ�յ���');
			$("#amount" + i).val("");
			return false;
		}
		rowMoney = accAdd($("#softMoney" + i).val(),rowMoney,2);
		rowMoney = accAdd($("#hardMoney" + i).val(),rowMoney,2);
		rowMoney = accAdd($("#repairMoney" + i).val(),rowMoney,2);
		rowMoney = accAdd($("#serviceMoney" + i).val(),rowMoney,2);
		rowMoney = accAdd($("#equRentalMoney" + i).val(),rowMoney,2);
		rowMoney = accAdd($("#spaceRentalMoney" + i).val(),rowMoney,2);
		rowMoney = accAdd($("#otherMoney" + i).val(),rowMoney,2);
		rowMoney = accAdd($("#dsEnergyCharge" + i).val(),rowMoney,2);
		rowMoney = accAdd($("#dsWaterRateMoney" + i).val(),rowMoney,2);
		rowMoney = accAdd($("#houseRentalFee" + i).val(),rowMoney,2);
		rowMoney = accAdd($("#installationCost" + i).val(),rowMoney,2);
		if(rowMoney == 0 || rowMoney == "" ){
			alert('������ϸ�д����ܽ��Ϊ0����');
			return false;
		}
	}

	//���ÿ�Ʊ��ϸ
	$("#invoiceContent").val(invoiceContentsArr.toString());

	//���ÿ�Ʊ����
	$("#psType").val(psTypeArr.toString());

	if($('#invoiceMoney').val() == "" || $('#invoiceMoney').val()*1 == 0){
		alert('�����뿪Ʊ��ϸ');
		return false;
	}

	//����������֤
	var cmps = $("#checkTable").yxeditgrid("getCmpByCol", "checkMoney");
	if(cmps.length == 0 && !confirm('û����д������¼,ȷ���ύ��')){
		return false;
	}
	var checkMoneyRs = true;
	if(cmps.length > 0){
		var allCheckMoney = 0;
		cmps.each(function(i,n) {
			if($(this).val() == "" || $(this).val() == 0){
				alert('�����������ջ�Ϊ0');
				checkMoneyRs = false;
				return false;
			}
			allCheckMoney = accAdd(allCheckMoney,$(this).val(),2);
		});
		if(checkMoneyRs == false){
			return false;
		}
		//���˵�ɾ������
		if(allCheckMoney == 0 || allCheckMoney != rowMoney){
			alert('���������ڿ�Ʊ���������޸�');
			checkMoneyRs = false;
			return false;
		}
		if(checkMoneyRs == false){
			return false;
		}
	}

	return true;
}