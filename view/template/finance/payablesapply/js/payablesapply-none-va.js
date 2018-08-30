function checkform(){
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

	if($("#deptName").val() == ""){
		alert('请选择申请部门');
		return false;
	}

	if($("#salesman").val() == ""){
		alert('请选择申请人');
		return false;
	}

	if($("#feeDeptName").val() == ""){
		alert('请选择费用归属部门');
		return false;
	}
	if(strTrim($("#formDate").val()) == ""){
		alert('请填写单据日期');
		return false;
	}

	if(strTrim($("#payDate").val()) == ""){
		alert('请填写期望付款日期');
		return false;
	}

	if($("#payMoney").val() == "" || $("#payMoney").val()*1 == 0){
		alert('付款金额不能为0');
		return false;
	}

	var remark = strTrim($("#remark").val());
	if(remark == ""){
		alert('请填写款项用途');
		return false;
	}else{
		if(remark.length > 10){
			alert('请将款项用途描述信息保持在10个字或10个字以内,当前长度为'+ remark.length +" 个字");
			return false;
		}
	}
	if($("#deptIsNeedProvince").val() ==1 && $("#provinceId").val() =="" ){
		alert('请选择省份!');
		return false;
	}
	//防止重复提交
	$("input[type='submit']").attr('disabled',true);

	return true;
}