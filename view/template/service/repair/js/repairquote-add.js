$(document).ready(function() {

			$("#tableDiv").width(document.documentElement.clientWidth - 30);
			// ѡ�����
			$("#chargeUserName").yxselect_user({
						hiddenId : 'chargeUserCode',
						mode : 'single'
					});
		})

// ɾ��
function delItem(obj) {
	if (confirm('ȷ��Ҫɾ�����У�')) {
		var rowNo = obj.parentNode.parentNode.rowIndex - 2;
		$(obj).parent().parent().hide();
		$(obj).parent().append('<input type="hidden" name="repairquote[items]['
				+ rowNo + '][isDelTag]" value="1" id="isDelTag' + rowNo + '" />');
		reloadItemCount();
	}
}

/**
 * ���¼����嵥���к�
 */
function reloadItemCount() {
	var i = 1;
	$("#itembody").children("tr").each(function() {
				if ($(this).css("display") != "none") {
					$(this).children("td").eq(1).text(i);
					i++;

				}
		})
}

/**
 * ���ȷ��
 */
function confirmAudit() {
	if (confirm("��ȷ��Ҫ�ύ�����?")) {
			$("#form1")
					.attr("action",
							"?model=service_repair_repairquote&action=add&actType=audit");
			$("#form1").submit();
	} else {
		return false;
	}
}