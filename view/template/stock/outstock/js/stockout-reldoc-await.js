$(document).ready(function(){
	$.post(
			"?model=projectmanagent_return_return&action=getItemList",
			{
				returnId : $("#relDocId").val()
			}, function(result) {
				if (result != "") {
					$("#itembody").append(result);
					reloadItemStock();
					formateMoney();
				} else {
					alert("该销售退货通知单中没有物料信息，请重新选择！")
				}
			});
})