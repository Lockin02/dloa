function checkform(){
	var payFor = $("#payFor").val();

	var innerPayType = $("#payType").find("option:selected").attr("e1");

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
	
	if($("#supplierName").val() == ""){
		alert('请填写供应商名称');
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

	var remark = strTrim($("#remark").val());
	if(remark == ""){
        if(payFor == 'FKLX-03'){
            alert('请填写退款说明');
        }else{
            alert('请填写款项用途');
        }
        return false;
	}else{
		if(remark.length > 10){
            if(payFor == 'FKLX-03'){
                alert('请将退款说明描述信息保持在10个字或10个字以内,当前长度为'+ remark.length +" 个字");
                return false;
            }else{
                alert('请将款项用途描述信息保持在10个字或10个字以内,当前长度为'+ remark.length +" 个字");
                return false;
            }
		}
	}

	//币种
	if($("#currency").val() == ""){
		alert('请填写付款币种');
		return false;
	}

	//汇入地点
	if($("#place").val() == ""){
		alert('请填写汇入地点(省市)');
		return false;
	}

	if(payFor != 'FKLX-03'){
		//款项说明
		if($("#payDesc").val() == ""){
			alert('请填写款项说明');
			return false;
		}
	}

	var coutNumb = $("#coutNumb").val();
	for(var i = 1;i<=coutNumb;i++){
		if($("#money" + i).val() == "" || $("#money" + i).val()*1 == 0){
			alert("申请金额不能为0或者空");
			return false;
		}
	}

    // 租车时才验证
    if ($("#sourceType").val() == 'YFRK-06' && $("#period").val() == ""){
        alert('请填写归属月份');
        return false;
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
        var payFor = $("#payFor").val();
        var sourceType=$("input[name='payablesapply[sourceType]']").val();
        if('FKLX-03'==payFor&&'YFRK-06'==sourceType){
            alert('申请金额不能超过已付款金额上限');
        }else{
            alert('申请金额不能超过源单金额');
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