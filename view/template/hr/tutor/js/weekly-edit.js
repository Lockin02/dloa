$(document).ready(function() {

	//�ʼ�������
	if($("#ADDIDS").length!=0){
		$("#ADDNAMES").yxselect_user({
			mode : 'check',
			hiddenId : 'ADDIDS',
			formCode : 'tutor'
		});
	}


   /*
	validate({
				"orderNum" : {
					required : true,
					custom : 'onlyNumber'
				}
			});
   */  })

   function sub(){

      var guideIdea = $("#guideIdea").val();
      if(guideIdea == ""){
         alert("����д��ʦָ�����");
         return false;
      }
         return true;
   }