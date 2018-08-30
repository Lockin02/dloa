//表单验证方法
function checkform(){
	var payFor = $("#payFor").val();

	if($("#supplierName").val() == ""){
		alert('请选择供应商');
		return false;
	}
	if($("#supplierId").val() == ""){
		alert('请从下拉表格中对供应商进行选择,若没有找到对应供应商,请联系相关负责人进行添加');
		return false;
	}

	innerPayType = $("#payType").find("option:selected").attr("e1");
	if(innerPayType == 1){
		if($("#bank").val() == ""){
			alert('请填写供应商开户银行');
			return false;
		}

		if($("#account").val() == ""){
			alert('请填写收款银行帐号');
			return false;
		}
	}

	if($("#payCondition").val() == ""){
		alert('请填写付款条件');
		return false;
	}

	if($("#payMoney").val()*1 == 0){
		alert('申请金额不能为0');
		return false;
	}

	if($("#deptName").val() == ""){
		alert('请选择申请部门');
		return false;
	}

	if($("#salesman").val() == ""){
		alert('请选择申请人');
		return false;
	}

	//期望付款日期
	if($("#payDate").val() == ""){
		alert('请填写期望付款日期');
		return false;
	}

	//汇入地点
	if($("#place").val() == ""){
		alert('请填写汇入地点(省市)');
		return false;
	}

	//币种
	if($("#currency").val() == ""){
		if(payFor == 'FKLX-03'){
			alert('请填写退款币种');
		}else{
			alert('请填写付款币种');
		}
		return false;
	}

	// 提前付款原因
	if($("#needPayEarlyReason").val() == '1' && $("#payEarlyReason").val() == ''){
		alert('请填写提前付款原因');
		$("#payEarlyReason").focus();
		return false;
	}

	var remark = strTrim($("#remark").val());
	if(remark == ""){
		if(payFor == 'FKLX-03'){
			alert('请填写退款说明');
		}else{
			alert('请填写款项用途');
		}
		return false;
	}else{
		if(remark.length > 10 && payFor != 'FKLX-03'){
			alert('请将款项用途描述信息保持在10个字或10个字以内,当前长度为'+ remark.length +" 个字");
			return false;
		}
	}

	if(payFor != 'FKLX-03'){
		//款项说明
		if($("#payDesc").val() == ""){
			alert('请填写款项说明');
			return false;
		}
	}


	//提前付款值初始化
	var isAdvPayObj = $("#isAdvPay");
	if(isAdvPayObj.length != 0){
		if(isAdvPayObj.val() == 1){

			//提前付款原因
			if($("#advPayReason").val() == ""){
				alert('请填写提前原因');
				return false;
			}
		}
	}

	if($("#canApplyAll").length == 1){
		canApplyAll = $("#canApplyAll").val()*1;
		payMoney = $("#payMoney").val()*1;
		if(canApplyAll < payMoney){
			alert('所填单据申请金额为:' + payMoney + ' 不能超出该金额本次最大申请金额:' + canApplyAll );
			return false;
		}
	}
	//防止重复提交
	$("input[type='submit']").attr('disabled',true);

	return true;
}

function checkMax(thisI){
	if($("#objId" + thisI).val() == "" || $("#objId" + thisI).val() == 0 ){
		return false;
	}
	if($("#money"+ thisI).val()*1 > $("#oldMoney"+ thisI).val()*1){
		alert('申请金额已超出可申请金额');
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