$(function(){
	if($('#objType').val() != "" ){
		$('#showContract').show();
	}

	if($('#objType').val() != "" ){
		$('#showContract').show();
	}
	
	if($("#hideBtn").val()==1){
		$("#btn").hide();
	}

    // 如果申请比别不是人民币，则需要显示折算后的人民币金额
    showInvoiceLocal();

    // 判断如果扣款部分不是空的，就显示出来
    if($("#emptyDeduct").length == 0){
        $("#deductTable").show();
    }
});

/**
 * 开票历史
 * @param thisVal
 * 1.开票历史
 * 2.单据信息
 */
function clickFun(thisVal){
	var url = "";
	if(thisVal == 1){
		url = '?model=finance_invoice_invoice&action=invoiceHistory'
            + '&obj[objId]=' + $("#objId").val()
            + '&obj[objCode]=' + $("#objCode").val()
            + '&obj[objType]=' + $("#objType").val()
            + '&obj[skey]=' + $("#skey").val()
		;
	}else{
		url = '?model=finance_invoiceapply_invoiceapply&action=toViewObj'
            + '&invoiceapply[objId]=' + $("#objId").val()
            + '&invoiceapply[objCode]=' + $("#objCode").val()
            + '&invoiceapply[objType]=' + $("#objType").val()
            + '&invoiceapply[skey]=' + $("#skey").val()
		;
	}
	showModalWin(url,1);
}