/*
 * ���ȷ��
 */
function confirmAudit() {
	if (confirm("��ȷ��Ҫ�ύ�����?")) {
		$("#form1").attr("action",
				"?model=asset_assetcard_clean&action=add&actType=audit");
		$("#form1").submit();

	} else {
		return false;
	}
}

$(function(){
	$('#changeWay').val('����--ϵͳ')
})