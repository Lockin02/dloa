$(document).ready(function() {

	/**
	 * ��֤��Ϣ
	 */
	validate({
		"carType" : {
			required : true
		},
		"brand" : {
			required : true
		},
		"displacement" : {
			required : true
		},
		"owners" : {
			required : true
		},
		"driver" : {
			required : true
		},
		"linkPhone" : {
			required : true
		}
	});

	var url = "?model=carrental_carinfo_carinfo&action=checkRepeat";
	$("#carNo").ajaxCheck({
		url : url,
		alertText : "* �ú����Ѵ���",
		alertTextOk : "* �ú������"
	});
})