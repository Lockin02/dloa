   $(document).ready(function() {
		$( 'textarea.editor' ).ckeditor();

		//抄送人员选择
		$("#toccmail").yxselect_user({
			hiddenId : 'toccmailId',
			mode : 'check',
			formCode : 'intUseManager'

		})
	    //密送人员选择
		$("#tobccmail").yxselect_user({
			hiddenId : 'tobccmailId',
			mode : 'check',
			formCode : 'intUseManager'
		})
//		//插入职位申请表
//		var jobUrl = $("#jobUrl").val();
//		var jobHtml = "<a href='http://www.baidu.com'>baidu</a>"
//		$("#remark").val(jobHtml);
	});


function addmail(name){
	if(name == 'ccmail'){
	  var nameCol = "抄送";
	}else{
	  var nameCol = "密送";
	}
   var temp = document.getElementById(name);
	if (temp.style.display == ''){
	   temp.style.display = "none";
	   $("#to"+name).val("");

	   $("#btn"+name).val("添加"+nameCol);
	}else if (temp.style.display == "none"){
		temp.style.display = '';
	    $("#btn"+name).val("删除"+nameCol);
	}
}