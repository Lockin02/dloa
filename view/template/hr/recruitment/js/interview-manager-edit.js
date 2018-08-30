$(document).ready(function () {

	//是否为实习生
	var deptId = $("#deptId").val();
	if (deptId == '155') {
		isIntern();
	} else {
		noIntern();
	}
	positionsGrid(deptId);
	hrJobNameGrid(deptId);

	//级别
	$("#postType").trigger("change");
	$("#positionLevel").val($("#positionLevelSelect").val());
	positionLevelChange();

	//性别
	$("#sexy").val($("#sexySelect").val());

	//项目类型
	$("#projectType").val($("#projectTypeResultSelect").val());
	if ($("#projectType").val() == "GCXM") {
		isProject();
	} else if ($("#projectType").val() == "YFXM") { //研发类型项目
		isResearch();
	}

	//是否本地化（仅限服务线）
	$("#isLocal").val($("#isLocalSelect").val());

	//实习工资类型
	$("#internshipSalaryType").val($("#internshipSalaryTypeVal").val());

	//签订《竞业限制协议》
	$("#useSign").val($("#useSignSelect").val()).trigger("change");

	//基本工资与薪点及薪资是否对应
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
			display: '面试官',
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
			display: '面试官ID',
			name: 'interviewerId',
			type: 'hidden'
		}, {
			display: '面试日期',
			name: 'interviewDate',
			type: 'date',
			validation: {
				required: true
			},
			readonly: true
		}, {
			display: '笔试评价',
			name: 'useWriteEva',
			type: 'textarea',
			cols: '40',
			rows: '3',
			validation: {
				required: true
			}
		}, {
			display: '面试评价',
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
			display: '面试官',
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
			display: '面试官ID',
			name: 'interviewerId',
			type: 'hidden'
		}, {
			display: '面试日期',
			name: 'interviewDate',
			type: 'date',
			validation: {
				required: true
			},
			readonly: true
		}, {
			display: '面试评价',
			name: 'interviewEva',
			type: 'textarea',
			cols: '40',
			rows: '3',
			validation: {
				required: true
			}
		}]
	});

	//如果是想从储备人才转为立即录用
	if ($("#changeHire").val() == 1) {
		$("#useInterviewResult [value=1]").attr("selected", true);
		$("#useInterviewResult").change(function () {
			if ($(this).val() != 1) {
				alert('面试结果不能更改！');
				$("#useInterviewResult [value=1]").attr("selected", true);
			}
		});
	}

	//是否为复制新评估
	if ($("#isCopy").val() == 1) {
		$("#form1").attr("action", "?model=hr_recruitment_interview&action=interviewadd");
	}

	//修改已经完成审批的单据
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