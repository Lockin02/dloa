$(function() {
	/**
	 * 调出仓库combogrid
	 */
	$("#exportStockName").yxcombogrid_stockinfo( {
		hiddenId : 'exportStockId',
		nameCol : 'stockName',
		gridOptions : {
			showcheckbox : false,
			event : {
				'row_dblclick' : function(e, row, data) {
					$("#inStockCode").val(data.stockCode);
					var itemscount = $('#itemscount').val();
						for(var i=0;i<=itemscount;i++){
							$("#exportStockId"+i).val(data.id);
							$("#exportStockCode"+i).val(data.stockCode);
							$("#exportStockName"+i).val(data.stockName);
						}
				}
			}
		}
	});
	/**
	 * 调入仓库combogrid
	 */
	$("#importStockName").yxcombogrid_stockinfo( {
		hiddenId : 'importStockId',
		nameCol : 'stockName',
		gridOptions : {
			showcheckbox : false,
			event : {
				'row_dblclick' : function(e, row, data) {
					$("#inStockCode").val(data.stockCode);
					var itemscount = $('#itemscount').val();
						for(var i=0;i<=itemscount;i++){
							$("#importStockId"+i).val(data.id);
							$("#importStockCode"+i).val(data.stockCode);
							$("#importStockName"+i).val(data.stockName);
						}
				}
			}
		}
	});

});