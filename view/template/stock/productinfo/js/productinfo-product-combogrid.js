$(function() {
			$("#changeProductCode").yxcombogrid_product({
						hiddenId : 'changeProductId',
						event : {
							'clear' : function(e, row, data) {
								$("#changeProductName").val("");
							}
						},
						gridOptions : {
							showcheckbox : false,
							event : {
								'row_dblclick' : function(e, row, data) {
									$("#changeProductName")
											.val(data.productName);
								}
							}
						}
					});
			$("#changeProductName").yxcombogrid_product({
						nameCol:'productName',
						hiddenId : 'changeProductId',
						event : {
							'clear' : function(e, row, data) {
								$("#changeProductCode").val("");
							}
						},
						gridOptions : {
							showcheckbox : false,
							event : {
								'row_dblclick' : function(e, row, data) {
									$("#changeProductCode")
											.val(data.productCode);
								}
							}
						}
					});
		});