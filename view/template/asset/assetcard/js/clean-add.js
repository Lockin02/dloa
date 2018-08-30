/*
 * 审核确认
 */
function confirmAudit() {
	if (confirm("你确定要提交审核吗?")) {
		$("#form1").attr("action",
				"?model=asset_assetcard_clean&action=add&actType=audit");
		$("#form1").submit();

	} else {
		return false;
	}
}

$(function(){
	$('#changeWay').val('清理--系统')
})