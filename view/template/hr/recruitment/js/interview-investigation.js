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
//		"workBeginDate" : {
//			required : true,
//			custom : ['date']
//		},
//		"workEndDate" : {
//			required : true,
//			custom : ['date']
//		},
		"userPosition" : {
			required : true
		},
		"userCompany" : {
			required : true
		}
//		"consultationEmail" : {
//			required : true,
//			custom : ['email']
//		}
//		,
//		"relationshipCode" : {
//			required : true
//		}
	});

  })
  function checkSubmit(){
	var workBeginDate=$("#workBeginDate").val();
	var workEndDate=$("#workEndDate").val();
	if(workBeginDate>workEndDate){
		alert('开始时间不能大于结束时间');
		return false;
	}else{
		return true;
	}
}