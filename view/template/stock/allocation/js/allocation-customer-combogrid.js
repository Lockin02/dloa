$(function() {
			$("#customerName").yxcombogrid_customer({
						hiddenId : 'customerId',
						isShowButton : false,
						gridOptions : {
							showcheckbox : false,
							event : {
								'row_dblclick' : function(e, row, data) {
								}
							}
						}
					});
		});