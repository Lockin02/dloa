$(function() {
			$("#supplierName").yxcombogrid_supplier({
						hiddenId : 'supplierId',
						width : 600,
						isShowButton : false,
						gridOptions : {
							isShowButton : true,
							showcheckbox : false,
							event : {
								'row_dblclick' : function(e, row, data) {
								}
							}
						}
					});
		});