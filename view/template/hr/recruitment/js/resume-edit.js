function Calculate(obj){
	$("#workSeniority").val("");
	var graduateDate = obj.value;
	var dates = graduateDate.split("-");
	var graduateYear = parseInt(dates[0]);
	var graduateMon = parseInt(dates[1]);
	var nowDate = new Date();
	var nowYear = nowDate.getFullYear();
	var nowMon = nowDate.getMonth() + 1;

	//����ʱ��õ���������
	var s = parseInt(((nowYear - graduateYear) * 12 + nowMon - graduateMon)/12);
	if(s < 1){
		$("#workSeniority").val("0");
	}else {
		$("#workSeniority").val(s);
	}
}

$(document).ready(function() {
     $("#sourceB").yxcombogrid_resumeSource({
		hiddenId : 'sourceB',
		isShowButton : false,
		gridOptions : {
			showcheckbox : false
		}
	});
	  //����״��
		$('select[name="resume[marital]"] option').each(function() {
			if( $(this).val() == $("#maritalS").val() ){
				$(this).attr("selected","selected'");
			}
		});
//       //ѧ��
//		$('select[name="resume[education]"] option').each(function() {
//			if( $(this).val() == $("#educationS").val() ){
//				$(this).attr("selected","selected'");
//			}
//		});

})

$(function(){

	/**
	 * ��֤��Ϣ
	 */
	validate({
		"applicantName" : {
			required : true
		},
		"phone" : {
			required : true,
			custom: ['mobilephone']
		},
		"email" : {
			required : true,
			custom: ['email']
		},
//		"wishSalary" : {
//			custom: ['onlyNumber']
//		},
		"workSeniority" : {
			required : true
		},
		"educationExp" : {
			required : true
		},
		"education" : {
			required : true
		},
		"post" : {
			required : true
		},
		"workExp" : {
			required : true
		},
		"selfAssessment" : {
			required : true
		},
		"graduateDate" : {
			required : true
		},
		"reserveA" : {
			required : true
		}

	});
})

function sub(){
    var sourceA = $("#sourceA").val();
    var sourceB = $("#sourceB").val();
   if(sourceA == '' && sourceB == ''){
       alert("����дӦƸ����!");
       return false;
   }
      return true;
}