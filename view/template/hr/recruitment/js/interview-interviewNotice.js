   $(document).ready(function() {
		$( 'textarea.editor' ).ckeditor();

		//������Աѡ��
		$("#toccmail").yxselect_user({
			hiddenId : 'toccmailId',
			mode : 'check',
			formCode : 'intUseManager'

		})
	    //������Աѡ��
		$("#tobccmail").yxselect_user({
			hiddenId : 'tobccmailId',
			mode : 'check',
			formCode : 'intUseManager'
		})
//		//����ְλ�����
//		var jobUrl = $("#jobUrl").val();
//		var jobHtml = "<a href='http://www.baidu.com'>baidu</a>"
//		$("#remark").val(jobHtml);
	});


function addmail(name){
	if(name == 'ccmail'){
	  var nameCol = "����";
	}else{
	  var nameCol = "����";
	}
   var temp = document.getElementById(name);
	if (temp.style.display == ''){
	   temp.style.display = "none";
	   $("#to"+name).val("");

	   $("#btn"+name).val("���"+nameCol);
	}else if (temp.style.display == "none"){
		temp.style.display = '';
	    $("#btn"+name).val("ɾ��"+nameCol);
	}
}