$(function(){
})

//初始化设备选择
function toStockout(){
	$("#outStockCode").yxcombogrid_stockout('remove');
	$("#outStockCode").yxcombogrid_stockout({
		hiddenId : 'outStockId',
		gridOptions : {
			param : {'docStatus' : 'YSH','docType' : $("#outStockType").val() , 'contractId' : $("#saleId").val()},
			showcheckbox :false
		},
	});
}

//表单验证
function checkform(){
	if($("#saleId").val() == ""){
		alert('请选择一个合同');
		return false;
	}
	if($("#outStockId").val() == ""){
		alert('请选择一个销售出库单');
		return false;
	}

	var isCarried = 0;
	//验证表单是否已结转
	$.ajax({
		type : "POST",
		url : "?model=projectmanagent_carriedforward_carriedforward&action=isCarried",
	    data: {"outStockId" : $("#outStockId").val()},
	    async: false,
		success : function(msg) {
			if (msg == 1) {
				isCarried = 1;
			}
		}
	});

	if(isCarried == 1){
		alert('已结转单据，不能进行再次结转！');
		$("#outStockId").val('');
		$("#outStockCode").val('');
		return false;
	}


	if($("#thisDate").val() == ""){
		alert('勾稽日期必须填写');
		return false;
	}
}