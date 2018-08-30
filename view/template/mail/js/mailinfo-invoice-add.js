$(function() {
	//·¢Æ±
	$("#docCode").yxcombogrid_invoice({
		hiddenId : 'docId',
		gridOptions : {
			param : { 'isMailNo' : '1' },
			showcheckbox : false,
			event : {
				'row_dblclick' : function(e, row, data){
					$("#customerName").val(data.invoiceUnitName);
					$("#customerId").val(data.invoiceUnitId);
				}
			}
		}
	});

	$("#customerName").yxcombogrid_customer({
		hiddenId : 'customerId',
		isShowButton : false,
		gridOptions : {
			showcheckbox : false,
			event : {
				'row_dblclick' : function( e ,row ,data){
					$('#address').val( data.Address );
				}
			}
		}
	});

	$("#logisticsName").yxcombogrid_logistics({
		hiddenId : 'logisticsId',
		gridOptions : {
			showcheckbox : false
		}
	});

	$("#mailMan").yxselect_user({
		hiddenId : 'mailManId'
	});

	$("#salesman").yxselect_user({
		mode : 'check',
		hiddenId : 'salesmanId'
	});
});