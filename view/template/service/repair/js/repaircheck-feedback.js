$(function() {
	if($("#docStatus").val() == 'DHCJ'){
		$("#beforeInfo").show();
	}else{
		$("#beforeInfo").show();
	}
});
/*
 * �ύȷ��
 */
function confirmAudit() {
	if (confirm("��ȷ��Ҫ�ύ������?")) {
		$("#form1").attr("action",
				"?model=service_repair_repaircheck&action=feedback&actType=submit");
		$("#form1").submit();
	} else {
		return false;
	}
}