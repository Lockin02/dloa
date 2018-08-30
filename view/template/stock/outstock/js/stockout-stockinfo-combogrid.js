$(function() {
	/**
	 * 主表基本信息收料仓库scombogrid
	 */
	$("#stockName").yxcombogrid_stockinfo( {
		hiddenId : 'stockId',
		nameCol : 'stockName',
		gridOptions : {
			showcheckbox : false,
			event : {
				'row_dblclick' : function(e, row, data) {
					$("#inStockCode").val(data.stockCode);
					var itemscount = $('#itemscount').val();
						for(var i=0;i<=itemscount;i++){
							$("#stockId"+i).val(data.id);
							$("#stockCode"+i).val(data.stockCode);
							$("#stockName"+i).val(data.stockName);
						}
				}
			}
		}
	});


});