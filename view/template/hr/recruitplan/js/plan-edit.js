$(document).ready(function() {
	initPCC();
	
	var postType=$("#postType").val();
	if('YPZW-WY'==postType) { //�����������
		initLevelWY();
	}else
		initLevel();
	// ����
// $('select[name="apply[positionLevel]"] option').each(function() {
// if( $(this).val() == $("#level").val() ){
// $(this).attr("selected","selected'");
// }
// });
	// ��Ŀ����
		$('select[name="plan[projectType]"] option').each(function() {
			if( $(this).val() == $("#projectTypeValue").val() ){
				$(this).attr("selected","selected'");
			}
		});
		if($("#projectType").val()=="GCXM"){// �з�������Ŀ
	// $("#leaveManName").removeClass("readOnlyText");
	// $("#leaveManName").addClass("txt");
				$("#projectGroup").yxcombogrid_esmproject({
					hiddenId : 'projectGroupId',
					nameCol : 'projectName',
					isShowButton : false,
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
				$("#projectGroup").yxcombogrid_esmproject("show");
		}else if($(this).val()=="YFXM"){
				$("#projectGroup").yxcombogrid_rdprojectfordl({
					hiddenId : 'projectGroupId',
					nameCol : 'projectName',
					isShowButton : false,
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

	uploadfile = createSWFUpload({
		"serviceType": "oa_hr_recruitplan_plan",
		"serviceId":$("#id").val()
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
						param:{deptId:row.dept.id},
						event:{
							'row_dblclick':function(e, row, data){													
														$("#positionId").val(data.id);   //ְλid
													}
						}
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
	$("#positionName").yxcombogrid_position({
		hiddenId : 'positionId',
		gridOptions : {
			param:{deptId:$("#deptId").val()},
			event:{
				'row_dblclick':function(e, row, data){													
								$("#positionId").val(data.id);   //ְλid
					}
			}
		}
	});
	if($("#addTypeCode").val()=="ZYLXLZ"){// ��ְ/����
		$("#leaveManName").removeClass("readOnlyText");
		$("#leaveManName").addClass("txt");
		$("#leaveManName").attr("readonly",false);
		$("#leaveStyle").css("color","blue");
	}

	$("#addTypeCode").bind('change',function(){// ��ְ/����������������ʽ
		if($(this).val()=="ZYLXLZ"){// ��ְ/����
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
	$("#projectType").bind('change',function(){// ��Ŀ����
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
		}else if($(this).val()=="YFXM"){// �з�������Ŀ
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
// "positionLevel" : {
// required : true
// },
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
// "addModeName" : {
// required : true
// },
				"addTypeCode" : {
					required : true
				},
				"employmentTypeCode" : {
					required : true
				},
				"wageRange" : {
					required : true
				}
 		});
 });


   // ֱ���ύ����
function toSubmit(){
	document.getElementById('form1').action = "?model=hr_recruitplan_plan&action=edit&actType=audit";
}