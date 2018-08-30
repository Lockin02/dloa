function checkForm(){
	if($("#objId").val() == ''){
		alert('请选择关联项目');
		return false;
	}

	if($("#salesman").val() == ''){
		alert('请选择销售负责人');
		return false;
	}

	if($("#trainDate").val() == ''){
		alert('请选择交流时间');
		return false;
	}

	if($("#customerId").val() == ''){
		alert('请选择客户');
		return false;
	}

	if($("#trainAddress").val() == ''){
		alert('请填写交流地址');
		return false;
	}

	if($("#cusLinkman").val() == ''){
		alert('请选择客户联系人');
		return false;
	}

	if($("#cusLinkPhone").val() == ''){
		alert('请填写客户联系电话');
		return false;
	}
}

