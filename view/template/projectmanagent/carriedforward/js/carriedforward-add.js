$(function(){
})

//��ʼ���豸ѡ��
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

//����֤
function checkform(){
	if($("#saleId").val() == ""){
		alert('��ѡ��һ����ͬ');
		return false;
	}
	if($("#outStockId").val() == ""){
		alert('��ѡ��һ�����۳��ⵥ');
		return false;
	}

	var isCarried = 0;
	//��֤���Ƿ��ѽ�ת
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
		alert('�ѽ�ת���ݣ����ܽ����ٴν�ת��');
		$("#outStockId").val('');
		$("#outStockCode").val('');
		return false;
	}


	if($("#thisDate").val() == ""){
		alert('�������ڱ�����д');
		return false;
	}
}