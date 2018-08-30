$(document).ready(function() {

	//邮件发送人
	if($("#TO_ID").length!=0){
		$("#TO_NAME").yxselect_user({
			mode : 'check',
			hiddenId : 'TO_ID',
			formCode : 'tutor'
		});
	}
	$( 'textarea.editor' ).ckeditor();
	//邮件抄送人
	if($("#ADDIDS").length!=0){
		$("#ADDNAMES").yxselect_user({
			mode : 'check',
			hiddenId : 'ADDIDS',
			formCode : 'tutor'
		});
	}

   /**
	 * 验证信息
	 */
	validate({
		"lastweekSummary" : {
			required : true
		}
	},{
		"nextweekSummary" : {
			required : true
		}
	});

})

 	   //保存
function toSubmit(){
	document.getElementById('form1').action = "?model=hr_tutor_weekly&action=editWeekly&addType=submit";
}
