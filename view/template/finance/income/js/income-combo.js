$(function() {
	if($("#TO_ID").length!=0){
		$("#TO_NAME").yxselect_user({
			mode : 'check',
			hiddenId : 'TO_ID',
			formCode : 'income'
		});
	}

	if($("#ADDIDS").length!=0){
		$("#ADDNAMES").yxselect_user({
			mode : 'check',
			hiddenId : 'ADDIDS',
			formCode : 'income'
		});
	}

    // ��ʼ������
    if($("#id").length > 0){
        var currency = $("#currency").val();
        if(currency != '�����'){
            $("#currencyInfo").show();
            $("#currencyShow").text(currency);
            $("#incomeMoney_v").removeClass('txt').addClass('readOnlyTxtNormal');
        }else{
            $("#currencyInfo").hide();
            $("#incomeMoney_v").addClass('txt').removeClass('readOnlyTxtNormal');
        }
    }
});

// ���õ�������ң�
function changeIncomeMoney(){
    var incomeMoney = accMul($("#incomeCurrency").val(),$("#rate").val(),2);
    setMoney('incomeMoney',incomeMoney,2);
}

// ���õ�����
function changeincomeCurrency(){
    var incomeCurrency = accDiv($("#incomeMoney").val(),$("#rate").val(),2);
    setMoney('incomeCurrency',incomeCurrency,2);
}

//����֤
function checkform(){
	if($("#incomeUnitId").val() == ""){
		alert("��ͨ�����������ȷѡ�񵽿λ");
		return false;
	}
	if($("#incomeMoney").val() == "" || $("#incomeMoney").val()*1 == 0 ){
		alert("�������Ϊ0���߿�");
		return false;
	}
    if($("#currency").val() == ""){
        alert('��ѡ�񵽿�ұ�');
        return false;
    }
    if($("#businessBelongName").val() == ""){
        alert('�����������˾');
        return false;
    }
    // ��������ֶ�����
    $("#allotAble").val($("#incomeMoney").val());
    $("#allotCurrency").val($("#incomeCurrency").val());
}

/**
 * ���������뵽�������
 */
function toSubmit() {
    if($("#incomeUnitId").val() == ''){
        alert('��ѡ��ͻ�');
        return false;
    }

    if($("#businessBelongName").val() == ''){
        alert('��ѡ�������˾');
        return false;
    }

    var incomeMoney = $("#incomeMoney").val();
    if(incomeMoney == '' || incomeMoney*1 == 0){
        alert('�����뵥�ݽ��');
        return false;
    }

    var isEmpty = false;
    //��ȡ����ӱ�����
    var objGrid = $("#allotTable"); // ����ӱ����
    objGrid.yxeditgrid('getCmpByCol','objId').each(function(){
        if($(this).val() == ''){
            isEmpty = true;
        }
    });

    if(isEmpty == true ){
        alert('������Ų���Ϊ�գ�');
        return false;
    }

    //��ȡ����ӱ�����
    var allotMoney = incomeMoney; // �������
    objGrid.yxeditgrid('getCmpByCol','money').each(function(){
        if($(this).val() != '' && $(this).val()*1 != 0){
            allotMoney = accSub(allotMoney,$(this).val(),2);
        }
    });

    if(allotMoney < 0){
        alert('������ܴ��ڵ�����');
        return false;
    }else{
        $("#allotMoney").val(allotMoney);
    }
}
