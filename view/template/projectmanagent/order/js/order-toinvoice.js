$(function(){
	$("#contractName").yxcombogrid_contract({
		hiddenId :  'contractId',
		gridOptions : {
			showcheckbox : false,
			param : { 'equtemporaryNo' : $('#orderCode').val() , "exaStatus" : "Íê³É"},
			event : {
				'row_dblclick' : function(e, row, data){
					$('#contractCode').val(data.contNumber);
				}
			}
		}
	});
});

function orderSend(){
	var objId = $('#orderId').val();
	var objName = $('#orderName').val();
	var objCode = $('#orderCode').val();

	var contractStr = "";
	var contractId = $('#contractId').val();
	if(objId != ""){
		var contractCode = $('#contractCode').val();
		contractStr = '&invoiceapply[extends][edsId]=' + contractId
			+ '&invoiceapply[extends][edsCode]=' + contractCode
			+ '&invoiceapply[extends][edsType]=KPRK-XSHT';
	}
	location='?model=finance_invoiceapply_invoiceapply&action=toAdd'
		+ '&invoiceapply[objId]=' + objId
		+ '&invoiceapply[objCode]=' + objCode
		+ '&invoiceapply[objName]=' + objName
		+ '&invoiceapply[objType]=KPRK-01'
		+ contractStr
		;


}