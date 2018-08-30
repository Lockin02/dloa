function checkform(){
	if($("#supplierName").val() == ""){
		alert('请选择供应商');
		return false;
	}
	if($("#supplierId").val() == ""){
		alert('请从下拉表格中对供应商进行选择,若没有找到对应供应商,请联系相关负责人进行添加');
		return false;
	}

	if($("#remark").val() == ""){
		alert('请填写款项用途');
		return false;
	}

	var coutNumb = $("#coutNumb").val();
	for(var i = 1;i<=coutNumb;i++){
		if($("#money" + i).val() == "" || $("#money" + i).val()*1 == 0){
			alert("申请金额不能为0或者空");
			return false;
		}
		if($("#objType" + i).length > 0){
			if($("#objType" + i).val() != "" && $("#objId" + i).val() == ""){
				alert("请选择对应的单据");
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
		alert('申请金额不能超过采购订单金额上限');
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