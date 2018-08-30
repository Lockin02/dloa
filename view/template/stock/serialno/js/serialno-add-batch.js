$(function() {
	addItem();
	/**
	 * 仓库scombogrid
	 */
	$("#stockName").yxcombogrid_stockinfo( {
		hiddenId : 'stockId',
		nameCol : 'stockName',
		gridOptions : {
			showcheckbox : false,
			event : {
				'row_dblclick' : function(e, row, data) {
					$("#stockCode").val(data.stockCode);
					
				}
			}
		}
	});
	
	/**
	 * 物料combogrid
	 */
	$("#productName").yxcombogrid_product( {
		hiddenId : 'productId',
		nameCol : 'productName',
		gridOptions : {
			showcheckbox : false,
			event : {
				'row_dblclick' : function(e, row, data) {
					$("#productCode").val(data.productCode);
					
				}
			}
		}
	});
	
	/**
	 * 物料combogrid
	 */
	$("#productCode").yxcombogrid_product( {
		hiddenId : 'productId',
		nameCol : 'productCode',
		gridOptions : {
			showcheckbox : false,
			event : {
				'row_dblclick' : function(e, row, data) {
					$("#productName").val(data.productName);
					
				}
			}
		}
	});
});



function addItem(){
	var itemscount=$("#itemscount").val();
	var trStr='<tr><td>'+(itemscount*1+1)+'</td>' +
		'<td><input type="text" class="txt" name="serialno[items][' + itemscount+ '][sequence]" id="sequence' + itemscount+ '" /></td>' +
		'<td><input type="text" class="txt" name="serialno[items][' + itemscount+ '][remark]" id="remark' + itemscount+ '" /></td></tr>';
		$("#itemscount").val(itemscount*1+1);
		$("#itembody").append(trStr);
}