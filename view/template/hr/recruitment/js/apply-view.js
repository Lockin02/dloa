$(document).ready(function () {
	if ($("#actType").val() != "") {
		$("#closeBtn").hide();
	}

	///�޸������鿴ҳ��
	$(".new").each(function () {
		if (0 == $(this).text()) {
			$(this).html('');
		}
		if ("" != $(this).text()) {
			$(this).wrap('<span style="font-weight:bold;color:red;">--></span>');
		}
	});
});