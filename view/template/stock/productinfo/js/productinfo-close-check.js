function checkClosePro(productId) {
	var checkResult = true;
	$.ajax({// �������к�
		type : "POST",
		async : false,
		url : "?model=stock_inventoryinfo_inventoryinfo&action=getSouceInvent",
		dataType : "json",
		data : {
			"productId" : data['id']
		},
		success : function(result) {
			if (result['closeStatus'] == "WLSTATUSGB") {
				var tipStr = "����Ϊ�ر�״̬(" + result['closeReson'] + ")��\n";
				if (result['stock'].length > 0) {
					for ( var i = 0; i < result['stock'].length; i++) {
						tipStr += result['stock'][i]['stockName'] + "ʣ����Ϊ"
								+ result['stock'][i]['actNum'] + "\n";
					}
				} else {
					tipStr += "���û�д����ϣ�"
				}
				alert(tipStr);
				checkResult = false;
			}
		}
	})
	return checkResult;
}
