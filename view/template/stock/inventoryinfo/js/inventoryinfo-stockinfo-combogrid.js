$(function() {
			$("#stockCode").yxcombogrid_stockinfo({
						hiddenId : 'stockId',
						gridOptions : {
							showcheckbox : false,
							event : {
								'row_dblclick' : function(e, row, data) {
									$("#stockName").val(data.stockName);
									$("#productId").val("");
									$("#productCode").val("");
									$("#productName").val("");
								}
							}
						}
					});
		});