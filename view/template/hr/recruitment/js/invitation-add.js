$(document).ready(function() {
	//add chenrf 20150523
	$("#useAreaNameHidden").val($("#useAreaName").find("option:selected").text());
	$("#useAreaName").change(function(){
		$("#useAreaNameHidden").val($("#useAreaName").find("option:selected").text());
	});
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
		$("#postTypeName").val($(this).find('option:selected').html());
		if($(this).val()=='YPZW-WY'){
			initLevelWY();
		}else{
			var option='<option value="">...请选择...</option><option value="1">初级</option><option value="2">中级</option><option value="3">高级</option>';
			$("#positionLevel").html(option);;
		}
	});
	$( 'textarea.editor' ).ckeditor();
	$("#deptName").yxselect_dept({
			hiddenId : 'deptId',
			event:{
				selectReturn : function(e,row){
					$("#positionsName").yxcombogrid_position('remove');
					$("#positionsName").yxcombogrid_position({
						hiddenId:'positionsId',
						width : 550,
						gridOptions : {
							param:{deptId:row.dept.id}
						}

					});
				}
			}
	});
	$("#positionsName").attr('readonly',true).click(function(){
		if($("#deptName").val()==''){
			alert('请选择用人部门!');
			return false;
		}

	});

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
		mode : 'check',
		formCode : 'intUseManager'

	})
    //密送人员选择
	$("#tobccmail").yxselect_user({
		hiddenId : 'tobccmailId',
		mode : 'check',
		formCode : 'intUseManager'
	})
	$("#parentCode").yxcombogrid_interviewparent({
		hiddenId : 'parentId',
		nameCol:'formCode',
		isFocusoutCheck:false,
		gridOptions : {
			event:{
				'row_dblclick' : function(e, row, data) {
					$("#parentCode").val(data.formCode);
					$("#parentId").val(data.id);
				}
			},
			showcheckbox : false
		}
	});
	validate({
//		"interviewPlace" : {
//			required : true
//		},
//		"interviewDate" : {
//			required : true
//		},
		"positionLevel" :　{
			required : true
		},
		"deptName" : {
			required : true
		},
		"interviewerName" : {
			required : true
		},
		"setdTitle" : {
			required : true
		},
		"formwork" : {
			required : true
		}
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
function checkForm(){
	if($.trim($('#remark').val())==''){
		alert('邮件内容不能为空！');
		return false;
	}
	return true;
}