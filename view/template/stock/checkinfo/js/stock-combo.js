$(function() {
	// 单选客户
	$("#stockName").yxcombogrid_stock({
		hiddenId : 'stockId',
		gridOptions : {
			showcheckbox : false,
			event : {
				'row_dblclick' : function(e, row, data) {
					$("#stockCode").val(data.stockCode);
					$('#itemtable input').val('');
					$('#productslist input').val('');
				}
			}
		}
	});

});

/**
 *
 * @param {} mycount
 * 渲染联系人下拉列表
 *
 */
	function reload( product ){
		var getGrid = function() {
			return $("#" + product)
					.yxcombogrid_inventory("getGrid");
		}
		var getGridOptions = function() {
			return $("#" + product)
					.yxcombogrid_inventory("getGridOptions");
		}
		if( !$('#stockId').val() ){
		}else{
			if (getGrid().reload) {
				getGridOptions().param = {
					stockId : $('#stockId').val()
				};
				getGrid().reload();
			} else {
				getGridOptions().param = {
					stockId : $('#stockId').val()
				}
			}
		}
	}
