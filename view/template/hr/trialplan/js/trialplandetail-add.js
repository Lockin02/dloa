$(document).ready(function() {
	validate({
		"taskName" : {
			required : true
		},
		"taskScore" : {
			required : true
		},
		"planScoreAll" : {
			required : true
		},
		"planBaseScore" : {
			required : true
		}
	});

	//¸ºÔğÈË
	$("#managerName").yxselect_user({
		hiddenId : 'managerId',
		formCode : 'trialplanManager'
	});
})