
$(document).ready(function () {
	$("#positionsName").attr("readonly", true);
	$("#positionsName").click(function () {
		if ($("#deptId").val() == "") {
			alert("��ѡ�����˲���");
			$(this).val("");
		}
	});

	$("#hrJobName").attr("readonly", true);
	$("#hrJobName").click(function () {
		if ($("#deptId").val() == "") {
			alert("��ѡ�����˲���");
			$(this).val("");
		}
	});

	$("#manager").yxselect_user({
		hiddenId: 'managerId',
		mode: 'check'
	});

	$("#useManager").yxselect_user({
		hiddenId: 'useManagerId',
		mode: 'check'
	});

	$("#tutor").yxselect_user({
		hiddenId: 'tutorId'
	});

	//������Ϣ
	$("#deptName").dblclick(function () {
		$("#positionsName").val("");
		$("#positionsId").val("");
		$("#positionsName").yxcombogrid_jobs("remove");
	});

	$("#deptName").yxselect_dept({
		hiddenId: 'deptId',
		event: {
			selectReturn: function (e, row) {
				//ʵϰ������
				if (row.dept.id == '155') {
					$("#useHireType").val('HRLYXX-03').trigger('change').unbind('change').change(function () {
						alert('�����޸�¼����ʽ��');
						$(this).val('HRLYXX-03');
					});
				} else {
					$("#useHireType").unbind('change').change(function () {
						if ($(this).val() == 'HRLYXX-03') {
							isIntern();
						} else {
							noIntern();
						}
						positionLevelChange();
					});
				}

				$("#positionsName").val("");
				$("#positionsId").val("");
				$("#hrJobName").val("");
				$("#hrJobId").val("");
				$("#positionsName").yxcombogrid_jobs("remove");
				$("#hrJobName").yxcombogrid_jobs("remove");
				positionsGrid(row.dept.id);
				hrJobNameGrid(row.dept.id);

				positionLevelChange(); //���ŵĲ����¼�
			}
		}
	});

	$("#compensation").hide();
	var hintArr = '����д����ҵ����Э�顷������׼';
	//ѡ��ǩ������ҵ����Э�顷
	$("#useSign").change(function () {
		if ($(this).val() == '��') {
			if ($.trim($("#compensation").val()) == '') {
				$("#compensation").css({
					"color": "#999"
				});
				$("#compensation").val(hintArr);
			}
			$("#compensation").show();
		} else {
			$("#compensation").hide();
		}
	});

	$("#compensation").focus(function () {
		if ($.trim($(this).val()) == hintArr) {
			$(this).val("");
			$(this).css({
				"color": "#000"
			});
		}
	});

	$("#compensation").blur(function () {
		if ($.trim($(this).val()) == '') {
			$(this).val(hintArr);
			$(this).css({
				"color": "#999"
			});
		}
	});

	//��������
	$("#resumeCode").yxcombogrid_resume({
		hiddenId: 'resumeId',
		nameCol: 'applicantName',
		isFocusoutCheck: false,
		gridOptions: {
			event: {
				row_dblclick: function (e, row, data) {
					$("#phone").val(data.phone);
					$("#email").val(data.email);
					$("#userName").val(data.applicantName);
					$("#resumeCode").val(data.resumeCode);
					$("#hrSourceType2Name").val(data.sourceB);
					// �Ա�
					$('select[name="interview[sexy]"] option').each(function () {
						if ($(this).val() == data.sex) {
							$(this).attr("selected", "selected");
						}
					});

					// ְλ����
					$('select[name="interview[postType]"] option').each(function () {
						if ($(this).val() == data.post) {
							$(this).attr("selected", "selected");
						}
					});

					// ������Դ
					$('select[name="interview[hrSourceType1]"] option').each(function () {
						if ($(this).val() == data.sourceA) {
							$(this).attr("selected", "selected");
						}
					});
				}
			},

			// ��������
			searchitems: [{
				display: 'ӦƸ������',
				name: 'applicantName'
			}]
		}
	});

	//�����ڲ��Ƽ�
	$("#recommendCode").yxcombogrid_recommend({
		hiddenId: 'recommendId'
	});

	//��ѡ��Ա�ȼ�
	$("#personLevel").yxcombogrid_personlevel({
		hiddenId: 'personLevelId',
		width: 340,
		gridOptions: {
			showcheckbox: false,
			param: {
				status: 0
			}
		}
	});

	//���Խ��
	$('select[name="interview[useInterviewResult]"] option').each(function () {
		if ($(this).val() == $("#useInterviewResultSelect").val()) {
			$(this).attr("selected", "selected");
		}
	});

	//��˾
	$('select[name="interview[sysCompanyId]"] option').each(function () {
		if ($(this).val() == $("#sysCompanyIdSelect").val()) {
			$(this).attr("selected", "selected");
		}
	});

	//����
	$('select[name="interview[useAreaId]"] option').each(function () {
		if ($(this).val() == $("#useAreaIdSelect").val()) {
			$(this).attr("selected", "selected");
		}
	});

	//�칫���������豸����
	$('select[name="interview[useDemandEqu]"] option').each(function () {
		if ($(this).val() == $("#useDemandEquSelect").val()) {
			$(this).attr("selected", "selected");
		}
	});

	//��Ŀ����
	$('select[name="interview[projectType]"] option').each(function () {
		if ($(this).val() == $("#projectTypeSelect").val()) {
			$(this).attr("selected", "selected");
		}
	});

	$("#projectType").bind('change', function () { // ��Ŀ����
		$("#projectGroup").val("");
		$("#projectGroupId").val("");
		$("#projectCode").val("");
		$("#projectGroup").yxcombogrid_esmproject("remove");
		$("#projectGroup").yxcombogrid_rdprojectfordl("remove");
		if ($(this).val() == "GCXM") {
			isProject();
		} else if ($(this).val() == "YFXM") { //�з�������Ŀ
			isResearch();
		}
	});

	$("#useJobName").yxcombogrid_jobs({
		hiddenId: 'useJobId',
		width: 280
	});

	$("#socialPlace").mouseover(function () {
		$.validationEngine.buildPrompt(this, "�����������麣�����Ϻ�,������Դ��ͨ�������ڱ��������ݱ���������ڹ���", null);
	});

	$("#socialPlace").mouseout(function () {
		$.validationEngine.closePrompt(this, false);
	});

	$("#socialPlace").yxcombogrid_socialplace({
		hiddenId: 'socialPlaceId',
		width: 350
	});

	$("#applyCode").yxcombogrid_interview({
		isDown: false,
		hiddenId: 'applyId',
		width: 500,
		nameCol: 'employmentCode',
		isFocusoutCheck: false,
		gridOptions: {
			event: {
				row_dblclick: function (e, row, data) {
					$("#applyCode").val(data.employmentCode);
				}
			},
			showcheckbox: false,
			// ��������
			searchitems: [{
				display: 'ӦƸ������',
				name: 'name'
			}]
		}
	});

	//������Ա����
	$("#parentCode").yxcombogrid_interviewparent({
		isDown: false,
		hiddenId: 'parentId',
		nameCol: 'formCode',
		isFocusoutCheck: false,
		gridOptions: {
			event: {
				row_dblclick: function (e, row, data) {
					$("#parentCode").val(data.formCode);
					$("#parentId").val(data.id);
					//��Ա��������
					$('#addTypeCode option').each(function () {
						if ($(this).val() == data.addTypeCode) {
							$(this).attr('selected', 'selected');
							return false; //�˳�ѭ��
						}
					});
					//��ʦ
					$("#tutor").val(data.tutor);
					$("#tutorId").val(data.tutorId);
					//��������
					$('#useDemandEqu option').each(function () {
						if ($(this).val() == data.computerConfiguration) {
							$(this).attr('selected', 'selected');
							return false; //�˳�ѭ��
						}
					});
				}
			}
		}
	});

	$("#useHireType").change(function () {
		if ($(this).val() == 'HRLYXX-03') {
			isIntern();
		} else {
			noIntern();
		}
		positionLevelChange();
	});

	//�Ͷ���ͬ��Ϣ
	$("#probation").blur(function () {
		laborContract($(this));
	});
	$("#contractYear").blur(function () {
		laborContract($(this));
	});

	//ְλ����
	$("#postType").change(function () {
		if ($(this).val() == 'YPZW-WY') { //�����������
			initLevelWY();
		} else {
			var options = '<option value="">...��ѡ��...</option> <option value="1">����</option><option value="2">�м�</option><option value="3">�߼�</option>';
			var positionLevel = $("#positionLevel");
			positionLevel.empty();
			positionLevel.html(options);
			$("#subsidyTr4").hide();
			$("#positionLevel").trigger("change").unbind("change"); //���ż������¼�
		}
	});

	// �ͳ���ֻ�����롰0��330��440������ֵ
	$('#mealCarSubsidy').bind('blur.checkVal', function () {
		if ($.trim($(this).val()) != '') {
			var num = $(this).val();
			if (num != 0 && num != 330 && num != 440) {
				alert('�ͳ���ֻ������0��330��440');
				$(this).val(0).focus();
			}
		}
	});
	$('#mealCarSubsidyFormal').bind('blur.checkVal', function () {
		if ($.trim($(this).val()) != '') {
			var num = $(this).val();
			if (num != 0 && num != 330 && num != 440) {
				alert('�ͳ���ֻ������0��330��440');
				$(this).val(0).focus();
			}
		}
	});

	// �����λ=>���ѣ����ޡ�=>50�����鳤��=>100����������Ŀ����=>120���м���Ŀ����=>150���߼���Ŀ����=>200
	$('#controlPostCode').change(function () {
		switch ($(this).val()) {
		case 'HRGLGW-01':
			$('#phoneSubsidy').val(50);
			$('#phoneSubsidyFormal').val(50);
			break;
		case 'HRGLGW-02':
			$('#phoneSubsidy').val(100);
			$('#phoneSubsidyFormal').val(100);
			break;
		case 'HRGLGW-03':
			$('#phoneSubsidy').val(120);
			$('#phoneSubsidyFormal').val(120);
			break;
		case 'HRGLGW-04':
			$('#phoneSubsidy').val(150);
			$('#phoneSubsidyFormal').val(150);
			break;
		case 'HRGLGW-05':
			$('#phoneSubsidy').val(200);
			$('#phoneSubsidyFormal').val(200);
			break;
		default:
			$('#phoneSubsidy').val('');
			$('#phoneSubsidyFormal').val('');
			break;
		}
		$('#phoneSubsidy').trigger('blur'); // ���������ܶ��¼�
	});

	//��֤��Ϣ
	validate({
		"userName": {
			required: true
		},
		"deptName": {
			required: true
		},
		"sexy": {
			required: true
		},
		"positionsName": {
			required: true
		},
		"phone": {
			required: true
		},
		"email": {
			required: true
		},
		"province": {
			required: true
		},
		"city": {
			required: true
		},
		"parentCode": {
			required: true
		},
		"positionLevel": {
			required: true
		},
		"postType": {
			required: true
		},
		"useInterviewResult": {
			required: true
		},
		"useHireType": {
			required: true
		},
		"sysCompanyId": {
			required: true
		},
		"useAreaId": {
			required: true
		},
		"useTrialWage": {
			required: true
		},
		"useFormalWage": {
			required: true
		},
		"useDemandEqu": {
			required: true
		},
		"hrInterviewResult": {
			required: true
		},
		"probation": {
			required: true
		},
		"contractYear": {
			required: true
		},
		"hrSourceType1": {
			required: true
		},
		"hrJobName": {
			required: true
		},
		"entryDate": {
			required: true
		},
		"useSign": {
			required: true
		},
		"useManager": {
			required: true
		},
		"tutor": {
			required: true
		},
		"manager": {
			required: true
		},
		"personLevel": {
			required: true
		},
		"wageLevelCode": {
			required: true
		},
		"phoneSubsidy": {
			required: true
		},
		"phoneSubsidyFormal": {
			required: true
		},
		"mealCarSubsidy": {
			required: true
		},
		"mealCarSubsidyFormal": {
			required: true
		}
	});

});

//ӦƸְλ
function positionsGrid(id) {
	$("#positionsName").yxcombogrid_jobs({
		hiddenId: 'positionsId',
		width: 280,
		gridOptions: {
			param: {
				deptId: id
			}
		}
	});
}

//¼��ְλ
function hrJobNameGrid(id) {
	$("#hrJobName").yxcombogrid_jobs({
		hiddenId: 'hrJobId',
		width: 280,
		gridOptions: {
			param: {
				deptId: id
			}
		}
	});
}

function getRadio() {
	if ($("#hrRequire5").length > 0) {
		$("#hrRequire5").remove();
		return;
	}
	var input = document.createElement("input");
	input.type = "text";
	input.id = "hrRequire5";
	input.name = "interview[computerConfiguration]";
	document.getElementById("setAttr").appendChild(input);
}

function closeRadio() {
	if ($("#hrRequire5").length > 0) {
		$("#hrRequire5").hide();
		$("#hrRequire5").val("");
		return;
	}
}

//ѡ����������ְλʱ�����������ֵ�����
function initLevelWY() {
	var data = $.ajax({
		url: '?model=hr_basicinfo_level&action=listJson&sort=personLevel&dir=ASC&status=0',
		type: 'post',
		dataType: 'json',
		async: false
	}).responseText;
	data = eval("(" + data + ")");

	var positionLevel = $("#positionLevel");
	positionLevel.empty();
	for (i = 0; i < data.length; i++) {
		var options = $("<option></option>");
		options.text(data[i].personLevel);
		options.val(data[i].id);
		options.appendTo(positionLevel);
	}

	//���ż������¼�
	$("#subsidyTr4").show();
	$("#positionLevel").unbind("change").change(positionLevelChange).trigger("change");
}

//�ύУ������
function checkData() {
	if ($("#compensation").val() == '����д����ҵ����Э�顷������׼' || $("#useSign").val() != '��') {
		$("#compensation").val("");
	}

	//���������ܶ��¼�
	$("#tripSubsidy").trigger('blur');
	$("#tripSubsidyFormal").trigger('blur');
	$("#workBonus").trigger('blur');
	$("#workBonusFormal").trigger('blur');

	//��������Ϣ
	//����
	if ($("#phoneSubsidy").is(':hidden')) {
		$("#phoneSubsidy").val("");
	}
	if ($("#tripSubsidy").is(':hidden')) {
		$("#tripSubsidy").val("");
	}
	if ($("#manageSubsidy").is(':hidden')) {
		$("#manageSubsidy").val("");
	}
	if ($("#accommodSubsidy").is(':hidden')) {
		$("#accommodSubsidy").val("");
	}
	if ($("#computerSubsidy").is(':hidden')) {
		$("#computerSubsidy").val("");
	}
	if ($("#bonusLimit").is(':hidden')) {
		$("#bonusLimit").val("");
	}
	if ($("#otherSubsidy").is(':hidden')) {
		$("#otherSubsidy").val("");
	}
	if ($("#workBonus").is(':hidden')) {
		$("#workBonus").val("");
	}
	if ($("#mealCarSubsidy").is(':hidden')) {
		$("#mealCarSubsidy").val("");
	}

	//ת��
	if ($("#phoneSubsidyFormal").is(':hidden')) {
		$("#phoneSubsidyFormal").val("");
	}
	if ($("#tripSubsidyFormal").is(':hidden')) {
		$("#tripSubsidyFormal").val("");
	}
	if ($("#manageSubsidyFormal").is(':hidden')) {
		$("#manageSubsidyFormal").val("");
	}
	if ($("#accommodSubsidyFormal").is(':hidden')) {
		$("#accommodSubsidyFormal").val("");
	}
	if ($("#computerSubsidyFormal").is(':hidden')) {
		$("#computerSubsidyFormal").val("");
	}
	if ($("#bonusLimitFormal").is(':hidden')) {
		$("#bonusLimitFormal").val("");
	}
	if ($("#otherSubsidyFormal").is(':hidden')) {
		$("#otherSubsidyFormal").val("");
	}
	if ($("#workBonusFormal").is(':hidden')) {
		$("#workBonusFormal").val("");
	}
	if ($("#mealCarSubsidyFormal").is(':hidden')) {
		$("#mealCarSubsidyFormal").val("");
	}
	if ($("#allTrialWage").is(':hidden')) {
		$("#allTrialWage").val("");
	}
	if ($("#allFormalWage").is(':hidden')) {
		$("#allFormalWage").val("");
	}

	//ʵϰ��
	if ($("#useHireType").val() == 'HRLYXX-03') {
		$("#probation").val("");
		$("#contractYear").val("");
		$("#socialPlace").val("");
		$("#hrIsMatch").val("");
	} else {
		$("#internshipSalaryType").val("");
		$("#internshipSalary").val("");
		$("#eatCarSubsidy").val("");
		$("#computerIntern").val("");
		$("#allInternship").val("");
	}

	if ($("#isLocal").val() == '��' && ($("#levelSubsidy").val() == '' || $("#areaSubsidy").val() == '')
			&& ($("#positionLevel option:selected").text()).indexOf('B') == 0) {
		alert("����д������������������");
		return false;
	} else {
		return true;
	}
}

//��ʵϰ��
function isIntern() {
	// ʵϰн��
	$("#internshipSalaryType").addClass("validate[required]").bind('change.caluate' ,caculateTrainee).parent().show().prev().show(); //ʵϰ��������
	$("#internshipSalary_v").addClass("validate[required]").bind('blur.caluate' ,caculateTrainee); //ʵϰ����
	$("#eatCarSubsidy_v").addClass("validate[required]").bind('blur.caluate' ,caculateTrainee).parent().show().prev().show(); //�ͳ���
	$("#computerIntern_v").addClass("validate[required]").bind('blur.caluate' ,caculateTrainee).parent().show().prev().show(); //���Բ���
	$("#allInternship").addClass("validate[required]").parent().show().prev().show(); //�ܶ�Ԥ��

	$("#useTrialWage").removeClass("validate[required]").parent().hide().prev().hide(); //�����ڹ���
	$("#useFormalWage").removeClass("validate[required]").parent().hide().prev().hide(); //ת������
	$("#phoneSubsidy").removeClass("validate[required]").parent().hide().prev().hide(); //�����ڵ绰����
	$("#phoneSubsidyFormal").removeClass("validate[required]"); //ת���绰����
	$("#mealCarSubsidy").removeClass("validate[required]").parent().hide().prev().hide(); //�����ڲͳ���
	$("#mealCarSubsidyFormal").removeClass("validate[required]"); //ת���ͳ���
	$("#hrInterviewResult").removeClass("validate[required]").parent().hide().prev().hide().parent().hide(); //��������
	$("#probation").removeClass("validate[required]").parent().parent().hide(); //�Ͷ���ͬ��Ϣ ������
	$("#contractYear").removeClass("validate[required]"); //�Ͷ���ͬ��Ϣ ��ͬ����
	$("#socialPlace").removeClass("validate[required]").val('������'); //�籣�����
	$("#socialPlaceId").val('9');
}

//����ʵϰ��
function noIntern() {
	// н�ʽṹ
	$("#internshipSalaryType").removeClass("validate[required]").unbind('change.caluate').parent().hide().prev().hide(); //ʵϰ��������
	$("#internshipSalary_v").removeClass("validate[required]").unbind('blur.caluate'); //ʵϰ����
	$("#eatCarSubsidy_v").removeClass("validate[required]").unbind('blur.caluate').parent().hide().prev().hide(); //�ͳ���
	$("#computerIntern_v").removeClass("validate[required]").unbind('blur.caluate').parent().hide().prev().hide(); //���Բ���
	$("#allInternship").removeClass("validate[required]").parent().hide().prev().hide(); //�ܶ�Ԥ��

	$("#useTrialWage").addClass("validate[required]").parent().show().prev().show(); //�����ڹ���
	$("#useFormalWage").addClass("validate[required]").parent().show().prev().show(); //ת������
	$("#phoneSubsidy").addClass("validate[required]").parent().show().prev().show(); //�����ڵ绰����
	$("#phoneSubsidyFormal").addClass("validate[required]"); //ת���绰����
	if ($('#postType').val() != 'YPZW-WY') { // ������
		$("#mealCarSubsidy").addClass("validate[required]").parent().show().prev().show(); //�����ڲͳ���
		$("#mealCarSubsidyFormal").addClass("validate[required]"); //ת���ͳ���
	}
	$("#hrInterviewResult").addClass("validate[required]").parent().show().prev().show().parent().show(); //��������
	$("#probation").addClass("validate[required]").parent().parent().show(); //�Ͷ���ͬ��Ϣ ������
	$("#probation").val($("#probation").val() != 0 ? $("#probation").val() : 3);
	$("#contractYear").addClass("validate[required]"); //�Ͷ���ͬ��Ϣ ��ͬ����
	$("#contractYear").val($("#contractYear").val() != 0 ? $("#contractYear").val() : 3);
	$("#socialPlace").addClass("validate[required]"); //�籣�����
	if ($("#socialPlace").val() == '' || $("#socialPlace").val() == '������') {
		$("#socialPlace").val('�麣');
		$("#socialPlaceId").val('8');
	}
}

// ʵϰ����н���ܶ����(ʵϰ��н���ܶ����=ʵϰ�ڣ��չ���*30 | �¹��ʣ�+���Բ���+�ͳ���)
function caculateTrainee() {
	var traineeNum = 1; // ����ϵ��
	if ($('#internshipSalaryType').val() == '�չ���') {
		traineeNum = 30;
	}
	var accAddArr = [
		accMul($("#internshipSalary_v").val(), traineeNum),
		$("#eatCarSubsidy_v").val(),
		$("#computerIntern_v").val()
	];
	$('#allInternship').val(accAddMore(accAddArr, 2));
}

//������Ŀ
function isProject() {
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
}

//�з���Ŀ
function isResearch() {
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

//�蹫������Ϊ����
function addSubsidyRequired() {
	$("#computerSubsidy").addClass("validate[required]");
	$("#computerSubsidyFormal").addClass("validate[required]");
	$("#allTrialWage").addClass("validate[required]");
	$("#allFormalWage").addClass("validate[required]");
}

//�Ƴ�����Ϊ����
function removeSubsidyRequired() {
	$("#tripSubsidy").removeClass("validate[required]");
	$("#workBonus").removeClass("validate[required]");
	$("#computerSubsidy").removeClass("validate[required]");
	$("#manageSubsidy").removeClass("validate[required]");
	$("#accommodSubsidy").removeClass("validate[required]");
	$("#bonusLimit").removeClass("validate[required]");
	$("#otherSubsidy").removeClass("validate[required]");

	$("#tripSubsidyFormal").removeClass("validate[required]");
	$("#workBonusFormal").removeClass("validate[required]");
	$("#computerSubsidyFormal").removeClass("validate[required]");
	$("#manageSubsidyFormal").removeClass("validate[required]");
	$("#accommodSubsidyFormal").removeClass("validate[required]");
	$("#bonusLimitFormal").removeClass("validate[required]");
	$("#otherSubsidyFormal").removeClass("validate[required]");

	$("#controlPostCode").removeClass("validate[required]");

	$("#allTrialWage").removeClass("validate[required]");
	$("#allFormalWage").removeClass("validate[required]");
}

//���ż������¼�(н�ʽṹ)
function positionLevelChange() {

	if ($('#postType').val() != 'YPZW-WY') { // ������
		$("#mealCarSubsidy").addClass("validate[required]").parent().show().prev().show(); //�����ڲͳ���
		$("#mealCarSubsidyFormal").addClass("validate[required]"); //ת���ͳ���
	} else {
		$("#mealCarSubsidy").removeClass("validate[required]").parent().hide().prev().hide(); //�����ڲͳ���
		$("#mealCarSubsidyFormal").removeClass("validate[required]"); //ת���ͳ���
	}

	if ($("#useHireType").val() != 'HRLYXX-03' && $("#useHireType").val() != '') { //��ʵϰ��
		noIntern();

		var personClass = $("#positionLevel option:selected").text(); //��Ա���
		$("#subsidyTr1").show();
		$("#subsidyTr2").show();
		$("#subsidyTr3").show();
		// $("#subsidyTr4").show();
		$("#levelSubsidy").parent().hide().prev().hide();
		$("#areaSubsidy").parent().hide().prev().hide();
		if (personClass.indexOf('A') == 0 || !isNaN(personClass.substring(0, 1))) { //A��
			$("#tripSubsidy").addClass("validate[required]").parent().show().prev().show();
			$("#tripSubsidyFormal").addClass("validate[required]");
			$("#workBonus").removeClass("validate[required]").parent().hide().prev().hide();
			$("#workBonusFormal").removeClass("validate[required]");
			addSubsidyRequired();
			$("#manageSubsidy").addClass("validate[required]");
			$("#manageSubsidyFormal").addClass("validate[required]");
			$("#accommodSubsidy").addClass("validate[required]");
			$("#accommodSubsidyFormal").addClass("validate[required]");
			$("#bonusLimit").addClass("validate[required]");
			$("#bonusLimitFormal").addClass("validate[required]");
			$("#otherSubsidy").addClass("validate[required]").parent().show().prev().show();
			$("#otherSubsidyFormal").addClass("validate[required]");
			$('#controlPostCode').addClass("validate[required]");

			//�����ܶ�
			bindAllTrialWage();
			$("#tripSubsidy").trigger('blur');
			$("#tripSubsidyFormal").trigger('blur');

		} else if (personClass.indexOf('B') == 0) { //B��
			$("#tripSubsidy").removeClass("validate[required]").parent().hide().prev().hide();
			$("#tripSubsidyFormal").removeClass("validate[required]");
			$("#workBonus").addClass("validate[required]").parent().show().prev().show();
			$("#workBonusFormal").addClass("validate[required]");
			$('#controlPostCode').addClass("validate[required]");
			$("#subsidyTr2").hide();
			addSubsidyRequired();
			$("#manageSubsidy").removeClass("validate[required]");
			$("#manageSubsidyFormal").removeClass("validate[required]");
			$("#accommodSubsidy").removeClass("validate[required]");
			$("#accommodSubsidyFormal").removeClass("validate[required]");
			$("#bonusLimit").removeClass("validate[required]");
			$("#bonusLimitFormal").removeClass("validate[required]");
			$("#otherSubsidy").removeClass("validate[required]").parent().hide().prev().hide();
			$("#otherSubsidyFormal").removeClass("validate[required]");
			$("#levelSubsidy").parent().show().prev().show();
			$("#areaSubsidy").parent().show().prev().show();

			//�����ܶ��д�깤������
			bindAllFormalWage();
			$("#workBonus").trigger('blur');
			$("#workBonusFormal").trigger('blur');

		} else {
			$("#subsidyTr1").hide();
			$("#subsidyTr2").hide();
			$("#subsidyTr3").hide();
			removeSubsidyRequired();
			$("#accommodSubsidy").unbind('blur');
			$("#accommodSubsidyFormal").unbind('blur');
			$("#workBonus").unbind('blur');
			$("#workBonusFormal").unbind('blur');
			if ($('#postType').val() == 'YPZW-WY') {
				$('#controlPostCode').addClass("validate[required]");
			}
		}
	} else {
		isIntern();
		$("#subsidyTr1").hide();
		$("#subsidyTr2").hide();
		$("#subsidyTr3").hide();
		$("#subsidyTr4").hide();
		$("#levelSubsidy").parent().hide().prev().hide();
		$("#areaSubsidy").parent().hide().prev().hide();
		removeSubsidyRequired();
		$("#accommodSubsidy").unbind('blur');
		$("#accommodSubsidyFormal").unbind('blur');
		$("#workBonus").unbind('blur');
		$("#workBonusFormal").unbind('blur');
	}
}

//����A��Ԥ���ܶ�
function countAllTrialWage() {
	//������
	var commonSubsidy = parseFloat($("#tripSubsidy").val() ? $("#tripSubsidy").val() : 0)
		+ parseFloat($("#phoneSubsidy").val() ? $("#phoneSubsidy").val() : 0)
		+ parseFloat($("#computerSubsidy").val() ? $("#computerSubsidy").val() : 0)
		+ parseFloat($("#manageSubsidy").val() ? $("#manageSubsidy").val() : 0)
		+ parseFloat($("#accommodSubsidy").val() ? $("#accommodSubsidy").val() : 0)
		+ parseFloat($("#bonusLimit").val() ? $("#bonusLimit").val() : 0)
		+ parseFloat($("#otherSubsidy").val() ? $("#otherSubsidy").val() : 0);
	$("#allTrialWage").val(parseFloat($("#useTrialWage").val() ? $("#useTrialWage").val() : 0) + commonSubsidy).trigger('blur');

	//ת��
	var commonSubsidyFormal = parseFloat($("#tripSubsidyFormal").val() ? $("#tripSubsidyFormal").val() : 0)
		+ parseFloat($("#phoneSubsidyFormal").val() ? $("#phoneSubsidyFormal").val() : 0)
		+ parseFloat($("#computerSubsidyFormal").val() ? $("#computerSubsidyFormal").val() : 0)
		+ parseFloat($("#manageSubsidyFormal").val() ? $("#manageSubsidyFormal").val() : 0)
		+ parseFloat($("#accommodSubsidyFormal").val() ? $("#accommodSubsidyFormal").val() : 0)
		+ parseFloat($("#bonusLimitFormal").val() ? $("#bonusLimitFormal").val() : 0)
		+ parseFloat($("#otherSubsidyFormal").val() ? $("#otherSubsidyFormal").val() : 0);
	$("#allFormalWage").val(parseFloat($("#useFormalWage").val() ? $("#useFormalWage").val() : 0) + commonSubsidyFormal).trigger('blur');
}

//�󶨼���A���ܶ��¼�
function bindAllTrialWage() {
	//������
	$("#tripSubsidy").blur(countAllTrialWage);
	$("#phoneSubsidy").blur(countAllTrialWage);
	$("#computerSubsidy").blur(countAllTrialWage);
	$("#manageSubsidy").blur(countAllTrialWage);
	$("#accommodSubsidy").blur(countAllTrialWage);
	$("#useTrialWage").blur(countAllTrialWage);
	$("#bonusLimit").blur(countAllTrialWage);
	$("#otherSubsidy").blur(countAllTrialWage);

	//ת��
	$("#tripSubsidyFormal").blur(countAllTrialWage);
	$("#phoneSubsidyFormal").blur(countAllTrialWage);
	$("#computerSubsidyFormal").blur(countAllTrialWage);
	$("#manageSubsidyFormal").blur(countAllTrialWage);
	$("#accommodSubsidyFormal").blur(countAllTrialWage);
	$("#useFormalWage").blur(countAllTrialWage);
	$("#bonusLimitFormal").blur(countAllTrialWage);
	$("#otherSubsidyFormal").blur(countAllTrialWage);
}

//����B����ܶ�
function countAllFormalWage() {
	//������
	var commonSubsidy = parseFloat($("#workBonus").val() ? $("#workBonus").val() : 0)
		+ parseFloat($("#phoneSubsidy").val() ? $("#phoneSubsidy").val() : 0)
		+ parseFloat($("#computerSubsidy").val() ? $("#computerSubsidy").val() : 0)
		+ parseFloat($("#levelSubsidy").val() ? $("#levelSubsidy").val() : 0)
		+ parseFloat($("#areaSubsidy").val() ? $("#areaSubsidy").val() : 0);
	$("#allTrialWage").val(parseFloat($("#useTrialWage").val() ? $("#useTrialWage").val() : 0) + commonSubsidy).trigger('blur');

	//ת��
	var commonSubsidyFormal = parseFloat($("#workBonusFormal").val() ? $("#workBonusFormal").val() : 0)
		+ parseFloat($("#phoneSubsidyFormal").val() ? $("#phoneSubsidyFormal").val() : 0)
		+ parseFloat($("#computerSubsidyFormal").val() ? $("#computerSubsidyFormal").val() : 0)
		+ parseFloat($("#levelSubsidyFormal").val() ? $("#levelSubsidyFormal").val() : 0)
		+ parseFloat($("#areaSubsidyFormal").val() ? $("#areaSubsidyFormal").val() : 0);
	$("#allFormalWage").val(parseFloat($("#useFormalWage").val() ? $("#useFormalWage").val() : 0) + commonSubsidyFormal).trigger('blur');
}

//�󶨼���B���ܶ��¼�
function bindAllFormalWage() {
	//������
	$("#workBonus").blur(countAllFormalWage);
	$("#phoneSubsidy").blur(countAllFormalWage);
	$("#computerSubsidy").blur(countAllFormalWage);
	$("#levelSubsidy").blur(countAllFormalWage);
	$("#areaSubsidy").blur(countAllFormalWage);
	$("#useTrialWage").blur(countAllFormalWage);

	//ת��
	$("#workBonusFormal").blur(countAllFormalWage);
	$("#phoneSubsidyFormal").blur(countAllFormalWage);
	$("#computerSubsidyFormal").blur(countAllFormalWage);
	$("#levelSubsidyFormal").blur(countAllFormalWage);
	$("#areaSubsidyFormal").blur(countAllFormalWage);
	$("#useFormalWage").blur(countAllFormalWage);
}