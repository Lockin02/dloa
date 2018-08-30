$(function() {
	if($("#docStatus").val() == 'DHCJ'){
		$("#beforeInfo").show();
	}else{
		$("#beforeInfo").show();
	}
});
/*
 * 提交确认
 */
function confirmAudit() {
	if (confirm("你确定要提交反馈吗?")) {
		$("#form1").attr("action",
				"?model=service_repair_repaircheck&action=feedback&actType=submit");
		$("#form1").submit();
	} else {
		return false;
	}
}