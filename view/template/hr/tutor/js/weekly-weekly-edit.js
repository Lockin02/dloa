$(document).ready(function() {

	//�ʼ�������
	if($("#TO_ID").length!=0){
		$("#TO_NAME").yxselect_user({
			mode : 'check',
			hiddenId : 'TO_ID',
			formCode : 'tutor'
		});
	}
	$( 'textarea.editor' ).ckeditor();
	//�ʼ�������
	if($("#ADDIDS").length!=0){
		$("#ADDNAMES").yxselect_user({
			mode : 'check',
			hiddenId : 'ADDIDS',
			formCode : 'tutor'
		});
	}

   /**
	 * ��֤��Ϣ
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

 	   //����
function toSubmit(){
	document.getElementById('form1').action = "?model=hr_tutor_weekly&action=editWeekly&addType=submit";
}
