
$(document).ready(function () {
	$("#positionsName").attr("readonly", true);
	$("#positionsName").click(function () {
		if ($("#deptId").val() == "") {
			alert("请选择用人部门");
			$(this).val("");
		}
	});

	$("#hrJobName").attr("readonly", true);
	$("#hrJobName").click(function () {
		if ($("#deptId").val() == "") {
			alert("请选择用人部门");
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

	//部门信息
	$("#deptName").dblclick(function () {
		$("#positionsName").val("");
		$("#positionsId").val("");
		$("#positionsName").yxcombogrid_jobs("remove");
	});

	$("#deptName").yxselect_dept({
		hiddenId: 'deptId',
		event: {
			selectReturn: function (e, row) {
				//实习生部门
				if (row.dept.id == '155') {
					$("#useHireType").val('HRLYXX-03').trigger('change').unbind('change').change(function () {
						alert('不能修改录用形式！');
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

				positionLevelChange(); //网优的补助事件
			}
		}
	});

	$("#compensation").hide();
	var hintArr = '请填写《竞业限制协议》补偿标准';
	//选择签订《竞业限制协议》
	$("#useSign").change(function () {
		if ($(this).val() == '是') {
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

	//关联简历
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
					// 性别
					$('select[name="interview[sexy]"] option').each(function () {
						if ($(this).val() == data.sex) {
							$(this).attr("selected", "selected");
						}
					});

					// 职位类型
					$('select[name="interview[postType]"] option').each(function () {
						if ($(this).val() == data.post) {
							$(this).attr("selected", "selected");
						}
					});

					// 简历来源
					$('select[name="interview[hrSourceType1]"] option').each(function () {
						if ($(this).val() == data.sourceA) {
							$(this).attr("selected", "selected");
						}
					});
				}
			},

			// 快速搜索
			searchitems: [{
				display: '应聘者姓名',
				name: 'applicantName'
			}]
		}
	});

	//关联内部推荐
	$("#recommendCode").yxcombogrid_recommend({
		hiddenId: 'recommendId'
	});

	//单选人员等级
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

	//面试结果
	$('select[name="interview[useInterviewResult]"] option').each(function () {
		if ($(this).val() == $("#useInterviewResultSelect").val()) {
			$(this).attr("selected", "selected");
		}
	});

	//公司
	$('select[name="interview[sysCompanyId]"] option').each(function () {
		if ($(this).val() == $("#sysCompanyIdSelect").val()) {
			$(this).attr("selected", "selected");
		}
	});

	//区域
	$('select[name="interview[useAreaId]"] option').each(function () {
		if ($(this).val() == $("#useAreaIdSelect").val()) {
			$(this).attr("selected", "selected");
		}
	});

	//办公电脑需求设备类型
	$('select[name="interview[useDemandEqu]"] option').each(function () {
		if ($(this).val() == $("#useDemandEquSelect").val()) {
			$(this).attr("selected", "selected");
		}
	});

	//项目类型
	$('select[name="interview[projectType]"] option').each(function () {
		if ($(this).val() == $("#projectTypeSelect").val()) {
			$(this).attr("selected", "selected");
		}
	});

	$("#projectType").bind('change', function () { // 项目类型
		$("#projectGroup").val("");
		$("#projectGroupId").val("");
		$("#projectCode").val("");
		$("#projectGroup").yxcombogrid_esmproject("remove");
		$("#projectGroup").yxcombogrid_rdprojectfordl("remove");
		if ($(this).val() == "GCXM") {
			isProject();
		} else if ($(this).val() == "YFXM") { //研发类型项目
			isResearch();
		}
	});

	$("#useJobName").yxcombogrid_jobs({
		hiddenId: 'useJobId',
		width: 280
	});

	$("#socialPlace").mouseover(function () {
		$.validationEngine.buildPrompt(this, "尽量购买在珠海或者上海,北京世源信通编制买在北京、广州贝软编制买在广州", null);
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
			// 快速搜索
			searchitems: [{
				display: '应聘者姓名',
				name: 'name'
			}]
		}
	});

	//关联增员申请
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
					//增员申请类型
					$('#addTypeCode option').each(function () {
						if ($(this).val() == data.addTypeCode) {
							$(this).attr('selected', 'selected');
							return false; //退出循环
						}
					});
					//导师
					$("#tutor").val(data.tutor);
					$("#tutorId").val(data.tutorId);
					//电脑配置
					$('#useDemandEqu option').each(function () {
						if ($(this).val() == data.computerConfiguration) {
							$(this).attr('selected', 'selected');
							return false; //退出循环
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

	//劳动合同信息
	$("#probation").blur(function () {
		laborContract($(this));
	});
	$("#contractYear").blur(function () {
		laborContract($(this));
	});

	//职位类型
	$("#postType").change(function () {
		if ($(this).val() == 'YPZW-WY') { //如果等于网优
			initLevelWY();
		} else {
			var options = '<option value="">...请选择...</option> <option value="1">初级</option><option value="2">中级</option><option value="3">高级</option>';
			var positionLevel = $("#positionLevel");
			positionLevel.empty();
			positionLevel.html(options);
			$("#subsidyTr4").hide();
			$("#positionLevel").trigger("change").unbind("change"); //网优级别补助事件
		}
	});

	// 餐车补只能输入“0、330、440”三个值
	$('#mealCarSubsidy').bind('blur.checkVal', function () {
		if ($.trim($(this).val()) != '') {
			var num = $(this).val();
			if (num != 0 && num != 330 && num != 440) {
				alert('餐车补只能输入0、330、440');
				$(this).val(0).focus();
			}
		}
	});
	$('#mealCarSubsidyFormal').bind('blur.checkVal', function () {
		if ($.trim($(this).val()) != '') {
			var num = $(this).val();
			if (num != 0 && num != 330 && num != 440) {
				alert('餐车补只能输入0、330、440');
				$(this).val(0).focus();
			}
		}
	});

	// 管理岗位=>话费，“无”=>50、“组长”=>100、“初级项目经理”=>120、中级项目经理“=>150、高级项目经理”=>200
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
		$('#phoneSubsidy').trigger('blur'); // 触发计算总额事件
	});

	//验证信息
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

//应聘职位
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

//录用职位
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

//选择网优类型职位时，加载数据字典内容
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

	//网优级别补助事件
	$("#subsidyTr4").show();
	$("#positionLevel").unbind("change").change(positionLevelChange).trigger("change");
}

//提交校验数据
function checkData() {
	if ($("#compensation").val() == '请填写《竞业限制协议》补偿标准' || $("#useSign").val() != '是') {
		$("#compensation").val("");
	}

	//触发计算总额事件
	$("#tripSubsidy").trigger('blur');
	$("#tripSubsidyFormal").trigger('blur');
	$("#workBonus").trigger('blur');
	$("#workBonusFormal").trigger('blur');

	//处理补助信息
	//试用
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

	//转正
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

	//实习生
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

	if ($("#isLocal").val() == '是' && ($("#levelSubsidy").val() == '' || $("#areaSubsidy").val() == '')
			&& ($("#positionLevel option:selected").text()).indexOf('B') == 0) {
		alert("请填写技术级别补助和区域补助");
		return false;
	} else {
		return true;
	}
}

//是实习生
function isIntern() {
	// 实习薪资
	$("#internshipSalaryType").addClass("validate[required]").bind('change.caluate' ,caculateTrainee).parent().show().prev().show(); //实习工资类型
	$("#internshipSalary_v").addClass("validate[required]").bind('blur.caluate' ,caculateTrainee); //实习工资
	$("#eatCarSubsidy_v").addClass("validate[required]").bind('blur.caluate' ,caculateTrainee).parent().show().prev().show(); //餐车补
	$("#computerIntern_v").addClass("validate[required]").bind('blur.caluate' ,caculateTrainee).parent().show().prev().show(); //电脑补贴
	$("#allInternship").addClass("validate[required]").parent().show().prev().show(); //总额预计

	$("#useTrialWage").removeClass("validate[required]").parent().hide().prev().hide(); //试用期工资
	$("#useFormalWage").removeClass("validate[required]").parent().hide().prev().hide(); //转正工资
	$("#phoneSubsidy").removeClass("validate[required]").parent().hide().prev().hide(); //试用期电话补助
	$("#phoneSubsidyFormal").removeClass("validate[required]"); //转正电话补助
	$("#mealCarSubsidy").removeClass("validate[required]").parent().hide().prev().hide(); //试用期餐车补
	$("#mealCarSubsidyFormal").removeClass("validate[required]"); //转正餐车补
	$("#hrInterviewResult").removeClass("validate[required]").parent().hide().prev().hide().parent().hide(); //总体评价
	$("#probation").removeClass("validate[required]").parent().parent().hide(); //劳动合同信息 试用期
	$("#contractYear").removeClass("validate[required]"); //劳动合同信息 合同期限
	$("#socialPlace").removeClass("validate[required]").val('不购买'); //社保购买地
	$("#socialPlaceId").val('9');
}

//不是实习生
function noIntern() {
	// 薪资结构
	$("#internshipSalaryType").removeClass("validate[required]").unbind('change.caluate').parent().hide().prev().hide(); //实习工资类型
	$("#internshipSalary_v").removeClass("validate[required]").unbind('blur.caluate'); //实习工资
	$("#eatCarSubsidy_v").removeClass("validate[required]").unbind('blur.caluate').parent().hide().prev().hide(); //餐车补
	$("#computerIntern_v").removeClass("validate[required]").unbind('blur.caluate').parent().hide().prev().hide(); //电脑补贴
	$("#allInternship").removeClass("validate[required]").parent().hide().prev().hide(); //总额预计

	$("#useTrialWage").addClass("validate[required]").parent().show().prev().show(); //试用期工资
	$("#useFormalWage").addClass("validate[required]").parent().show().prev().show(); //转正工资
	$("#phoneSubsidy").addClass("validate[required]").parent().show().prev().show(); //试用期电话补助
	$("#phoneSubsidyFormal").addClass("validate[required]"); //转正电话补助
	if ($('#postType').val() != 'YPZW-WY') { // 非网优
		$("#mealCarSubsidy").addClass("validate[required]").parent().show().prev().show(); //试用期餐车补
		$("#mealCarSubsidyFormal").addClass("validate[required]"); //转正餐车补
	}
	$("#hrInterviewResult").addClass("validate[required]").parent().show().prev().show().parent().show(); //总体评价
	$("#probation").addClass("validate[required]").parent().parent().show(); //劳动合同信息 试用期
	$("#probation").val($("#probation").val() != 0 ? $("#probation").val() : 3);
	$("#contractYear").addClass("validate[required]"); //劳动合同信息 合同期限
	$("#contractYear").val($("#contractYear").val() != 0 ? $("#contractYear").val() : 3);
	$("#socialPlace").addClass("validate[required]"); //社保购买地
	if ($("#socialPlace").val() == '' || $("#socialPlace").val() == '不购买') {
		$("#socialPlace").val('珠海');
		$("#socialPlaceId").val('8');
	}
}

// 实习生人薪资总额计算(实习期薪资总额计算=实习期（日工资*30 | 月工资）+电脑补贴+餐车补)
function caculateTrainee() {
	var traineeNum = 1; // 工资系数
	if ($('#internshipSalaryType').val() == '日工资') {
		traineeNum = 30;
	}
	var accAddArr = [
		accMul($("#internshipSalary_v").val(), traineeNum),
		$("#eatCarSubsidy_v").val(),
		$("#computerIntern_v").val()
	];
	$('#allInternship').val(accAddMore(accAddArr, 2));
}

//工程项目
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

//研发项目
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

//设公共补助为必填
function addSubsidyRequired() {
	$("#computerSubsidy").addClass("validate[required]");
	$("#computerSubsidyFormal").addClass("validate[required]");
	$("#allTrialWage").addClass("validate[required]");
	$("#allFormalWage").addClass("validate[required]");
}

//移除补助为必填
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

//网优级别补助事件(薪资结构)
function positionLevelChange() {

	if ($('#postType').val() != 'YPZW-WY') { // 非网优
		$("#mealCarSubsidy").addClass("validate[required]").parent().show().prev().show(); //试用期餐车补
		$("#mealCarSubsidyFormal").addClass("validate[required]"); //转正餐车补
	} else {
		$("#mealCarSubsidy").removeClass("validate[required]").parent().hide().prev().hide(); //试用期餐车补
		$("#mealCarSubsidyFormal").removeClass("validate[required]"); //转正餐车补
	}

	if ($("#useHireType").val() != 'HRLYXX-03' && $("#useHireType").val() != '') { //非实习生
		noIntern();

		var personClass = $("#positionLevel option:selected").text(); //人员类别
		$("#subsidyTr1").show();
		$("#subsidyTr2").show();
		$("#subsidyTr3").show();
		// $("#subsidyTr4").show();
		$("#levelSubsidy").parent().hide().prev().hide();
		$("#areaSubsidy").parent().hide().prev().hide();
		if (personClass.indexOf('A') == 0 || !isNaN(personClass.substring(0, 1))) { //A类
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

			//计算总额
			bindAllTrialWage();
			$("#tripSubsidy").trigger('blur');
			$("#tripSubsidyFormal").trigger('blur');

		} else if (personClass.indexOf('B') == 0) { //B类
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

			//计算总额（填写完工作奖金）
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

//计算A类预计总额
function countAllTrialWage() {
	//试用期
	var commonSubsidy = parseFloat($("#tripSubsidy").val() ? $("#tripSubsidy").val() : 0)
		+ parseFloat($("#phoneSubsidy").val() ? $("#phoneSubsidy").val() : 0)
		+ parseFloat($("#computerSubsidy").val() ? $("#computerSubsidy").val() : 0)
		+ parseFloat($("#manageSubsidy").val() ? $("#manageSubsidy").val() : 0)
		+ parseFloat($("#accommodSubsidy").val() ? $("#accommodSubsidy").val() : 0)
		+ parseFloat($("#bonusLimit").val() ? $("#bonusLimit").val() : 0)
		+ parseFloat($("#otherSubsidy").val() ? $("#otherSubsidy").val() : 0);
	$("#allTrialWage").val(parseFloat($("#useTrialWage").val() ? $("#useTrialWage").val() : 0) + commonSubsidy).trigger('blur');

	//转正
	var commonSubsidyFormal = parseFloat($("#tripSubsidyFormal").val() ? $("#tripSubsidyFormal").val() : 0)
		+ parseFloat($("#phoneSubsidyFormal").val() ? $("#phoneSubsidyFormal").val() : 0)
		+ parseFloat($("#computerSubsidyFormal").val() ? $("#computerSubsidyFormal").val() : 0)
		+ parseFloat($("#manageSubsidyFormal").val() ? $("#manageSubsidyFormal").val() : 0)
		+ parseFloat($("#accommodSubsidyFormal").val() ? $("#accommodSubsidyFormal").val() : 0)
		+ parseFloat($("#bonusLimitFormal").val() ? $("#bonusLimitFormal").val() : 0)
		+ parseFloat($("#otherSubsidyFormal").val() ? $("#otherSubsidyFormal").val() : 0);
	$("#allFormalWage").val(parseFloat($("#useFormalWage").val() ? $("#useFormalWage").val() : 0) + commonSubsidyFormal).trigger('blur');
}

//绑定计算A类总额事件
function bindAllTrialWage() {
	//试用期
	$("#tripSubsidy").blur(countAllTrialWage);
	$("#phoneSubsidy").blur(countAllTrialWage);
	$("#computerSubsidy").blur(countAllTrialWage);
	$("#manageSubsidy").blur(countAllTrialWage);
	$("#accommodSubsidy").blur(countAllTrialWage);
	$("#useTrialWage").blur(countAllTrialWage);
	$("#bonusLimit").blur(countAllTrialWage);
	$("#otherSubsidy").blur(countAllTrialWage);

	//转正
	$("#tripSubsidyFormal").blur(countAllTrialWage);
	$("#phoneSubsidyFormal").blur(countAllTrialWage);
	$("#computerSubsidyFormal").blur(countAllTrialWage);
	$("#manageSubsidyFormal").blur(countAllTrialWage);
	$("#accommodSubsidyFormal").blur(countAllTrialWage);
	$("#useFormalWage").blur(countAllTrialWage);
	$("#bonusLimitFormal").blur(countAllTrialWage);
	$("#otherSubsidyFormal").blur(countAllTrialWage);
}

//计算B类计总额
function countAllFormalWage() {
	//试用期
	var commonSubsidy = parseFloat($("#workBonus").val() ? $("#workBonus").val() : 0)
		+ parseFloat($("#phoneSubsidy").val() ? $("#phoneSubsidy").val() : 0)
		+ parseFloat($("#computerSubsidy").val() ? $("#computerSubsidy").val() : 0)
		+ parseFloat($("#levelSubsidy").val() ? $("#levelSubsidy").val() : 0)
		+ parseFloat($("#areaSubsidy").val() ? $("#areaSubsidy").val() : 0);
	$("#allTrialWage").val(parseFloat($("#useTrialWage").val() ? $("#useTrialWage").val() : 0) + commonSubsidy).trigger('blur');

	//转正
	var commonSubsidyFormal = parseFloat($("#workBonusFormal").val() ? $("#workBonusFormal").val() : 0)
		+ parseFloat($("#phoneSubsidyFormal").val() ? $("#phoneSubsidyFormal").val() : 0)
		+ parseFloat($("#computerSubsidyFormal").val() ? $("#computerSubsidyFormal").val() : 0)
		+ parseFloat($("#levelSubsidyFormal").val() ? $("#levelSubsidyFormal").val() : 0)
		+ parseFloat($("#areaSubsidyFormal").val() ? $("#areaSubsidyFormal").val() : 0);
	$("#allFormalWage").val(parseFloat($("#useFormalWage").val() ? $("#useFormalWage").val() : 0) + commonSubsidyFormal).trigger('blur');
}

//绑定计算B类总额事件
function bindAllFormalWage() {
	//试用期
	$("#workBonus").blur(countAllFormalWage);
	$("#phoneSubsidy").blur(countAllFormalWage);
	$("#computerSubsidy").blur(countAllFormalWage);
	$("#levelSubsidy").blur(countAllFormalWage);
	$("#areaSubsidy").blur(countAllFormalWage);
	$("#useTrialWage").blur(countAllFormalWage);

	//转正
	$("#workBonusFormal").blur(countAllFormalWage);
	$("#phoneSubsidyFormal").blur(countAllFormalWage);
	$("#computerSubsidyFormal").blur(countAllFormalWage);
	$("#levelSubsidyFormal").blur(countAllFormalWage);
	$("#areaSubsidyFormal").blur(countAllFormalWage);
	$("#useFormalWage").blur(countAllFormalWage);
}