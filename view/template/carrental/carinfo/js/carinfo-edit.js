$(document).ready(function() {
	setSelect("isSign");

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
})