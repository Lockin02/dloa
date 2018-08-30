$(document).ready(function () {

	initPCC();

	//��Ŀ����
	$('select[name="apply[projectType]"] option').each(function () {
		if ($(this).val() == $("#projectTypeValue").val()) {
			$(this).attr("selected", "selected");
		};
	});

	$('#computerConfiguration option').each(function () {
		if ($(this).val() == $('#computerConfiguration').attr('val')) {
			$(this).attr("selected", "selected");
			return false;
		}
	});

	if ($("#projectType").val() == "GCXM") { //�з�������Ŀ
		$("#projectGroup").yxcombogrid_esmproject({
			hiddenId: 'projectGroupId',
			nameCol: 'projectName',
			isShowButton: false,
			height: 250,
			event: {
				clear: function () {
					$("#projectCode").val("");
				}
			},
			gridOptions: {
				isTitle: true,
				showcheckbox: false,
				event: {
					row_dblclick: function (e, row, data) {
						$("#projectCode").val(data.projectCode);
					}
				}
			}
		});
		$("#projectGroup").yxcombogrid_esmproject("show");
	} else if ($("#projectType").val() == "YFXM") {
		$("#projectGroup").yxcombogrid_rdprojectfordl({
			hiddenId: 'projectGroupId',
			nameCol: 'projectName',
			isShowButton: false,
			height: 250,
			event: {
				clear: function () {
					$("#projectCode").val("");
				}
			},
			gridOptions: {
				isTitle: true,
				showcheckbox: false,
				event: {
					row_dblclick: function (e, row, data) {
						$("#projectCode").val(data.projectCode);
					}
				}
			}
		});
		$("#projectGroup").yxcombogrid_rdprojectfordl("show");
	}

	uploadfile = createSWFUpload({
		"serviceType": "oa_hr_recruitment_apply",
		"serviceId": $("#id").val()
	});

	$("#deptName").yxselect_dept({
		hiddenId: 'deptId',
		event: {
			selectReturn: function (e, row) {
				$("#positionName").val("");
				$("#positionId").val("");
				$("#positionName").yxcombogrid_position("remove");
				//ְλѡ��
				$("#positionName").yxcombogrid_position({
					hiddenId: 'positionId',
					width: 350,
					gridOptions: {
						param: {
							deptId: row.dept.id
						},
						event: {
							row_dblclick: function (e, row, data) {
								$("#positionId").val(data.id); //ְλid
							}
						}
					}
				});
				$("#positionName").yxcombogrid_position("show");
			}
		}
	});
	$("#resumeToName").yxselect_user({
		hiddenId: 'resumeToId',
		mode: 'check'
	});
	$("#positionName").yxcombogrid_position({
		hiddenId: 'positionId',
		gridOptions: {
			param: {
				deptId: $("#deptId").val()
			},
			event: {
				row_dblclick: function (e, row, data) {
					$("#positionId").val(data.id); //ְλid
				}
			}
		}
	});
	if ($("#addTypeCode").val() == "ZYLXLZ") { //��ְ/����
		$("#leaveManName").removeClass("readOnlyText");
		$("#leaveManName").addClass("txt");
		$("#leaveManName").attr("readonly", false);
		$("#leaveStyle").css("color", "blue");
	}

	$("#addTypeCode").bind('change', function () { //��ְ/����������������ʽ
		if ($(this).val() == "ZYLXLZ") { //��ְ/����
			$("#leaveManName").removeClass("readOnlyText");
			$("#leaveManName").addClass("txt");
			$("#leaveManName").val("");
			$("#leaveManName").attr("readonly", false);
			$("#leaveStyle").css("color", "blue");
		} else {
			$("#leaveManName").val("");
			$("#leaveManName").removeClass("txt");
			$("#leaveManName").addClass("readOnlyText");
			$("#leaveManName").attr("readonly", true);
			$("#leaveStyle").css("color", "black");
		}
	});
	$("#projectType").bind('change', function () { //��Ŀ����
		$("#projectGroup").val("");
		$("#projectGroupId").val("");
		$("#projectCode").val("");
		$("#projectGroup").yxcombogrid_esmproject("remove");
		$("#projectGroup").yxcombogrid_rdprojectfordl("remove");
		if ($(this).val() == "GCXM") {
			$("#projectGroup").yxcombogrid_esmproject({
				hiddenId: 'projectGroupId',
				nameCol: 'projectName',
				isShowButton: false,
				height: 250,
				event: {
					clear: function () {
						$("#projectCode").val("");
					}
				},
				gridOptions: {
					isTitle: true,
					showcheckbox: false,
					event: {
						row_dblclick: function (e, row, data) {
							$("#projectCode").val(data.projectCode);
						}
					}
				}
			});
			$("#projectGroup").yxcombogrid_esmproject("show");
		} else if ($(this).val() == "YFXM") { //�з�������Ŀ
			$("#projectGroup").yxcombogrid_rdprojectfordl({
				hiddenId: 'projectGroupId',
				nameCol: 'projectName',
				isShowButton: false,
				isFocusoutCheck: false,
				height: 250,
				event: {
					clear: function () {
						$("#projectCode").val("");
					}
				},
				gridOptions: {
					isTitle: true,
					showcheckbox: false,
					event: {
						row_dblclick: function (e, row, data) {
							$("#projectCode").val(data.projectCode);
						}
					}
				}
			});
			$("#projectGroup").yxcombogrid_rdprojectfordl("show");
		}
	});

	$("#regionId").change(function () {
		$('#useAreaId').val($(this).val());
	});
	if ($("#editFromApply").val() == 1) {
		$("#submitApp").css('display', 'none');
	}
	$("#employmentTypeCode").change(function () {
		$("#employmentType").val($("#employmentTypeCode").find("option:selected").text());
	});
	$("#addTypeCode").change(function () {
		$("#addType").val($("#addTypeCode").find("option:selected").text());

	});
});

//��֤�Ƿ���ѡ����Ŀ����
function checkProjectType() {
	if ($("#projectType").val() == "") {
		alert("����ѡ����Ŀ����");
	}
}

//ֱ���ύ����
function toSubmit() {
	document.getElementById('form1').action = "?model=hr_recruitment_apply&action=edit&actType=onSubmit";
}