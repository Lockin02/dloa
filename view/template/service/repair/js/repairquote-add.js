$(document).ready(function() {

			$("#tableDiv").width(document.documentElement.clientWidth - 30);
			// 选择组件
			$("#chargeUserName").yxselect_user({
						hiddenId : 'chargeUserCode',
						mode : 'single'
					});
		})

// 删除
function delItem(obj) {
	if (confirm('确定要删除该行？')) {
		var rowNo = obj.parentNode.parentNode.rowIndex - 2;
		$(obj).parent().parent().hide();
		$(obj).parent().append('<input type="hidden" name="repairquote[items]['
				+ rowNo + '][isDelTag]" value="1" id="isDelTag' + rowNo + '" />');
		reloadItemCount();
	}
}

/**
 * 重新计算清单序列号
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
 * 审核确认
 */
function confirmAudit() {
	if (confirm("你确定要提交审核吗?")) {
			$("#form1")
					.attr("action",
							"?model=service_repair_repairquote&action=add&actType=audit");
			$("#form1").submit();
	} else {
		return false;
	}
}