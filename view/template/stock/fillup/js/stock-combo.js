$(function() {
	// ²Ö¿â
	$("#stockName").yxcombogrid_stock({
				hiddenId : 'stockId',
				gridOptions : {
					showcheckbox : false,
					event : {
						'row_dblclick' : function(e, row, data) {
							$("#stockCode").val(data.stockCode);
							$("#stockId").val(data.id);
						},
						'row_click' : function() {
							// alert(123)
						},
						'row_rclick' : function() {
							// alert(222)
						}
					}
				}
			});

});