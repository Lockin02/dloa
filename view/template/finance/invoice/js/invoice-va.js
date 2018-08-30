function checkform(){
	if($('#customerName').val() == ""){
		alert('开票单位需要填写');
		return false;
	}

	if($('#objCode').val() == ""){
		alert('源单编号需要填写');
		return false;
	}

	if($('#invoiceNo').val() == ""){
		alert('发票号码需要填写');
		return false;
	}

    var managerNameObj = $('#managerName');
    if(managerNameObj.length == 1){
        if(managerNameObj.val() == ""){
            alert('请选择主管');
            return false;
        }
    }

    if($('#salesmanId').val() == ""){
        alert('请选择业务员');
        return false;
    }

	if($('#deptId').val() == ""){
		alert('请选择部门');
		return false;
	}

    if($('#invoiceUnitType').val() == ""){
        alert('请选择客户类型');
        return false;
    }

    // 获取选中发票的隐含类型
	var innerInvType = $('#invoiceType').find("option:selected").attr("e2");
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
	}else if(innerInvType == 'ZLHTPT'){//租赁合同普通发票
		var rentBeginDate = $("#rentBeginDate").val();
		var rentEndDate = $("#rentEndDate").val();
		if(rentBeginDate != "" && rentEndDate != ""){
			if(rentBeginDate > rentEndDate){
				alert("租赁结束日期不能早于租赁开始日期");
				return false;
			}
		}
	}
	
	var invoiceContentsArr = [];
	var invoiceEqu ;//开票项目

	var psTypeArr = [];
	var psType ;//产品类型

	var rowsNumber = $("#invnumber").val()*1;
	var rowMoney = 0;
	for(var i = 1;i<= rowsNumber ; i++){
		//如果记录不存在，则继续循环
		if($("#invoiceEquName" + i).length == 0){
			continue;
		}

		invoiceEqu = $("#invoiceEquName" + i).val();
		//循环处理开票项目
		if (invoiceContentsArr.indexOf(invoiceEqu) == -1){
			invoiceContentsArr.push(invoiceEqu);
		}

		psType = $("#psType" + i).find("option:selected").attr("title");
		//循环处理开票项目
		if (psTypeArr.indexOf(psType) == -1){
			psTypeArr.push(psType);
		}

		if(strTrim(invoiceEqu) == "" ){
			alert('申请明细中的货品名称/服务项目不能存在空项');
			$("#invoiceEquName" + i).val("");
			return false;
		}
		if(strTrim($("#amount" + i).val()) == "" || $("#amount" + i).val()*1 == "" ){
			alert('申请明细中存在数量为空的行');
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
			alert('申请明细中存在总金额为0的行');
			return false;
		}
	}

	//设置开票详细
	$("#invoiceContent").val(invoiceContentsArr.toString());

	//设置开票类型
	$("#psType").val(psTypeArr.toString());

	if($('#invoiceMoney').val() == "" || $('#invoiceMoney').val()*1 == 0){
		alert('请输入开票明细');
		return false;
	}

	//核销部分验证
	var cmps = $("#checkTable").yxeditgrid("getCmpByCol", "checkMoney");
	if(cmps.length == 0 && !confirm('没有填写核销记录,确认提交吗？')){
		return false;
	}
	var checkMoneyRs = true;
	if(cmps.length > 0){
		var allCheckMoney = 0;
		cmps.each(function(i,n) {
			if($(this).val() == "" || $(this).val() == 0){
				alert('核销金额不能留空或为0');
				checkMoneyRs = false;
				return false;
			}
			allCheckMoney = accAdd(allCheckMoney,$(this).val(),2);
		});
		if(checkMoneyRs == false){
			return false;
		}
		//过滤掉删除的行
		if(allCheckMoney == 0 || allCheckMoney != rowMoney){
			alert('核销金额不等于开票金额，请重新修改');
			checkMoneyRs = false;
			return false;
		}
		if(checkMoneyRs == false){
			return false;
		}
	}

	return true;
}