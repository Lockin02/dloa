$(document).ready(function(){
	$.post("?model=asset_require_requireinitem&action=getOutStockDetail",
		{
			requireId : $("#relDocId").val()
		}, function(result) {
			if (result != "") {
				$("#itembody").append(result);
				reloadItemStock();
				formateMoney();
			} else {
				alert("���ʲ����ⵥ��û��������Ϣ��������ѡ��")
			}
	});
})