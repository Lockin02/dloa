$(function() {	
	//���۲ֿ�combogrid
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
	
	//��װ��ֿ�combogrid
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
	
	//���豸�ֿ�combogrid
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
	
	//���豸�ֿ�combogrid
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