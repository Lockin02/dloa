$(document).ready(function() {
	initLevel();
	initPCC();
	$("#positionName").mouseover(function(){
		$.validationEngine.buildPrompt(this,"请选择需求职位。若下拉选择没有该职位，请先填写职位说明书",null);
	});
	$("#positionName").mouseout(function(){
		$.validationEngine.closePrompt(this,false);
	});

	$("#developPositionName").mouseover(function(){
		$.validationEngine.buildPrompt(this,"研发职位如：java、php",null);
	});
	$("#developPositionName").mouseout(function(){
		$.validationEngine.closePrompt(this,false);
	});

	$("#addTypeCode").mouseover(function(){
		$.validationEngine.buildPrompt(this,"增员类型为'离职/换岗补充'，需要填写离职/换岗人",null);
	});
	$("#addTypeCode").mouseout(function(){
		$.validationEngine.closePrompt(this,false);
	});

	uploadfile = createSWFUpload({
		"serviceType": "oa_hr_recruitment_apply"
	});

	$("#deptName").yxselect_dept({
		hiddenId : 'deptId',
		event : {
			selectReturn : function(e,row){
				$("#positionName").val("");
				$("#positionId").val("");
				$("#positionName").yxcombogrid_position("remove");
				//职位选择
				$("#positionName").yxcombogrid_position({
					hiddenId : 'positionId',
					width:350,
					gridOptions : {
						param:{deptId:row.dept.id}
					}
				});
				$("#positionName").yxcombogrid_position("show");
			}
		}
	});

	$("#resumeToName").yxselect_user({
		hiddenId : 'resumeToId',
		mode:'check'
	});

	var deptId=$("#deptId").val();
	$("#positionName").yxcombogrid_position({
		hiddenId : 'positionId',
		width:350,
		gridOptions : {
			param:{deptId:deptId}
		}
	});

	$("#addTypeCode").bind('change',function(){//离职/换岗人姓名更换样式
		if($(this).val()=="ZYLXLZ"){//离职/换岗
			$("#leaveManName").removeClass("readOnlyText");
			$("#leaveManName").addClass("txt");
			$("#leaveManName").attr("readonly",false);
			$("#leaveStyle").css("color","blue");
		}else{
			$("#leaveManName").val("");
			$("#leaveManName").removeClass("txt");
			$("#leaveManName").addClass("readOnlyText");
			$("#leaveManName").attr("readonly",true);
			$("#leaveStyle").css("color","black");
		}
	});

	$("#projectType").bind('change',function(){//项目类型
		$("#projectGroup").val("");
		$("#projectGroupId").val("");
		$("#projectCode").val("");
		$("#projectGroup").yxcombogrid_esmproject("remove");
		$("#projectGroup").yxcombogrid_rdprojectfordl("remove");
		if($(this).val()=="GCXM"){
			$("#projectGroup").yxcombogrid_esmproject({
				hiddenId : 'projectGroupId',
				nameCol : 'projectName',
				isShowButton : false,
				height : 250,
				event : {
					'clear': function() {
						$("#projectCode").val("");
					}
				},
				gridOptions : {
					isTitle : true,
					showcheckbox : false,
					event : {
						'row_dblclick' : function(e, row, data) {
							$("#projectCode").val(data.projectCode);
						}
					}
				}
			});
			$("#projectGroup").yxcombogrid_esmproject("show");
		}else if($(this).val()=="YFXM"){//研发类型项目
			$("#projectGroup").yxcombogrid_rdprojectfordl({
				hiddenId : 'projectGroupId',
				nameCol : 'projectName',
				isShowButton : false,
				isFocusoutCheck:false,
				height : 250,
				event : {
					'clear' : function() {
						$("#projectCode").val("");
					}
				},
				gridOptions : {
					isTitle : true,
					showcheckbox : false,
					event : {
						'row_dblclick' : function(e, row, data) {
							$("#projectCode").val(data.projectCode);
						}
					}
				}
			});
			$("#projectGroup").yxcombogrid_rdprojectfordl("show");
		}else{
		}
	});

});

/* //提交校验数据
 	function checkData(){
 		if($("#addTypeCode").val()=="ZYLXLZ"){
 			if($("#leaveManName").val()==""){
 				alert("请输入离职/换岗人姓名");
 				return false;
 			}
 		}else　if($("#positionLevelHidden").val()==""){
 				alert("请选择级别");
 				return false;

 		}else　if($("#addModeNameHidden").val()==""){
 				alert("请选择建议补充方式");
 				return false;
 		}else if($("#addTypeCode").val()==""){
 				alert("请选择增员类型");
				return false;
 		}
 		else{
 			return true;
 		}
 	}*/

//验证是否已选择项目类型
function checkProjectType(){
	if($("#projectType").val()==""){
		alert("请先选择项目类型");
	}
}

//直接提交
function toSubmit(){
	//document.getElementById('form1').action = "?model=hr_recruitment_apply&action=add&actType=audit";
	document.getElementById('form1').action = "?model=hr_recruitment_apply&action=add&actType=onSubmit";
}

