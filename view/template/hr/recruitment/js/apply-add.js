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
		"serviceType": "oa_hr_recruitment_apply"
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

});

/* //�ύУ������
 	function checkData(){
 		if($("#addTypeCode").val()=="ZYLXLZ"){
 			if($("#leaveManName").val()==""){
 				alert("��������ְ/����������");
 				return false;
 			}
 		}else��if($("#positionLevelHidden").val()==""){
 				alert("��ѡ�񼶱�");
 				return false;

 		}else��if($("#addModeNameHidden").val()==""){
 				alert("��ѡ���鲹�䷽ʽ");
 				return false;
 		}else if($("#addTypeCode").val()==""){
 				alert("��ѡ����Ա����");
				return false;
 		}
 		else{
 			return true;
 		}
 	}*/

//��֤�Ƿ���ѡ����Ŀ����
function checkProjectType(){
	if($("#projectType").val()==""){
		alert("����ѡ����Ŀ����");
	}
}

//ֱ���ύ
function toSubmit(){
	//document.getElementById('form1').action = "?model=hr_recruitment_apply&action=add&actType=audit";
	document.getElementById('form1').action = "?model=hr_recruitment_apply&action=add&actType=onSubmit";
}

