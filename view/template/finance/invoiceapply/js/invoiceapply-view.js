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

    // �������ȱ�������ң�����Ҫ��ʾ����������ҽ��
    showInvoiceLocal();

    // �ж�����ۿ�ֲ��ǿյģ�����ʾ����
    if($("#emptyDeduct").length == 0){
        $("#deductTable").show();
    }
});

/**
 * ��Ʊ��ʷ
 * @param thisVal
 * 1.��Ʊ��ʷ
 * 2.������Ϣ
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