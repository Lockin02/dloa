$(document).ready(function() {
	/**
	 * ��֤��Ϣ
	 */
	validate({
		"executionDate" : {
			required : true
		},
		"workStatus" : {
			required : true
		}
	});

	//������Ŀ��Ⱦ
	$("#projectName").yxcombogrid_esmproject({
		hiddenId : 'projectId',
		nameCol : 'projectName',
		isShowButton : false,
		height : 250,
		isDown : true,
		gridOptions : {
			action : 'myProjectListPageJson',
			param : {'selectstatus' : 'GCXMZT02'},
			isTitle : true,
			showcheckbox : false,
			event : {
				'row_dblclick' : function(e,row,data) {
					$("#projectCode").val(data.projectCode);

				    $("#province").val(data.provinceId);//����ʡ��Id
				    $("#province").trigger("change");
					$("#city").val(data.cityId);//����ID
					$("#city").trigger("change");
					$("#projectEndDate").val(data.planEndDate);//����ID

					//������������Ϣ
					clearActivity();

					//��Ⱦ����
					initActivity(data.id);
				}
			},
			// Ĭ�������ֶ���
			sortname : "c.updateTime",
			// Ĭ������˳�� ����DESC ����ASC
			sortorder : "DESC"
		},
		event : {
			'clear' : function(){
				clearActivity();
			}
		}
	});

	//ʵ����������Ϣ
	initCity();

	//��Ʊ���ͻ���
	billTypeArr = getBillType();
});

//����������Ϣ
function initActivity(projectId){
	$("#activityName").yxcombogrid_esmactivity("remove");
	//�����Ŀidδ���壬���ȡҳ���е���Ŀid
	if(projectId == undefined){
		projectId = $("#projectId").val();
	}

	//������Ŀ��Ⱦ
	$("#activityName").yxcombogrid_esmactivity({
		hiddenId : 'activityId',
		nameCol : 'activityName',
		isShowButton : false,
		height : 250,
		gridOptions : {
//			param : {"projectId":projectId , 'isLeaf' : 1 , 'memberIn' : $("#userId").val()},
			param : {"projectId":projectId , 'isLeaf' : 1 },
			isTitle : true,
			showcheckbox : false,
			event : {
				'row_dblclick' : function(e,row,data) {
					//�����һЩ����
					changeActivity();

					$("#workloadUnit").val(data.workloadUnit);
					$("#workloadUnitView").val(data.workloadUnit);
					$("#activityEndDate").val(data.planEndDate);//����ID
				}
			}
		},
		event : {
			'clear' : function(){
				changeActivity();
			}
		}
	});
}

// ��̨����������ɽ���
function calTaskProcess(workload){
	if(workload == "" || workload *1 == 0){
		$("#workProcess").val('');
		return false;
	}
	var activityId = $("#activityId").val();
	if(activityId == "" || activityId*1 == 0){
		return false;
	}
	$.ajax({
		type : "POST",
		url : "?model=engineering_activity_esmactivity&action=calTaskProcess",
		data : {
			"workload" : workload,
			"id" : activityId
		},
		success : function(msg) {
			if(msg != -1){
				var processObj = eval("(" + msg + ")");
				$("#workProcess").val(processObj.process);
				$("#thisActivityProcess").val(processObj.thisActivityProcess);
				$("#thisProjectProcess").val(processObj.thisProjectProcess);
			}else{
				alert('��ȡ���ȴ���');
			}
		}
	});
}

//����֤
function checkForm(){
	var isTrue = true;
	//�����ж� -- ����������Ѿ���д����־�������������д
	$.ajax({
		type : "POST",
		url : "?model=engineering_worklog_esmworklog&action=checkActivityLog",
		data : {
			"executionDate" : $("#executionDate").val(),
			"activityId" : $("#activityId").val()
		},
	    async: false,
		success : function(msg) {
			if(msg != "0"){
				if(msg *1 == msg){
					if(confirm('������־�Ѿ���д���Ƿ��������޸�?')){
						isTrue = false;
						location = "?model=engineering_worklog_esmworklog&action=toEdit&id=" + msg;
					}else{
						isTrue = false;
					}
				}else{
					alert(msg);
					isTrue = false;
				}
			}
		}
	});
	return false;
//	return isTrue;
}