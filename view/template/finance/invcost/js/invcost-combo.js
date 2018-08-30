$(function() {
	// π©”¶…Ã
	$("#supplierName").yxcombogrid_supplier({
		hiddenId : 'supplierId',
		gridOptions : {
			showcheckbox : false,
			event : {
				'row_dblclick' : function(e, row, data) {
					$("#adress").val(data.address);
				}
			}
		}
	});
});