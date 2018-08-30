$(function() {
	$("#stockName").yxcombogrid_stockinfo({
		hiddenId : 'stockId',
		nameCol : 'stockName',
		gridOptions : {
			showcheckbox : false
		}
	});
	$("#productNo").yxcombogrid_product({
		hiddenId : 'productId',
		nameCol : 'productCode',
		gridOptions : {
			showcheckbox : false,
			event : {
				'row_dblclick' : function(e, row, data) {
					$("#productName").val(data.productName);
					$("#productModel").val(data.pattern);
					$("#units").val(data.unitName);

				}
			}
		}
	});
});

function thisChange(thisVal){
	$("#thisMonth").val(thisVal.substr(5,2) * 1);
	$("#thisYear").val(thisVal.substr(0,4));
}