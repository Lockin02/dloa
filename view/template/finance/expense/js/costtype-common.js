$(document).ready(function() {
	validate({
		"CostTypeName" : {
			required : true
		}
	});

	//显示天数
	setSelect('showDays');
	//允许替票
	setSelect('isReplace');
	//录入清单
	setSelect('isEqu');
	//是否补贴
	setSelect('isSubsidy');
	//设值允许替票
	canReplace();	
	//是否关闭 chenrf
	setSelect('isClose');
});

//设置默认发票类型名称
function setInvoiceTypeName(thisVal){
	var invoiceTypeName = $("#invoiceType").find("option:selected").text();
	$("#invoiceTypeName").val(invoiceTypeName);
}

//设值允许替票
function canReplace(){
	//默认允许替票时的操作
	var isReplace = $("#isReplace").val();
	if(isReplace == '0'){
		$("#invoiceTypeInfo").attr('class','blue');
	}else{
		if($("#isSubsidy").val() !="1" ){
			$("#invoiceTypeInfo").attr('class','none');
		}
	}
}

//设置是否补贴
function setIsSubsidy(){
	//默认允许替票时的操作
	var isSubsidy = $("#isSubsidy").val();
	if(isSubsidy == '1'){
		$("#invoiceTypeInfo").attr('class','blue');
	}else{
		if($("#isReplace").val() !="0" ){
			$("#invoiceTypeInfo").attr('class','none');
		}
	}
}

//表单验证
function checkForm(){
	//默认允许替票时的操作
	var isReplace = $("#isReplace").val();
	if(isReplace == "0"){//如果不允许替票时，必须选一种发票
		if($("#invoiceType").val() == ""){
			alert('不允许替票时，必须选择默认发票类型');
			return false;
		}
	}

	//默认是否补贴时的操作
	var isSubsidy = $("#isSubsidy").val();
	if(isSubsidy == "1"){//如果是补贴时，必须选一种发票
		if($("#invoiceType").val() == ""){
			alert('费用类型设置成补贴时，请将设置一个相应的发票类型');
			return false;
		}
	}

	return true;
}
//设置是否关闭费用类型
function selClose(){
	var isClose=$("#isClose").val();
	if(isClose=='1'){
		alert('如果为父节点，则其子节点不可用!');
	}
	
}