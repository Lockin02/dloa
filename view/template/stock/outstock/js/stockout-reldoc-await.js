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
					alert("�������˻�֪ͨ����û��������Ϣ��������ѡ��")
				}
			});
})