$(document).ready(function() {

	var url = "?model=hr_basicinfo_socialplace&action=checkRepeat";
	if ($("#id").val()) {
		url += "&id=" + $("#id").val();
	}
	$("#socialCity").ajaxCheck({
		url : url,
		alertText : "* �ù�����Ѵ���",
		alertTextOk : "* ����"
	});
	//��֤������
	validate({
//		"socialCity" : {
//			required : true
//		}
	});
   })
