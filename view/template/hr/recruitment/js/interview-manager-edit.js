$(document).ready(function () {

	//�Ƿ�Ϊʵϰ��
	var deptId = $("#deptId").val();
	if (deptId == '155') {
		isIntern();
	} else {
		noIntern();
	}
	positionsGrid(deptId);
	hrJobNameGrid(deptId);

	//����
	$("#postType").trigger("change");
	$("#positionLevel").val($("#positionLevelSelect").val());
	positionLevelChange();

	//�Ա�
	$("#sexy").val($("#sexySelect").val());

	//��Ŀ����
	$("#projectType").val($("#projectTypeResultSelect").val());
	if ($("#projectType").val() == "GCXM") {
		isProject();
	} else if ($("#projectType").val() == "YFXM") { //�з�������Ŀ
		isResearch();
	}

	//�Ƿ񱾵ػ������޷����ߣ�
	$("#isLocal").val($("#isLocalSelect").val());

	//ʵϰ��������
	$("#internshipSalaryType").val($("#internshipSalaryTypeVal").val());

	//ǩ������ҵ����Э�顷
	$("#useSign").val($("#useSignSelect").val()).trigger("change");

	//����������н�㼰н���Ƿ��Ӧ
	$("#hrIsMatch").val($("#hrIsMatchSelect").val());

	$("#itemTable").yxeditgrid({
		objName: 'interview[items]',
		isAddOneRow: true,
		delTagName: 'isDelTag',
		url: '?model=hr_recruitment_interviewComment&action=interviewManagerJson',
		param: {
			interviewId: $("#id").val(),
			invitationId: $("#invitationId").val(),
			interviewerType: 1
		},
		colModel: [{
			display: 'id',
			name: 'id',
			type: 'hidden'
		}, {
			display: '���Թ�',
			name: 'interviewer',
			validation: {
				required: true
			},
			process: function ($input) {
				var rowNum = $input.data("rowNum");
				$input.yxselect_user({
					hiddenId: 'itemTable_cmp_interviewerId' + rowNum
				});
			},
			readonly: true
		}, {
			display: '���Թ�ID',
			name: 'interviewerId',
			type: 'hidden'
		}, {
			display: '��������',
			name: 'interviewDate',
			type: 'date',
			validation: {
				required: true
			},
			readonly: true
		}, {
			display: '��������',
			name: 'useWriteEva',
			type: 'textarea',
			cols: '40',
			rows: '3',
			validation: {
				required: true
			}
		}, {
			display: '��������',
			name: 'interviewEva',
			type: 'textarea',
			cols: '40',
			rows: '3',
			validation: {
				required: true
			}
		}]
	});

	$("#humanResource").yxeditgrid({
		objName: 'interview[humanResources]',
		isAddOneRow: true,
		delTagName: 'isDelTag',
		url: '?model=hr_recruitment_interviewComment&action=interviewManagerJson',
		param: {
			interviewId: $("#id").val(),
			invitationId: $("#invitationId").val(),
			interviewerType: 2
		},
		colModel: [{
			display: 'id',
			name: 'id',
			type: 'hidden'
		}, {
			display: '���Թ�',
			name: 'interviewer',
			validation: {
				required: true
			},
			process: function ($input) {
				var rowNum = $input.data("rowNum");
				$input.yxselect_user({
					hiddenId: 'humanResource_cmp_interviewerId' + rowNum
				});
			},
			readonly: true
		}, {
			display: '���Թ�ID',
			name: 'interviewerId',
			type: 'hidden'
		}, {
			display: '��������',
			name: 'interviewDate',
			type: 'date',
			validation: {
				required: true
			},
			readonly: true
		}, {
			display: '��������',
			name: 'interviewEva',
			type: 'textarea',
			cols: '40',
			rows: '3',
			validation: {
				required: true
			}
		}]
	});

	//�������Ӵ����˲�תΪ����¼��
	if ($("#changeHire").val() == 1) {
		$("#useInterviewResult [value=1]").attr("selected", true);
		$("#useInterviewResult").change(function () {
			if ($(this).val() != 1) {
				alert('���Խ�����ܸ��ģ�');
				$("#useInterviewResult [value=1]").attr("selected", true);
			}
		});
	}

	//�Ƿ�Ϊ����������
	if ($("#isCopy").val() == 1) {
		$("#form1").attr("action", "?model=hr_recruitment_interview&action=interviewadd");
	}

	//�޸��Ѿ���������ĵ���
	if ($("#audit").val() == 1) {
		$("#changeTip").val(1);
		$("#form1").attr("action", "?model=hr_recruitment_interview&action=editAuditFinish");
	}
});

$(function () {
	if ($("input[name='interview[isCompanyStandard]']:checked").val() == '0') {
		getRadio()
	} else {
		$("#hrRequire").val("");
		closeRadio()
	}
})