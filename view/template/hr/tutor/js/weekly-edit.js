$(document).ready(function() {

	//邮件抄送人
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
         alert("请填写导师指导意见");
         return false;
      }
         return true;
   }