$(document).ready(function() {
	// ��֤��Ϣ
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
		alert('��ʼʱ�䲻�ܴ��ڽ���ʱ��');
		return false;
	}else{
		return true;
	}
}