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
     		alert("��ѡ��ͬ����֤�ȼ�");
     		return false;
     	}else{
     		return true;
     	}
     }