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

	//区域
	$("#useAreaId").val($("#useAreaIdHidden").val());
	$("#useAreaName").val($("#useAreaId").find("option:selected").text());
	$("#useAreaId").change(function(){
		$("#useAreaName").val($(this).find("option:selected").text());
	});
	//级别
//		$('select[name="invitation[positionLevel]"] option').each(function() {
//			if( $(this).val() == $("#level").val() ){
//				$(this).attr("selected","selected'");
//			}
//		});
	//add chenrf 20130524
	//选择网优类型职位时，加载数据字典内容
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
		$("<option  value=''>...请选择...</option>").appendTo($positionLevel);
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
			var option='<option value="">...请选择...</option><option value="1">初级</option><option value="2">中级</option><option value="3">高级</option>';
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
	//抄送人员选择
	$("#toccmail").yxselect_user({
		hiddenId : 'toccmailId',
		mode : 'check'

	})
    //密送人员选择
	$("#tobccmail").yxselect_user({
		hiddenId : 'tobccmailId',
		mode : 'check'
	})

})
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
/**
 * 填充模版内容
 * @param formwork
 * @param remark
 */
function fillTemp(formwork,remark){
	$("#formwork").val(formwork);
	$("#remark").val(remark);
}