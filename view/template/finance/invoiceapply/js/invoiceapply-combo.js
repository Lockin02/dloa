//��Ʊ��ʼ��ѡ��
$thisInitCode = 'objCode';
$thisInitId = 'objId';
$thisInitName = 'objName';
$thisInitType = 'objType';

//��ʼ�����
function initGrid(thisVal){
	$("#" + $thisInitCode).yxcombogrid_allcontract('remove');

	$("#" + $thisInitCode).val('');
	$("#" + $thisInitName).val('');
	$("#" + $thisInitId).val('');

	initGridNoEmpty(thisVal);
}

//��ʼ�����,�����
function initGridNoEmpty(thisVal){
	switch(thisVal){
		case 'KPRK-12' : initContract();break;
		default : break;
	}
}

//��ʼ���������ͱ��
function initContract(){
	$("#" + $thisInitCode).yxcombogrid_allcontract({
		hiddenId : $thisInitId,
		height : 300,
		width : 700,
		nameCol : 'contractCode',
		isFocusoutCheck : false,
		gridOptions : {
			param : {"customerName":$("#customerName").val()  , 'ExaStatus' : '���'},
			showcheckbox :false,
			event : {
				'row_dblclick' : function(e,row,data) {
					if($('#getLastInfoUrl').val() != undefined){
						var url = $('#getLastInfoUrl').val()+"&customerId="+data.customerId+"&objType="+$('#objTypeView').val();
						$('#getLastInfoUrl').val(url);
						changeInvType('invoiceType');
					}

					$("#" + $thisInitType).val('KPRK-12');

                    if(data.currency == '�����'){
                        var canApply = accSub(data.contractMoney,data.invoiceApplyMoney,2);
                        canApply = accSub(canApply,data.uninvoiceMoney,2);//�۳�����Ʊ���
                        canApply = accSub(canApply,data.deductMoney,2);//�۳��ۿ���

                        //��ȡ��Ʊ���
                        var invoiceRedMoney = $.ajax({
                            url : '?model=finance_invoice_invoice&action=getInvoiceRecordsMoney',
                            type : "POST",
                            data : { "objCode" : data.contractCode, "objType" : 'red' },//objType�����жϺ���Ʊ��red / blue����������ֵĬ��Ϊͳ�ƺ������ֺ���ܺ�
                            async : false
                        }).responseText;
                        canApply = accAdd(canApply,invoiceRedMoney,2);//���Ϻ�Ʊ���

                        $("#contAmount").val(data.contractMoney);
                        $("#contAmountView").val(moneyFormat2(data.contractMoney));
                    }else{
                        var invoiceApplyMoney = $.ajax({
                            url : '?model=finance_invoiceapply_invoiceapply&action=getApplyedMoney',
                            type : "POST",
                            data : { "objId" : data.id , "objType" : 'KPRK-12'},
                            async : false
                        }).responseText;
                        var canApply = accSub(data.contractMoneyCur,invoiceApplyMoney,2);
                        $("#contAmount").val(data.contractMoneyCur);
                        $("#contAmountView").val(moneyFormat2(data.contractMoneyCur));
                    }

			   		$("#canApply").val(canApply);
			   		$("#canApply_v").val(moneyFormat2(canApply));
                    $("#contLocal").val(data.contractMoney);
                    $("#currency").val(data.currency);
                    $("#rate").val(data.rate);

					$("#" + $thisInitId).val(data.id);
					//ҵ��������
					$("#rObjCode").val(data.objCode);
					//������˾����
					$("#businessBelongName").val(data.businessBelongName);
					$("#businessBelong").val(data.businessBelong);
                    $("#formBelong").val(data.formBelong);
                    $("#formBelongName").val(data.formBelongName);
				}
			}
		}
	});
}

/************************************���÷���***************************************/
//��ؿ�Ʊѡ�񴥷��¼�
function changeMailInfo(thisVal){
	$.ajax({
	    type: "POST",
	    url: "?model=finance_invoiceapply_invoiceapply&action=getMailInfo",
	    data: {"thisVal" : thisVal},
	    async: false,
	    success: function(data){
	    	arr = eval("(" + data + ")");
	    	for(i in arr){
				if(i == 'sendName'){
					$("#TO_NAME").val(arr[i]);
				}else{
					$("#TO_ID").val(arr[i]);
				}
	    	}
		}
	});
}

// ��ȡ��Ʊ��Ϣ�в����û���Ϣ����˰�ţ������С������˻���Ϣ��ID2245 2016-11-30
var isEdit = 0;
function getInfoJson(){
	var editmark = $('#getLastInfoUrl').attr('data-edit');// �ֱ��Ƿ�Ϊ�༭ҳ���༭ҳ�״δ򿪲���̬��������
	isEdit = (editmark == '1' && isEdit == '0')? '1' : isEdit;
	if(isEdit != '1'){
		var invoiceType = $('#invoiceType').val();
		var infourl = $('#getLastInfoUrl').val();
		var objCode = $('#objCode').val();
		var returnValue = $.ajax({
			type : 'POST',
			url : "?model=finance_invoiceapply_invoiceapply&action=getLastInfoJson&"+infourl+"&invoiceType="+invoiceType+"&objCode="+objCode,
			async : false,
			success : function(data) {
			}
		}).responseText;
		returnValue = eval("(" + returnValue + ")");
		return returnValue;
	}else{
		isEdit = 'ok';
		$('#getLastInfoUrl').removeAttr('data-edit');
		return [];
	}
}

//��Ʊ���Ͷ�Ӧ�ֶ�
function changeInvType(thisVal){
	// ��̬��ȡ�û���Ϣ(����ѡ���ͼ�¼��ȡ�������ݣ�û�е�ȡ���������������µ�����) ID2245 2016-11-30
	var userInfo = getInfoJson();
	var phoneNo = (userInfo.msg == 'ok')? userInfo.data.phoneNo:$("#phoneNo").val();//��Ʊ��λ�绰
	var taxpayerId = (userInfo.msg == 'ok')? userInfo.data.taxpayerId :$("#taxpayerId").val();//��˰��ʶ���
	var bank = (userInfo.msg == 'ok')? userInfo.data.bank :$("#bank").val();//��������
	var bankCount = (userInfo.msg == 'ok')? userInfo.data.bankCount :$("#bankCount").val();//�����˺�
	// �û���ϵ��ʽ��Ϣ
	if(userInfo.msg == 'ok' && userInfo.data.linkMan != '' && userInfo.data.linkPhone != '' && userInfo.data.unitAddress != ''){
		$("#linkMan").val(userInfo.data.linkMan);
		$("#linkPhone").val(userInfo.data.linkPhone);
		$("#unitAddress").val(userInfo.data.unitAddress);
	}

	innerInvType = $("#" + thisVal).find("option:selected").attr("e2");
	switch(innerInvType){
		case 'ZZSFP':
			$("#linkAddressNeed").html("");
			$("#unitAddressNeed").html("[*]");

			$("#phoneNoNeed").html("[*]");
			$(".phoneNo").show();$("#phoneNo").val(phoneNo);

			$("#taxpayerIdNeed").html("[*]");
			$("#bankNeed").html("[*]");
			$("#bankCountNeed").html("[*]");
			$(".taxpayerNo").show();$("#taxpayerId").val(taxpayerId);
			$(".bankInfo").show();
			$("#bank").val(bank);
			$("#bankCount").val(bankCount);
			
			$(".rentInfo").hide();
			$("#rentBeginDate").val("");
			$("#rentEndDate").val("");
			$("#rentDays").val("");
			break;
		case 'FWFP':
			$("#linkAddressNeed").html("[*]");
			$("#unitAddressNeed").html("");

			$("#phoneNoNeed").html("");
			$(".phoneNo").hide();
			$("#phoneNo").val("");

			$("#taxpayerIdNeed").html("[*]");
			$("#bankNeed").html("");
			$("#bankCountNeed").html("");
			$(".taxpayerNo").show();$("#taxpayerId").val(taxpayerId);
			$(".bankInfo").hide();
			$("#bank").val("");
			$("#bankCount").val("");
			
			$(".rentInfo").hide();
			$("#rentBeginDate").val("");
			$("#rentEndDate").val("");
			$("#rentDays").val("");
			break;
		case 'ZZSPT':
			$("#linkAddressNeed").html("");
			$("#unitAddressNeed").html("[*]");

			$("#phoneNoNeed").html("[*]");
			$(".phoneNo").show();$("#phoneNo").val(phoneNo);

			$("#taxpayerIdNeed").html("[*]");
			$("#bankNeed").html("[*]");
			$("#bankCountNeed").html("[*]");
			$(".taxpayerNo").show();$("#taxpayerId").val(taxpayerId);
			$(".bankInfo").show();
			$("#bank").val(bank);
			$("#bankCount").val(bankCount);
			
			$(".rentInfo").hide();
			$("#rentBeginDate").val("");
			$("#rentEndDate").val("");
			$("#rentDays").val("");
			break;
		case 'ZLHTFP':
			$("#linkAddressNeed").html("");
			$("#unitAddressNeed").html("[*]");

			$("#phoneNoNeed").html("[*]");
			$(".phoneNo").show();$("#phoneNo").val(phoneNo);

			$("#taxpayerIdNeed").html("[*]");
			$("#bankNeed").html("[*]");
			$("#bankCountNeed").html("[*]");
			$(".taxpayerNo").show();$("#taxpayerId").val(taxpayerId);
			$(".bankInfo").show();
			$("#bank").val(bank);
			$("#bankCount").val(bankCount);
			
			$("#rentBeginDateNeed").html("[*]");
			$("#rentEndDateNeed").html("[*]");
			$(".rentInfo").show();
			break;
		case 'ZLHTPT':
			$("#linkAddressNeed").html("");
			$("#unitAddressNeed").html("[*]");

			$("#phoneNoNeed").html("[*]");
			$(".phoneNo").show();$("#phoneNo").val(phoneNo);

			$("#taxpayerIdNeed").html("[*]");
			$("#bankNeed").html("[*]");
			$("#bankCountNeed").html("[*]");
			$(".taxpayerNo").show();$("#taxpayerId").val(taxpayerId);
			$(".bankInfo").show();
			$("#bank").val(bank);
			$("#bankCount").val(bankCount);
			
			$("#rentBeginDateNeed").html("[*]");
			$("#rentEndDateNeed").html("[*]");
			$(".rentInfo").show();
			break;
		default :
			$("#linkAddressNeed").html("");
			$("#unitAddressNeed").html("[*]");

			$("#phoneNoNeed").html("");
			$(".phoneNo").hide();
			$("#phoneNo").val("");

			$("#taxpayerIdNeed").html("");
			$("#bankNeed").html("");
			$("#bankCountNeed").html("");
			$(".taxpayerNo").hide();
			$("#taxpayerId").val("");
			$(".bankInfo").hide();
			$("#bank").val("");
			$("#bankCount").val("");
			
			$(".rentInfo").hide();
			$("#rentBeginDate").val("");
			$("#rentEndDate").val("");
			$("#rentDays").val("");
			break;
	}
}

//���÷���
function countDetail(obj){
	if(isNaN(obj.value.replace(/,|\s/g,''))){
		alert('����������')
		obj.value = "";
	}
	countFun();
}

function countFun(){
	var detailRows = $('#invnumber').val();
	var amount = 0;
	var allMoney = 0;

	var allSoftMoney = 0;
	var allHardMoney = 0;
	var allRepairMoney = 0;
	var allServiceMoney = 0;
	var allEquRentalMoney = 0;
	var allSpaceRentalMoney = 0;
	var allOtherMoney = 0;
	var allDsEnergyCharge = 0;
	var allDsWaterRateMoney = 0;
	var allHouseRentalFee = 0;
	var allInstallationCost = 0;
	for(var i = 1;i <= detailRows ;i++){
		if($('#amount'+i).length == 0){
			continue;
		}
		thisAmount = $('#amount'+i).val();
		thisSoftMoney = $('#softMoney'+i).val();
		thisHardMoney = $('#hardMoney'+i).val();
		thisRepairMoney = $('#repairMoney'+i).val();
		thisServiceMoney = $('#serviceMoney'+i).val();
		thisEquRentalMoney = $('#equRentalMoney'+i).val();
		thisSpaceRentalMoney = $('#spaceRentalMoney'+i).val();
		thisOtherMoney = $('#otherMoney'+i).val();
		thisDsEnergyCharge = $('#dsEnergyCharge'+i).val();
		thisDsWaterRateMoney = $('#dsWaterRateMoney'+i).val();
		thisHouseRentalFee = $('#houseRentalFee'+i).val();
		thisInstallationCost = $('#installationCost'+i).val();


		allSoftMoney = accAdd(allSoftMoney,thisSoftMoney,2);
		allHardMoney = accAdd(allHardMoney,thisHardMoney,2);
		allRepairMoney = accAdd(allRepairMoney,thisRepairMoney,2);
		allServiceMoney = accAdd(allServiceMoney,thisServiceMoney,2);
		allEquRentalMoney = accAdd(allEquRentalMoney,thisEquRentalMoney,2);
		allSpaceRentalMoney = accAdd(allSpaceRentalMoney,thisSpaceRentalMoney,2);
		allOtherMoney = accAdd(allOtherMoney,thisOtherMoney,2);
		allDsEnergyCharge = accAdd(allDsEnergyCharge,thisDsEnergyCharge,2);
		allDsWaterRateMoney = accAdd(allDsWaterRateMoney,thisDsWaterRateMoney,2);
		allHouseRentalFee = accAdd(allHouseRentalFee,thisHouseRentalFee,2);
		allInstallationCost = accAdd(allInstallationCost,thisInstallationCost,2);

		amount = accAdd(amount,thisAmount,2);
	}

	//���ݽ��
	allMoney = accAdd(allSoftMoney,allMoney,2);
	allMoney = accAdd(allHardMoney,allMoney,2);
	allMoney = accAdd(allRepairMoney,allMoney,2);
	allMoney = accAdd(allServiceMoney,allMoney,2);
	allMoney = accAdd(allEquRentalMoney,allMoney,2);
	allMoney = accAdd(allSpaceRentalMoney,allMoney,2);
	allMoney = accAdd(allOtherMoney,allMoney,2);
	allMoney = accAdd(allDsEnergyCharge,allMoney,2);
	allMoney = accAdd(allDsWaterRateMoney,allMoney,2);
	allMoney = accAdd(allHouseRentalFee,allMoney,2);
	allMoney = accAdd(allInstallationCost,allMoney,2);

	$('#view_softMoney').val(allSoftMoney);
	$('#view_softMoney_v').val(moneyFormat2(allSoftMoney));

	$('#view_hardMoney').val(allHardMoney);
	$('#view_hardMoney_v').val(moneyFormat2(allHardMoney));

	$('#view_repairMoney').val(allRepairMoney);
	$('#view_repairMoney_v').val(moneyFormat2(allRepairMoney));

	$('#view_serviceMoney').val(allServiceMoney);
	$('#view_serviceMoney_v').val(moneyFormat2(allServiceMoney));

	$('#view_equRentalMoney').val(allEquRentalMoney);
	$('#view_equRentalMoney_v').val(moneyFormat2(allEquRentalMoney));

	$('#view_spaceRentalMoney').val(allSpaceRentalMoney);
	$('#view_spaceRentalMoney_v').val(moneyFormat2(allSpaceRentalMoney));

	$('#view_otherMoney').val(allOtherMoney);
	$('#view_otherMoney_v').val(moneyFormat2(allOtherMoney));

	$('#view_dsEnergyCharge').val(allDsEnergyCharge);
	$('#view_dsEnergyCharge_v').val(moneyFormat2(allDsEnergyCharge));
	$('#view_dsWaterRateMoney').val(allDsWaterRateMoney);
	$('#view_dsWaterRateMoney_v').val(moneyFormat2(allDsWaterRateMoney));
	$('#view_houseRentalFee').val(allHouseRentalFee);
	$('#view_houseRentalFee_v').val(moneyFormat2(allHouseRentalFee));
	$('#view_installationCost').val(allInstallationCost);
	$('#view_installationCost_v').val(moneyFormat2(allInstallationCost));

	//��������
	$('#allAmount').val(amount);

	//������
	$('#invoiceMoney').val(allMoney);
	$('#invoiceMoney_v').val(moneyFormat2(allMoney));

    // ���������ң�
    var invoiceLocal = accMul(allMoney,$("#rate").val(),2);
    $('#invoiceLocal').val(invoiceLocal);
}


//����֤����
function checkform(){
	var checkedObj = $('#invoiceType');

	if(strTrim($('#customerName').val()) == ""){
		alert('�ͻ����Ʊ�����д');
		return false;
	}

	if($('#customerId').val() == ""){
		alert('�����������жԿͻ�����ѡȡ����������޲��ҵĿͻ�������ϵ��ظ����˶Կͻ��������');
		return false;
	}

	var objCode = strTrim($('#objCode').val());
	var objType = $("#objType").val();
	if(objCode == ""){
		alert('Դ����ű�����д');
		return false;
	}else{
		if(objType == "" && objCode.indexOf('-') > 0){
			alert('Դ�����в��������"-"��');
			return false;
		}
	}

	var linkMan = $("#linkMan");
	if(strTrim(linkMan.val()) == ""){
		alert('��ϵ�˱�����д');
		linkMan.val("").focus();
		return false;
	}

	var linkPhone = $("#linkPhone");
	if(strTrim(linkPhone.val()) == ""){
		alert('��ϵ�绰������д');
		linkPhone.val("").focus();
		return false;
	}

    // ��ȡѡ�з�Ʊ����������
	innerInvType = checkedObj.find("option:selected").attr("e2");

	//��˰��ʶ������⴦��  update chenrf
	var taxpayerId = $("#taxpayerId");
	var taxpayerIdVal = strTrim(taxpayerId.val());
	if(taxpayerIdVal != ""){
		if(taxpayerIdVal.length != 15 && taxpayerIdVal.length != 18){
			alert('��˰��ʶ���Ϊ15λ��18λ�ַ�����ǰʶ���Ϊ' + taxpayerIdVal.length + 'λ');
			return false;
		}
	}

	if(innerInvType == 'ZZSFP' || innerInvType == 'ZLHTFP'){//��ֵ˰ר�÷�Ʊ�����޺�ͬ��ֵ��Ʊ

		var unitAddress = $("#unitAddress");
		if(strTrim(unitAddress.val()) == ""){
			alert('��Ʊ��λ��ַ������д');
			unitAddress.val("").focus();
			return false;
		}

		var phoneNo = $("#phoneNo");
		if(strTrim(phoneNo.val()) == ""){
			alert('��Ʊ��λ�绰������д');
			phoneNo.val("").focus();
			return false;
		}

		//��˰��ʶ������⴦��
		var taxpayerId = $("#taxpayerId");
		var taxpayerIdVal = strTrim(taxpayerId.val());
		if(taxpayerIdVal == ""){
			alert('��˰��ʶ��ű�����д');
			taxpayerId.val("").focus();
			return false;
		}else{
			if(taxpayerIdVal.length != 15 && taxpayerIdVal.length != 18){
				alert('��˰��ʶ���Ϊ15λ��18λ�ַ�����ǰʶ���Ϊ' + taxpayerIdVal.length + 'λ');
				return false;
			}
		}

		var bank = $("#bank");
		if(strTrim(bank.val()) == ""){
			alert('�������б�����д');
			bank.val("").focus();
			return false;
		}

		var bankCount = $("#bankCount");
		if(strTrim(bankCount.val()) == ""){
			alert('�����ʺű�����д');
			bankCount.val("").focus();
			return false;
		}
		
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
		}
	}else if(innerInvType == 'FWFP'){//����Ʊ

		var linkAddress = $("#linkAddress");
		if(strTrim(linkAddress.val()) == ""){
			alert('��Ʊ�ʼĵ�ַ������д');
			linkAddress.val("").focus();
			return false;
		}

		//��˰��ʶ������⴦��
		var taxpayerId = $("#taxpayerId");
		var taxpayerIdVal = strTrim(taxpayerId.val());
		if(taxpayerIdVal == ""){
			alert('��˰��ʶ��ű�����д');
			taxpayerId.val("").focus();
			return false;
		}else{
			if(taxpayerIdVal.length != 15 && taxpayerIdVal.length != 18){
				alert('��˰��ʶ���Ϊ15λ��18λ�ַ�����ǰʶ���Ϊ' + taxpayerIdVal.length + 'λ');
				return false;
			}
		}
	}else if(innerInvType == 'ZZSPT' || innerInvType == 'ZLHTPT'){ //��ֵ˰��ͨ��Ʊ�����޺�ͬ��ͨ��Ʊ
		var unitAddress = $("#unitAddress");
		if(strTrim(unitAddress.val()) == ""){
			alert('��Ʊ��λ��ַ������д');
			unitAddress.val("").focus();
			return false;
		}

		var phoneNo = $("#phoneNo");
		if(strTrim(phoneNo.val()) == ""){
			alert('��Ʊ��λ�绰������д');
			phoneNo.val("").focus();
			return false;
		}

		//��˰��ʶ������⴦��
		var taxpayerId = $("#taxpayerId");
		var taxpayerIdVal = strTrim(taxpayerId.val());
		if(taxpayerIdVal == ""){
			alert('��˰��ʶ��ű�����д');
			taxpayerId.val("").focus();
			return false;
		}else{
			if(taxpayerIdVal.length != 15 && taxpayerIdVal.length != 18){
				alert('��˰��ʶ���Ϊ15λ��18λ�ַ�����ǰʶ���Ϊ' + taxpayerIdVal.length + 'λ');
				return false;
			}
		}
		var bank = $("#bank");
		if(strTrim(bank.val()) == ""){
			alert('�������б�����д');
			bank.val("").focus();
			return false;
		}

		var bankCount = $("#bankCount");
		if(strTrim(bankCount.val()) == ""){
			alert('�����ʺű�����д');
			bankCount.val("").focus();
			return false;
		}
		
		if(innerInvType == 'ZLHTPT'){//���޺�ͬ��ͨ��Ʊ
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
		}
	}else{
		var unitAddress = $("#unitAddress");
		if(strTrim(unitAddress.val()) == ""){
			alert('��Ʊ��λ��ַ������д');
			unitAddress.val("").focus();
			return false;
		}
	}

	rowsNumber = $("#invnumber").val()*1;
	var thisInvoiceMoney = 0;
	for(var i = 1;i<= rowsNumber ; i++){
		if($("#invoiceEquName" + i).length == 0){
			continue;
		}
		if(strTrim($("#invoiceEquName" + i).val()) == "" ){
			alert('������ϸ�еĻ�Ʒ����/������Ŀ���ܴ��ڿ���');
			$("#invoiceEquName" + i).val("");
			return false;
		}
		if(strTrim($("#amount" + i).val()) == "" || $("#amount" + i).val()*1 == "" ){
			alert('������ϸ�д�������Ϊ�յ���');
			$("#amount" + i).val("");
			return false;
		}

		var rowMoney = 0;
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
		if($("#psTyle" + i).val() == "" ){
			alert('������ϸ�еĲ�Ʒ/�������Ͳ��ܴ��ڿ���');
			return false;
		}

		thisInvoiceMoney = accAdd(thisInvoiceMoney,rowMoney,2);
	}

	//���浥�ݽ�����
	var invoiceMoneyObj = $('#invoiceMoney');

	if(thisInvoiceMoney != invoiceMoneyObj.val()*1){
		alert('�������������������ϸ�ܶ�');
		return false;
	}

	if(invoiceMoneyObj.val()*1 == 0 || invoiceMoneyObj.val() == ""){
		alert('�������Ϊ0���߿�');
		return false;
	}

	if($("#contAmount").length > 0){
		if(objType != ""){
			if(invoiceMoneyObj.val() *1 > $("#canApply").val()*1){
				alert('������('+ invoiceMoneyObj.val() +')���ں�ͬʣ���������(' + $("#canApply").val() + ')');
				return false;
			}
		}
	}

	//�ʼĲ��ֱ���֤
	var isMail = $('input[name="invoiceapply[isMail]"]:checked').val();
	if(isMail == '1'){
		if($("#expressCompany").val() == ""){//��ݹ�˾
			alert('�����Ҫ�ʼķ�Ʊ����ѡ��һ����ݹ�˾');
			return false;
		}

		if($("#postalCode").val() == ""){//��������
			alert('�����Ҫ�ʼķ�Ʊ���������ʼĵ�ַ����������');
			$("#postalCode").focus();
			return false;
		}
	}else{
		$("#expressCompany").attr('disabled',false).val('');
		$("#expressCompanyId").attr('disabled',false).val('');
		$("#postalCode").attr('disabled',false).val('');
	}

    // �ұ�
    if($("#currency").val() == ""){
        alert('�ұ���Ϊ��');
        $("#currency").focus();
        return false;
    }
	
	//���²�����֤
	var isNeedStamp = $('input[name="invoiceapply[isNeedStamp]"]:checked').val();
	if(isNeedStamp == '1'){
		if($("#stampType").val() == ""){//��ݹ�˾
			alert('�����Ҫ���£�����д��������');
			return false;
		}
	}else{
		$("#stampType").attr('disabled',false).val('');
	}

//	countFun();

	//�ύʱ������������ֶ��ٴκ˶�ȷ�ϵ�С���ڵĹ��� ID2245 2016-11-30
	if(confirm('��ע�⣬�����в���Ϊϵͳ�Զ�������Ϣ��\n���Ե����ȡ�����ٴ�ȷ����Ϣ����㡾ȷ���������ύ����')){
		$('#getLastInfoUrl').val('ok');
	}

	if($('#getLastInfoUrl').val() != 'ok'){
		return false;
	}
	$("input[type='submit']").attr('disabled',true);

	return true;
}

//�Ƿ�Ҫ�ʼ�
function changeIsMail(thisVal){
	$("#expressCompany").yxcombogrid_logistics('remove');

	if(thisVal == 1){
		$("#expressCompany").attr('disabled',false);
		$("#expressCompanyId").attr('disabled',false);
		$("#postalCode").attr('disabled',false);
		$("#mailInfo").show();
		//��ݹ�˾��Ⱦ
		initExpressCompany();
	}else{
		$("#expressCompany").attr('disabled',true);
		$("#expressCompanyId").attr('disabled',true);
		$("#postalCode").attr('disabled',true);
		$("#mailInfo").hide();
	}
}

//�Ƿ����
function changeIsStamp(t){
	if(t == 1){
		//����������֤
		if($("#uploadfileList").html() == "" || $("#uploadfileList").html() == "�����κθ���"){
			alert('�������ǰ��Ҫ�ϴ���Ҫ���µĸ���!');
			$("#isNeedStampNo").attr("checked",true);
			return false;
		}
		
		//��ֹ�ظ������������
		if($("#stampType").yxcombogrid_stampconfig('getIsRender') == true) return false;
		//����������Ⱦ
		$("#stampType").yxcombogrid_stampconfig({
			hiddenId : 'stampType',
			height : 250,
			gridOptions : {
				isTitle : true,
				showcheckbox : true
			}
		});
		$("#stampType").attr('disable',false);
		$("#radioSpan").show();
	}else{
		$("#radioSpan").hide();
		
		//����������Ⱦ
		var stampTypeObj = $("#stampType");
		stampTypeObj.yxcombogrid_stampconfig('remove');
		stampTypeObj.val('');
		$("#stampType").attr('disable',true);
	}
}

//ʵ�����ʼĹ�˾
function initExpressCompany(){
	$("#expressCompany").yxcombogrid_logistics({
		hiddenId : 'expressCompanyId',
		gridOptions : {
			showcheckbox : false,
			event : {
				'row_dblclick' : function(e,row,data) {

				}
			}
		}
	});
}

/**********************��̬����б�*************************/
function detailAdd(tablelist,countNumP){
	mycount = document.getElementById(countNumP).value*1 + 1;
	var tablelist = document.getElementById(tablelist);
	i=tablelist.rows.length;
	oTR =tablelist.insertRow([i]);
	oTL0=oTR.insertCell([0]);
	oTL0.innerHTML=i;
	oTL1=oTR.insertCell([1]);
	oTL1.innerHTML="<input type='text' name='invoiceapply[invoiceDetail]["+mycount+"][productName]' id='invoiceEquName"+mycount+"' onblur='isEmpty(this,\"" + mycount +"\")' readonly='readonly' class='txtmiddle'/><input type='hidden' name='invoiceapply[invoiceDetail]["+mycount+"][productId]' id='invoiceEquId"+mycount+"'/>";
	oTL2=oTR.insertCell([2]);
	oTL2.innerHTML="<input type='text' name='invoiceapply[invoiceDetail]["+mycount+"][amount]' id='amount"+mycount+"' class='txtshort' style='width:50px;'/>";
    oTL3=oTR.insertCell([3]);
    oTL3.innerHTML="<input type='text' name='invoiceapply[invoiceDetail]["+mycount+"][softMoney]' id='softMoney"+mycount+"' class='txtshort'/>";
    oTL4=oTR.insertCell([4]);
    oTL4.innerHTML="<input type='text' name='invoiceapply[invoiceDetail]["+mycount+"][hardMoney]' id='hardMoney"+mycount+"' class='txtshort'/>";
    oTL5=oTR.insertCell([5]);
    oTL5.innerHTML="<input type='text' name='invoiceapply[invoiceDetail]["+mycount+"][repairMoney]' id='repairMoney"+mycount+"' class='txtshort'/>";
    oTL6=oTR.insertCell([6]);
    oTL6.innerHTML="<input type='text' name='invoiceapply[invoiceDetail]["+mycount+"][serviceMoney]' id='serviceMoney"+mycount+"' class='txtshort'/>";
    oTL5=oTR.insertCell([7]);
    oTL5.innerHTML="<input type='text' name='invoiceapply[invoiceDetail]["+mycount+"][equRentalMoney]' id='equRentalMoney"+mycount+"' class='txtshort'/>";
    oTL6=oTR.insertCell([8]);
    oTL6.innerHTML="<input type='text' name='invoiceapply[invoiceDetail]["+mycount+"][spaceRentalMoney]' id='spaceRentalMoney"+mycount+"' class='txtshort'/>";
    oTL7=oTR.insertCell([9]);
    oTL7.innerHTML="<input type='text' name='invoiceapply[invoiceDetail]["+mycount+"][otherMoney]' id='otherMoney"+mycount+"' class='txtshort'/>";
    oTL8=oTR.insertCell([10]);

	oTL8.innerHTML="<input type='text' name='invoiceapply[invoiceDetail]["+mycount+"][dsEnergyCharge]' id='dsEnergyCharge"+mycount+"' class='txtmiddle formatMoney' style='width:100px;'/>";
	oTL9=oTR.insertCell([11]);
	oTL9.innerHTML="<input type='text' name='invoiceapply[invoiceDetail]["+mycount+"][dsWaterRateMoney]' id='dsWaterRateMoney"+mycount+"' class='txtmiddle formatMoney' style='width:100px;'/>";
	oTL10=oTR.insertCell([12]);
	oTL10.innerHTML="<input type='text' name='invoiceapply[invoiceDetail]["+mycount+"][houseRentalFee]' id='houseRentalFee"+mycount+"' class='txtmiddle formatMoney' style='width:100px;'/>";
	oTL11=oTR.insertCell([13]);
	oTL11.innerHTML="<input type='text' name='invoiceapply[invoiceDetail]["+mycount+"][installationCost]' id='installationCost"+mycount+"' class='txtmiddle formatMoney' style='width:100px;'/>";
	oTL12=oTR.insertCell([14]);

    oTL12.innerHTML="<select id='psTyle"+mycount+"' name='invoiceapply[invoiceDetail]["+mycount+"][psTyle]' class='txtmiddle' style='width:90px;'><option value=''></option></select>";
    oTL13=oTR.insertCell([15]);
    oTL13.innerHTML="<input type='text' name='invoiceapply[invoiceDetail]["+mycount+"][remark]' class='txtmiddle' style='width:100px;'/>";
    oTL14=oTR.insertCell([16]);
    oTL14.innerHTML="<img src='images/closeDiv.gif' onclick='mydel(this,\""+tablelist.id+"\")' id='deteleRow" + mycount + "' title='ɾ����'/>";

    document.getElementById(countNumP).value = document.getElementById(countNumP).value*1 + 1 ;
    addDataToSelect(invoiceTypeArr, 'psTyle'+mycount);
    //��ͳ���¼�
    $("#amount" + mycount).bind("blur",function(){
		countDetail(this);
    });
    createFormatOnClick('softMoney'+mycount);
    //��ͳ���¼�
    $("#softMoney" + mycount + "_v").bind("blur",function(){
		countDetail(this);
    });
    createFormatOnClick('hardMoney'+mycount);
    //��ͳ���¼�
    $("#hardMoney" + mycount + "_v").bind("blur",function(){
		countDetail(this);
    });
    createFormatOnClick('repairMoney'+mycount);
    //��ͳ���¼�
    $("#repairMoney" + mycount + "_v").bind("blur",function(){
		countDetail(this);
    });
    createFormatOnClick('serviceMoney'+mycount);
    //��ͳ���¼�
    $("#serviceMoney" + mycount + "_v").bind("blur",function(){
		countDetail(this);
    });
    createFormatOnClick('equRentalMoney'+mycount);
    //��ͳ���¼�
    $("#equRentalMoney" + mycount + "_v").bind("blur",function(){
		countDetail(this);
    });
    createFormatOnClick('spaceRentalMoney'+mycount);
    //��ͳ���¼�
    $("#spaceRentalMoney" + mycount + "_v").bind("blur",function(){
		countDetail(this);
    });
    createFormatOnClick('otherMoney'+mycount);
    //��ͳ���¼�
    $("#otherMoney" + mycount + "_v").bind("blur",function(){
		countDetail(this);
    });

	$("#invoiceEquName"+ mycount).yxcombogrid_datadict({
		hiddenId :  'invoiceEquId'+ mycount,
		height : 250,
		gridOptions : {
			isTitle : true,
			param : {"parentCode":"KPXM"},
			showcheckbox : false,
			event : {
				'row_dblclick' : function(mycount){
					return function(e, row, data) {
						var thisObj = $("#invoiceEquName"+ mycount);
						$("#invoiceEquId" + mycount).val(data.dataCode);
						$("#invoiceEquName" + mycount).val(data.dataName);
						thisObj.attr('readonly',"readonly");
						if(data.dataCode == "QT"){
							thisObj.attr('readonly',"");
							thisObj.val("");
							thisObj.focus();
						}
					};
				}(mycount)
			},
			// ��������
			searchitems : [{
				display : '����',
				name : 'dataName'
			}]
		}
	});
}

//��ӿۿ���Ϣ
function deductinfoAdd(tablelist,countNumP){
	mycount = document.getElementById(countNumP).value*1 + 1;
	var tablelist = document.getElementById(tablelist);
	i=tablelist.rows.length;
	oTR =tablelist.insertRow([i]);
	oTL0=oTR.insertCell([0]);
	oTL0.innerHTML=i;
	oTL1=oTR.insertCell([1]);
	oTL1.innerHTML="<input type='text' name='invoiceapply[deductinfo]["+mycount+"][grade]' id='grade"+mycount+"' class='txtmiddle'/>";
	oTL2=oTR.insertCell([2]);
	oTL2.innerHTML="<input type='text' name='invoiceapply[deductinfo]["+mycount+"][deduction]' id='deduction"+mycount+"' class='txtmiddle formatMoney'/>";
	oTL3=oTR.insertCell([3]);
    oTL3.innerHTML="<img src='images/closeDiv.gif' onclick='mydel(this,\""+tablelist.id+"\")' id='deteleRow" + mycount + "' title='ɾ����'/>";
	document.getElementById(countNumP).value = document.getElementById(countNumP).value*1 + 1 ;
	addDataToSelect(invoiceTypeArr, 'psTyle'+mycount);
	 //��ͳ���¼�
    $("#grade" + mycount ).bind("blur",function(){
		countDetail(this);
    });
    createFormatOnClick('grade'+mycount);
    //��ͳ���¼�
    $("#deduction" + mycount + "_v").bind("blur",function(){
		countDetail(this);
    });
    createFormatOnClick('deduction'+mycount);
	 
}
//�ж�������״̬,�ǵĻ��������ֵ����productId��
function isEmpty(obj,thisKey){
	underObj = $("#invoiceEquId" + thisKey);
	if(obj.value != "" && underObj.val() == "QT"){
		underObj.val(obj.value);
	}
}

function audit(){
	document.getElementById('form1').action="?model=finance_invoiceapply_invoiceapply&action=add&act=audit";
}

function auditEdit(){
	document.getElementById('form1').action="?model=finance_invoiceapply_invoiceapply&action=edit&act=audit";
}

// ��ʾ����������ҽ��
function showInvoiceLocal(){
    if($("#currency").val() != '�����'){
        $(".invoiceLocalShow").show();
        $("#currencyTd").attr('colspan',1);
    }
}

//������������
function rentDaysCount(){
	var rentBeginDate = $("#rentBeginDate").val();
	var rentEndDate = $("#rentEndDate").val();
	if(rentBeginDate != "" && rentEndDate != ""){
		if(rentBeginDate > rentEndDate){
			alert("���޽������ڲ����������޿�ʼ����");
		}else{
			$("#rentDays").val(DateDiff(rentBeginDate,rentEndDate) + 1);
		}
	}
}