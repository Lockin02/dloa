$(function() {
	/**
	 * 主表基本信息收料仓库scombogrid
	 */
	$("#inStockName").yxcombogrid_stockinfo( {
		hiddenId : 'inStockId',
		nameCol : 'stockName',
		gridOptions : {
			showcheckbox : false,
			model : 'stock_stockinfo_stockinfo',
			action : 'pageJson',
			event : {
				'row_dblclick' : function(e, row, data) {
					$("#inStockCode").val(data.stockCode);
					var itemscount = $('#itemscount').val();
						for(var i=0;i<=itemscount;i++){
							$("#inStockId"+i).val(data.id);
							$("#inStockCode"+i).val(data.stockCode);
							$("#inStockName"+i).val(data.stockName);
						}
				}
			}
		}
	});
	

});