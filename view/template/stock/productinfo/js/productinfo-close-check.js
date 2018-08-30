function checkClosePro(productId) {
	var checkResult = true;
	$.ajax({// 缓存序列号
		type : "POST",
		async : false,
		url : "?model=stock_inventoryinfo_inventoryinfo&action=getSouceInvent",
		dataType : "json",
		data : {
			"productId" : data['id']
		},
		success : function(result) {
			if (result['closeStatus'] == "WLSTATUSGB") {
				var tipStr = "物料为关闭状态(" + result['closeReson'] + ")：\n";
				if (result['stock'].length > 0) {
					for ( var i = 0; i < result['stock'].length; i++) {
						tipStr += result['stock'][i]['stockName'] + "剩余库存为"
								+ result['stock'][i]['actNum'] + "\n";
					}
				} else {
					tipStr += "库存没有此物料！"
				}
				alert(tipStr);
				checkResult = false;
			}
		}
	})
	return checkResult;
}
