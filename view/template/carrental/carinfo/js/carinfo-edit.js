$(document).ready(function() {
	setSelect("isSign");

	/**
	 * 验证信息
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