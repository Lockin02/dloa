$(document).ready(function() {
	validate({
				"gradeChange" : {
					required : true
				},
				"changeReason" : {
					required : true
				}
			});
     })

     function  checkGradeSelect(){
     	var suppGrade=$("#suppGrade").val();
     	var gradeChange=$("#gradeChange").val();
     	if(suppGrade==gradeChange){
     		alert("请选择不同的认证等级");
     		return false;
     	}else{
     		return true;
     	}
     }