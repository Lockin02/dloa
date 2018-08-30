$(document).ready(function() {
	$("#socialPlace").mouseover(function(){
		$.validationEngine.buildPrompt(this,"�����������麣�����Ϻ�,������Դ��ͨ�������ڱ��������ݱ���������ڹ���",null);
	});
	$("#socialPlace").mouseout(function(){
		$.validationEngine.closePrompt(this,false);
	});

	//�Ƿ�����
	$('select[name="interview[hrIsManageJob]"] option').each(function() {
		if( $(this).val() == $("#hrIsManageJobSelect").val() ){
			$(this).attr("selected","selected'");
		}
	});

	//����н��
	$('select[name="interview[hrIsMatch]"] option').each(function() {
		if( $(this).val() == $("#hrIsMatchSelect").val() ){
			$(this).attr("selected","selected'");
		}
	});

	//ȷ����
	$("#manager").yxselect_user({
		hiddenId : 'managerId',
		mode : 'check'
	});

	validate({
		"hrIsManageJob" : {
			required : true
		},
		"hrJobName" : {
			required : true
		},
		"hrSourceType1" : {
			required : true
		},
		"entryDate" : {
			required : true
		},
		"hrHireType" : {
			required : true
		},
		"socialPlace" : {
			required : true
		},
		"probation" : {
			required : true
		},
		"contractYear" : {
			required : true
		},
		"wageLevelCode" : {
			required : true
		}
	});
//	JLLYArr = getData('JLLY');
//	addDataToSelect(JLLYArr, 'hrSourceType1Name');

	$("#hrJobName").yxcombogrid_jobs({
		hiddenId : 'hrJobId',
		width : 350,
		gridOptions : {
			param:{deptId:$("#deptId").val()}
		}
	});
	$("#socialPlace").yxcombogrid_socialplace({
		hiddenId : 'socialPlaceId',
		width : 350,
		gridOptions : {
			param:{deptId:$("#deptId").val()}
		}
	});
})

 	   //����
function toEdit(){
	document.getElementById('form1').action = "?model=hr_recruitment_interview&action=lastedit&editType=edit";
}
