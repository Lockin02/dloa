$(function() {	
	//销售仓库combogrid
	$("#salesStockName").yxcombogrid_stockinfo( {
		hiddenId : 'salesStockId',
		nameCol : 'stockName',
		gridOptions : {
			showcheckbox : false,
			event : {
				'row_dblclick' : function(e, row, data) {
					$("#salesStockCode").val(data.stockCode);
				}
			}
		}
	});
	
	//包装物仓库combogrid
	$("#packingStockName").yxcombogrid_stockinfo( {
		hiddenId : 'packingStockId',
		nameCol : 'stockName',
		gridOptions : {
			showcheckbox : false,
			event : {
				'row_dblclick' : function(e, row, data) {
					$("#packingStockCode").val(data.stockCode);
				}
			}
		}
	});
	
	//旧设备仓库combogrid
	$("#outStockName").yxcombogrid_stockinfo( {
		hiddenId : 'outStockId',
		nameCol : 'stockName',
		gridOptions : {
			showcheckbox : false,
			event : {
				'row_dblclick' : function(e, row, data) {
					$("#outStockCode").val(data.stockCode);
				}
			}
		}
	});
	
	//旧设备仓库combogrid
	$("#borrowStockName").yxcombogrid_stockinfo( {
		hiddenId : 'borrowStockId',
		nameCol : 'stockName',
		gridOptions : {
			showcheckbox : false,
			event : {
				'row_dblclick' : function(e, row, data) {
					$("#borrowStockCode").val(data.stockCode);
				}
			}
		}
	});


});