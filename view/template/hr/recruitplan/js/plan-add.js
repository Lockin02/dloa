$(document).ready(function() {
	initLevel();
	initPCC();
	$("#positionName").mouseover(function(){
		$.validationEngine.buildPrompt(this,"��ѡ������ְλ��������ѡ��û�и�ְλ��������дְλ˵����",null);
	});
	$("#positionName").mouseout(function(){
		$.validationEngine.closePrompt(this,false);
	});

	$("#developPositionName").mouseover(function(){
		$.validationEngine.buildPrompt(this,"�з�ְλ�磺java��php",null);
	});
	$("#developPositionName").mouseout(function(){
		$.validationEngine.closePrompt(this,false);
	});

	$("#addTypeCode").mouseover(function(){
		$.validationEngine.buildPrompt(this,"��Ա����Ϊ'��ְ/���ڲ���'����Ҫ��д��ְ/������",null);
	});
	$("#addTypeCode").mouseout(function(){
		$.validationEngine.closePrompt(this,false);
	});

	uploadfile = createSWFUpload({
		"serviceType": "oa_hr_recruitplan_plan"
	});

	
	
	$("#deptName").yxselect_dept({
				hiddenId : 'deptId',
				event : {
					selectReturn : function(e,row){
						$("#positionName").val("");
						$("#positionId").val("");
						$("#positionName").yxcombogrid_position("remove");
						//ְλѡ��
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

	$("#addTypeCode").bind('change',function(){//��ְ/����������������ʽ
		if($(this).val()=="ZYLXLZ"){//��ְ/����
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
	$("#projectType").bind('change',function(){//��Ŀ����
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
		}else if($(this).val()=="YFXM"){//�з�������Ŀ
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
	validate({
				"deptName" : {
					required : true
				},
				"positionName" : {
					required : true
				},
				"postType" : {
					required : true
				},
				"needNum" : {
					required : true,
					custom : ['onlyNumber']
				},
				"workPlace" : {
					required : true
				},
				"hopeDate" : {
					required : true
				},
				"applyReason" : {
					required : true
				},
				"resumeToName" : {
					required : true
				},
				"addTypeCode" : {
					required: true
				},
				"employmentTypeCode" : {
					required : true
				},
				"wageRange" : {
					required : true
				},
				"regionId" : {
					required : true
				}
 		});
 });



 	//��֤�Ƿ���ѡ����Ŀ����
 	function checkProjectType(){
 		if($("#projectType").val()==""){
 			alert("����ѡ����Ŀ����");
 		}
 	}

   //ֱ���ύ����
function toSubmit(){
	document.getElementById('form1').action = "?model=hr_recruitplan_plan&action=add&actType=audit";
}