$(document).ready(function() {

	$("#openerName").yxselect_user({
		hiddenId : 'openerId'
	});

	validate({
		"packageType" : {
			required : true
		},
		"ratesOf" : {
			required : true
		},
		"pinNo" : {
			required : true
		},
		"openDate" : {
			custom : ['date']
		},
		"openerName" : {
			required : true
		}
	});

	var url = "?model=cardsys_cardsinfo_cardsinfo&action=checkRepeat";
	$("#cardNo").ajaxCheck({
		url : url,
		alertText : "* �ú����Ѵ���",
		alertTextOk : "* �ú������"
	});
})
