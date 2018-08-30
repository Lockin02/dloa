$(document).ready(function() {
	validate({
//		"interviewPlace" : {
//			required : true
//		},
//		"interviewDate" : {
//			required : true
//		},
		"deptName" : {
			required : true
		},
		"positionLevel" : {
			required : true
		},
		"interviewerName" : {
			required : true
		},
		"hrInterviewer" : {
			required : true
		},
		"mailTitle" : {
			required : true
		},
		"toMail" : {
			required : true
		},
		"formwork" : {
			required : true
		}
	});

	//����
	$("#useAreaId").val($("#useAreaIdHidden").val());
	$("#useAreaName").val($("#useAreaId").find("option:selected").text());
	$("#useAreaId").change(function(){
		$("#useAreaName").val($(this).find("option:selected").text());
	});
	//����
//		$('select[name="invitation[positionLevel]"] option').each(function() {
//			if( $(this).val() == $("#level").val() ){
//				$(this).attr("selected","selected'");
//			}
//		});
	//add chenrf 20130524
	//ѡ����������ְλʱ�����������ֵ�����
	function initLevelWY(){
		var dataArr=[];
		var data=$.ajax({
						url:'?model=hr_basicinfo_level&action=listJson&sort=personLevel&dir=ASC&status=0',
						type:'post',
						dataType:'json',
						async:false
					}).responseText;
		data=eval("("+data+")");
//		data=data.collection;
		var $positionLevel=$("#positionLevel");
		$positionLevel.empty();
		$("<option  value=''>...��ѡ��...</option>").appendTo($positionLevel);
		for(i=0;i<data.length;i++){
			var option=$("<option></option>");
			option.val(data[i].personLevel);
			option.text(data[i].personLevel);
			option.appendTo($positionLevel);
		}
	}
	if($("#postType").val()=='YPZW-WY'){
		initLevelWY();
	}
	$("#postType").change(function(){
		if($(this).val()=='YPZW-WY'){
			initLevelWY();
		}else{
			var option='<option value="">...��ѡ��...</option><option value="1">����</option><option value="2">�м�</option><option value="3">�߼�</option>';
			$("#positionLevel").html(option);;
		}
	});
	$( 'textarea.editor' ).ckeditor();
	$("#interviewerName").yxselect_user({
			hiddenId : 'interviewerId',
			mode : 'check'
	});
	$("#hrInterviewer").yxselect_user({
			hiddenId : 'hrInterviewerId',
			formCode : 'hrInterviewer',
			mode : 'check'
	});
	//������Աѡ��
	$("#toccmail").yxselect_user({
		hiddenId : 'toccmailId',
		mode : 'check'

	})
    //������Աѡ��
	$("#tobccmail").yxselect_user({
		hiddenId : 'tobccmailId',
		mode : 'check'
	})

})
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
/**
 * ���ģ������
 * @param formwork
 * @param remark
 */
function fillTemp(formwork,remark){
	$("#formwork").val(formwork);
	$("#remark").val(remark);
}