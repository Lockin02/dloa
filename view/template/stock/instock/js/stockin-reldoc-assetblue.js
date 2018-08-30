$(document).ready(function() {
	$.post(
		"?model=asset_require_requireout&action=getItemList",
		{
			requireId: $("#relDocId").val()
		}, function(result) {
			if (result != "") {
				$("#itembody").append(result);
				reloadItemStock();
				formateMoney();
			} else {
				alert("该资产入库单中没有物料信息，请重新选择！")
			}
		});
});