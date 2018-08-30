//发票初始化选择
$thisInitCode = 'objCode';
$thisInitId = 'objId';
$thisInitName = 'objName';
$thisInitType = 'objType';

//初始化表格
function initGrid(thisVal){
	$("#" + $thisInitCode).yxcombogrid_allcontract('remove');

	$("#" + $thisInitCode).val('');
	$("#" + $thisInitName).val('');
	$("#" + $thisInitId).val('');

	initGridNoEmpty(thisVal);
}

//初始化表格,不清空
function initGridNoEmpty(thisVal){
	switch(thisVal){
		case 'KPRK-12' : initContract();break;
		default : break;
	}
}

//初始化所有类型表格
function initContract(){
	$("#" + $thisInitCode).yxcombogrid_allcontract({
		hiddenId : $thisInitId,
		height : 300,
		width : 700,
		nameCol : 'contractCode',
		isFocusoutCheck : false,
		gridOptions : {
			param : {"customerName":$("#customerName").val()  , 'ExaStatus' : '完成'},
			showcheckbox :false,
			event : {
				'row_dblclick' : function(e,row,data) {
					if($('#getLastInfoUrl').val() != undefined){
						var url = $('#getLastInfoUrl').val()+"&customerId="+data.customerId+"&objType="+$('#objTypeView').val();
						$('#getLastInfoUrl').val(url);
						changeInvType('invoiceType');
					}

					$("#" + $thisInitType).val('KPRK-12');

                    if(data.currency == '人民币'){
                        var canApply = accSub(data.contractMoney,data.invoiceApplyMoney,2);
                        canApply = accSub(canApply,data.uninvoiceMoney,2);//扣除不开票金额
                        canApply = accSub(canApply,data.deductMoney,2);//扣除扣款金额

                        //获取红票金额
                        var invoiceRedMoney = $.ajax({
                            url : '?model=finance_invoice_invoice&action=getInvoiceRecordsMoney',
                            type : "POST",
                            data : { "objCode" : data.contractCode, "objType" : 'red' },//objType用于判断红蓝票（red / blue），传其他值默认为统计红蓝互抵后的总和
                            async : false
                        }).responseText;
                        canApply = accAdd(canApply,invoiceRedMoney,2);//加上红票金额

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
					//业务编号设置
					$("#rObjCode").val(data.objCode);
					//归属公司设置
					$("#businessBelongName").val(data.businessBelongName);
					$("#businessBelong").val(data.businessBelong);
                    $("#formBelong").val(data.formBelong);
                    $("#formBelongName").val(data.formBelongName);
				}
			}
		}
	});
}

/************************************公用方法***************************************/
//异地开票选择触发事件
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

// 获取开票信息中部分用户信息（纳税号，开户行、银行账户信息）ID2245 2016-11-30
var isEdit = 0;
function getInfoJson(){
	var editmark = $('#getLastInfoUrl').attr('data-edit');// 分辨是否为编辑页，编辑页首次打开不动态加载数据
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

//发票类型对应字段
function changeInvType(thisVal){
	// 动态获取用户信息(有所选类型记录的取最新数据，没有的取所有类型里面最新的数据) ID2245 2016-11-30
	var userInfo = getInfoJson();
	var phoneNo = (userInfo.msg == 'ok')? userInfo.data.phoneNo:$("#phoneNo").val();//开票单位电话
	var taxpayerId = (userInfo.msg == 'ok')? userInfo.data.taxpayerId :$("#taxpayerId").val();//纳税人识别号
	var bank = (userInfo.msg == 'ok')? userInfo.data.bank :$("#bank").val();//开户银行
	var bankCount = (userInfo.msg == 'ok')? userInfo.data.bankCount :$("#bankCount").val();//银行账号
	// 用户联系方式信息
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

//公用方法
function countDetail(obj){
	if(isNaN(obj.value.replace(/,|\s/g,''))){
		alert('请输入数字')
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

	//单据金额
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

	//申请数量
	$('#allAmount').val(amount);

	//申请金额
	$('#invoiceMoney').val(allMoney);
	$('#invoiceMoney_v').val(moneyFormat2(allMoney));

    // 申请金额（人民币）
    var invoiceLocal = accMul(allMoney,$("#rate").val(),2);
    $('#invoiceLocal').val(invoiceLocal);
}


//表单验证方法
function checkform(){
	var checkedObj = $('#invoiceType');

	if(strTrim($('#customerName').val()) == ""){
		alert('客户名称必须填写');
		return false;
	}

	if($('#customerId').val() == ""){
		alert('请从下拉表格中对客户进行选取，若表格中无查找的客户，请联系相关负责人对客户进行添加');
		return false;
	}

	var objCode = strTrim($('#objCode').val());
	var objType = $("#objType").val();
	if(objCode == ""){
		alert('源单编号必须填写');
		return false;
	}else{
		if(objType == "" && objCode.indexOf('-') > 0){
			alert('源单号中不允许存在"-"号');
			return false;
		}
	}

	var linkMan = $("#linkMan");
	if(strTrim(linkMan.val()) == ""){
		alert('联系人必须填写');
		linkMan.val("").focus();
		return false;
	}

	var linkPhone = $("#linkPhone");
	if(strTrim(linkPhone.val()) == ""){
		alert('联系电话必须填写');
		linkPhone.val("").focus();
		return false;
	}

    // 获取选中发票的隐含类型
	innerInvType = checkedObj.find("option:selected").attr("e2");

	//纳税人识别号特殊处理  update chenrf
	var taxpayerId = $("#taxpayerId");
	var taxpayerIdVal = strTrim(taxpayerId.val());
	if(taxpayerIdVal != ""){
		if(taxpayerIdVal.length != 15 && taxpayerIdVal.length != 18){
			alert('纳税人识别号为15位或18位字符，当前识别号为' + taxpayerIdVal.length + '位');
			return false;
		}
	}

	if(innerInvType == 'ZZSFP' || innerInvType == 'ZLHTFP'){//增值税专用发票或租赁合同增值发票

		var unitAddress = $("#unitAddress");
		if(strTrim(unitAddress.val()) == ""){
			alert('开票单位地址必须填写');
			unitAddress.val("").focus();
			return false;
		}

		var phoneNo = $("#phoneNo");
		if(strTrim(phoneNo.val()) == ""){
			alert('开票单位电话必须填写');
			phoneNo.val("").focus();
			return false;
		}

		//纳税人识别号特殊处理
		var taxpayerId = $("#taxpayerId");
		var taxpayerIdVal = strTrim(taxpayerId.val());
		if(taxpayerIdVal == ""){
			alert('纳税人识别号必须填写');
			taxpayerId.val("").focus();
			return false;
		}else{
			if(taxpayerIdVal.length != 15 && taxpayerIdVal.length != 18){
				alert('纳税人识别号为15位或18位字符，当前识别号为' + taxpayerIdVal.length + '位');
				return false;
			}
		}

		var bank = $("#bank");
		if(strTrim(bank.val()) == ""){
			alert('开户银行必须填写');
			bank.val("").focus();
			return false;
		}

		var bankCount = $("#bankCount");
		if(strTrim(bankCount.val()) == ""){
			alert('银行帐号必须填写');
			bankCount.val("").focus();
			return false;
		}
		
		if(innerInvType == 'ZLHTFP'){//租赁合同增值发票
			var rentBeginDate = $("#rentBeginDate");
			if(strTrim(rentBeginDate.val()) == ""){
				alert('租赁开始日期必须填写');
				rentBeginDate.val("").focus();
				return false;
			}
			
			var rentEndDate = $("#rentEndDate");
			if(strTrim(rentEndDate.val()) == ""){
				alert('租赁结束日期必须填写');
				rentEndDate.val("").focus();
				return false;
			}
			
			if(rentBeginDate.val() > rentEndDate.val()){
				alert("租赁结束日期不能早于租赁开始日期");
				return false;
			}
		}
	}else if(innerInvType == 'FWFP'){//服务发票

		var linkAddress = $("#linkAddress");
		if(strTrim(linkAddress.val()) == ""){
			alert('发票邮寄地址必须填写');
			linkAddress.val("").focus();
			return false;
		}

		//纳税人识别号特殊处理
		var taxpayerId = $("#taxpayerId");
		var taxpayerIdVal = strTrim(taxpayerId.val());
		if(taxpayerIdVal == ""){
			alert('纳税人识别号必须填写');
			taxpayerId.val("").focus();
			return false;
		}else{
			if(taxpayerIdVal.length != 15 && taxpayerIdVal.length != 18){
				alert('纳税人识别号为15位或18位字符，当前识别号为' + taxpayerIdVal.length + '位');
				return false;
			}
		}
	}else if(innerInvType == 'ZZSPT' || innerInvType == 'ZLHTPT'){ //增值税普通发票或租赁合同普通发票
		var unitAddress = $("#unitAddress");
		if(strTrim(unitAddress.val()) == ""){
			alert('开票单位地址必须填写');
			unitAddress.val("").focus();
			return false;
		}

		var phoneNo = $("#phoneNo");
		if(strTrim(phoneNo.val()) == ""){
			alert('开票单位电话必须填写');
			phoneNo.val("").focus();
			return false;
		}

		//纳税人识别号特殊处理
		var taxpayerId = $("#taxpayerId");
		var taxpayerIdVal = strTrim(taxpayerId.val());
		if(taxpayerIdVal == ""){
			alert('纳税人识别号必须填写');
			taxpayerId.val("").focus();
			return false;
		}else{
			if(taxpayerIdVal.length != 15 && taxpayerIdVal.length != 18){
				alert('纳税人识别号为15位或18位字符，当前识别号为' + taxpayerIdVal.length + '位');
				return false;
			}
		}
		var bank = $("#bank");
		if(strTrim(bank.val()) == ""){
			alert('开户银行必须填写');
			bank.val("").focus();
			return false;
		}

		var bankCount = $("#bankCount");
		if(strTrim(bankCount.val()) == ""){
			alert('银行帐号必须填写');
			bankCount.val("").focus();
			return false;
		}
		
		if(innerInvType == 'ZLHTPT'){//租赁合同普通发票
			var rentBeginDate = $("#rentBeginDate");
			if(strTrim(rentBeginDate.val()) == ""){
				alert('租赁开始日期必须填写');
				rentBeginDate.val("").focus();
				return false;
			}
			
			var rentEndDate = $("#rentEndDate");
			if(strTrim(rentEndDate.val()) == ""){
				alert('租赁结束日期必须填写');
				rentEndDate.val("").focus();
				return false;
			}
			
			if(rentBeginDate.val() > rentEndDate.val()){
				alert("租赁结束日期不能早于租赁开始日期");
				return false;
			}
		}
	}else{
		var unitAddress = $("#unitAddress");
		if(strTrim(unitAddress.val()) == ""){
			alert('开票单位地址必须填写');
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
			alert('申请明细中的货品名称/服务项目不能存在空项');
			$("#invoiceEquName" + i).val("");
			return false;
		}
		if(strTrim($("#amount" + i).val()) == "" || $("#amount" + i).val()*1 == "" ){
			alert('申请明细中存在数量为空的行');
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
			alert('申请明细中存在总金额为0的行');
			return false;
		}
		if($("#psTyle" + i).val() == "" ){
			alert('申请明细中的产品/服务类型不能存在空项');
			return false;
		}

		thisInvoiceMoney = accAdd(thisInvoiceMoney,rowMoney,2);
	}

	//缓存单据金额对象
	var invoiceMoneyObj = $('#invoiceMoney');

	if(thisInvoiceMoney != invoiceMoneyObj.val()*1){
		alert('单据申请金额不等于申请明细总额');
		return false;
	}

	if(invoiceMoneyObj.val()*1 == 0 || invoiceMoneyObj.val() == ""){
		alert('申请金额不能为0或者空');
		return false;
	}

	if($("#contAmount").length > 0){
		if(objType != ""){
			if(invoiceMoneyObj.val() *1 > $("#canApply").val()*1){
				alert('申请金额('+ invoiceMoneyObj.val() +')大于合同剩余可申请金额(' + $("#canApply").val() + ')');
				return false;
			}
		}
	}

	//邮寄部分表单验证
	var isMail = $('input[name="invoiceapply[isMail]"]:checked').val();
	if(isMail == '1'){
		if($("#expressCompany").val() == ""){//快递公司
			alert('如果需要邮寄发票，请选择一个快递公司');
			return false;
		}

		if($("#postalCode").val() == ""){//邮政编码
			alert('如果需要邮寄发票，请输入邮寄地址的邮政编码');
			$("#postalCode").focus();
			return false;
		}
	}else{
		$("#expressCompany").attr('disabled',false).val('');
		$("#expressCompanyId").attr('disabled',false).val('');
		$("#postalCode").attr('disabled',false).val('');
	}

    // 币别
    if($("#currency").val() == ""){
        alert('币别不能为空');
        $("#currency").focus();
        return false;
    }
	
	//盖章部分验证
	var isNeedStamp = $('input[name="invoiceapply[isNeedStamp]"]:checked').val();
	if(isNeedStamp == '1'){
		if($("#stampType").val() == ""){//快递公司
			alert('如果需要盖章，请填写盖章类型');
			return false;
		}
	}else{
		$("#stampType").attr('disabled',false).val('');
	}

//	countFun();

	//提交时，弹出对相关字段再次核对确认的小窗口的功能 ID2245 2016-11-30
	if(confirm('请注意，表单中有部分为系统自动带出信息。\n可以点击【取消】再次确认信息，或点【确定】继续提交表单！')){
		$('#getLastInfoUrl').val('ok');
	}

	if($('#getLastInfoUrl').val() != 'ok'){
		return false;
	}
	$("input[type='submit']").attr('disabled',true);

	return true;
}

//是否要邮寄
function changeIsMail(thisVal){
	$("#expressCompany").yxcombogrid_logistics('remove');

	if(thisVal == 1){
		$("#expressCompany").attr('disabled',false);
		$("#expressCompanyId").attr('disabled',false);
		$("#postalCode").attr('disabled',false);
		$("#mailInfo").show();
		//快递公司渲染
		initExpressCompany();
	}else{
		$("#expressCompany").attr('disabled',true);
		$("#expressCompanyId").attr('disabled',true);
		$("#postalCode").attr('disabled',true);
		$("#mailInfo").hide();
	}
}

//是否盖章
function changeIsStamp(t){
	if(t == 1){
		//附件盖章验证
		if($("#uploadfileList").html() == "" || $("#uploadfileList").html() == "暂无任何附件"){
			alert('申请盖章前需要上传需要盖章的附件!');
			$("#isNeedStampNo").attr("checked",true);
			return false;
		}
		
		//防止重复数理化下拉表格
		if($("#stampType").yxcombogrid_stampconfig('getIsRender') == true) return false;
		//盖章类型渲染
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
		
		//盖章类型渲染
		var stampTypeObj = $("#stampType");
		stampTypeObj.yxcombogrid_stampconfig('remove');
		stampTypeObj.val('');
		$("#stampType").attr('disable',true);
	}
}

//实例化邮寄公司
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

/**********************动态添加列表*************************/
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
    oTL14.innerHTML="<img src='images/closeDiv.gif' onclick='mydel(this,\""+tablelist.id+"\")' id='deteleRow" + mycount + "' title='删除行'/>";

    document.getElementById(countNumP).value = document.getElementById(countNumP).value*1 + 1 ;
    addDataToSelect(invoiceTypeArr, 'psTyle'+mycount);
    //绑定统计事件
    $("#amount" + mycount).bind("blur",function(){
		countDetail(this);
    });
    createFormatOnClick('softMoney'+mycount);
    //绑定统计事件
    $("#softMoney" + mycount + "_v").bind("blur",function(){
		countDetail(this);
    });
    createFormatOnClick('hardMoney'+mycount);
    //绑定统计事件
    $("#hardMoney" + mycount + "_v").bind("blur",function(){
		countDetail(this);
    });
    createFormatOnClick('repairMoney'+mycount);
    //绑定统计事件
    $("#repairMoney" + mycount + "_v").bind("blur",function(){
		countDetail(this);
    });
    createFormatOnClick('serviceMoney'+mycount);
    //绑定统计事件
    $("#serviceMoney" + mycount + "_v").bind("blur",function(){
		countDetail(this);
    });
    createFormatOnClick('equRentalMoney'+mycount);
    //绑定统计事件
    $("#equRentalMoney" + mycount + "_v").bind("blur",function(){
		countDetail(this);
    });
    createFormatOnClick('spaceRentalMoney'+mycount);
    //绑定统计事件
    $("#spaceRentalMoney" + mycount + "_v").bind("blur",function(){
		countDetail(this);
    });
    createFormatOnClick('otherMoney'+mycount);
    //绑定统计事件
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
			// 快速搜索
			searchitems : [{
				display : '名称',
				name : 'dataName'
			}]
		}
	});
}

//添加扣款信息
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
    oTL3.innerHTML="<img src='images/closeDiv.gif' onclick='mydel(this,\""+tablelist.id+"\")' id='deteleRow" + mycount + "' title='删除行'/>";
	document.getElementById(countNumP).value = document.getElementById(countNumP).value*1 + 1 ;
	addDataToSelect(invoiceTypeArr, 'psTyle'+mycount);
	 //绑定统计事件
    $("#grade" + mycount ).bind("blur",function(){
		countDetail(this);
    });
    createFormatOnClick('grade'+mycount);
    //绑定统计事件
    $("#deduction" + mycount + "_v").bind("blur",function(){
		countDetail(this);
    });
    createFormatOnClick('deduction'+mycount);
	 
}
//判断是其他状态,是的话将输入的值附到productId中
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

// 显示折算后的人民币金额
function showInvoiceLocal(){
    if($("#currency").val() != '人民币'){
        $(".invoiceLocalShow").show();
        $("#currencyTd").attr('colspan',1);
    }
}

//计算租赁天数
function rentDaysCount(){
	var rentBeginDate = $("#rentBeginDate").val();
	var rentEndDate = $("#rentEndDate").val();
	if(rentBeginDate != "" && rentEndDate != ""){
		if(rentBeginDate > rentEndDate){
			alert("租赁结束日期不能早于租赁开始日期");
		}else{
			$("#rentDays").val(DateDiff(rentBeginDate,rentEndDate) + 1);
		}
	}
}