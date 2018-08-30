function reloadContractCombo() {
	$("#contractCode").yxcombogrid_allcontract({
		hiddenId : 'contractId',
		valueCol : 'id',
		isFocusoutCheck : false,
		isDown : false,
		gridOptions : {
			param : {
				ExaStatus : 'Íê³É'
			},
			showcheckbox : false,
			event : {
				'row_dblclick' : function(e, row, data) {
					$("#customerName").val(data.customerName);
					$("#customerId").val(data.customerId);
					$("#contractName").val(data.contractName);
					$("#contractType").val("oa_contract_contract");
					$("#contractObjCode").val(data.objCode);
				}
			}
		}
	});
}

$(function() {
	reloadContractCombo();

	function clearContractInfo() {
		$("#contractType").val("");
		$("#contractId").val("");
		$("#contractCode").val("");
		$("#contractName").val("");
		$("#contractObjCode").val("");

	}
});