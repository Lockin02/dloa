
$(function() {
	// π©”¶…Ã
	$("#suppName").yxcombogrid_supplier({
		hiddenId : 'suppId',
		height : 400,
		width : 600,
		isShowButton : false,
		gridOptions : {
			showcheckbox : false
		}
	});

	$("#productNumb").yxcombogrid_product({
		hiddenId : 'productId',
		height : 400,
		width : 700,
		gridOptions : {
			showcheckbox : false,
			event : {
				'row_dblclick' : function(e, row, data) {
					$("#productName").val(data.productName);
				}
			}
		}
	});

	$("#productName").yxcombogrid_product({
		hiddenId : 'productId',
    	nameCol:'productName',
		height : 400,
		width : 700,
		gridOptions : {
			showcheckbox : false,
			event : {
				'row_dblclick' : function(e, row, data) {
					$("#productNumb").val(data.productCode);
				}
			}
		}
	});

	$("#sendName").yxselect_user({
				hiddenId : 'sendUserId'
			});
});