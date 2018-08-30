$(document).ready(function() {
	// 验证信息
	validate({
		"consultationName" : {
			required : true
		},
		"consultationTel" : {
			required : true,
			custom : ['onlyNumber']
		},
		"workBeginDate" : {
			required : true,
			custom : ['date']
		},
		"workEndDate" : {
			required : true,
			custom : ['date']
		},
		"userPosition" : {
			required : true
		},
		"consultationEmail" : {
			required : true,
			custom : ['email']
		},
		"userCompany" : {
			required : true
		},
		"relationshipName" : {
			required : true
		}
	});


	var opt=$("option");
	for(var i=0;i<opt.length;i++){
		if(opt[i].value==$("#code").val())
			opt[i].selected=true;
	}


  })