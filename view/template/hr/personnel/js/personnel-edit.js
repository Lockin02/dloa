$(document).ready(function() {
	//身份证唯一性验证
	$("#identityCard").ajaxCheck({
		url : "?model=hr_personnel_personnel&action=checkRepeat&id=" + $("#id").val(),
		alertText : "* 该身份证号已存在档案",
		alertTextOk : "* OK"
	});

	//身份证有效日期
	var identityCardDate = $("#identityCardDate").val();
	var carDate = identityCardDate.split("-");
	if (carDate.length > 1) {
		$("#identityCardDate0").val(carDate[0]);
		$("#identityCardDate1").val(carDate[1]);
	}

	$("#userName").yxselect_user({
		hiddenId : 'userAccount'
	});

	//性别
	$('select[name="personnel[sex]"] option').each(function() {
		if( $(this).val() == $("#sex").val() ){
			$(this).attr("selected","selected");
		}
	});

	//婚姻状况
	$('select[name="personnel[maritalStatusName]"] option').each(function() {
		if( $(this).val() == $("#maritalStatusName").val() ){
			$(this).attr("selected","selected");
		}
	});

	//生育状况
	$('select[name="personnel[birthStatus]"] option').each(function() {
		if( $(this).val() == $("#birthStatus").val() ){
			$(this).attr("selected","selected");
		}
	});

	//户籍类型
	$('select[name="personnel[householdType]"] option').each(function() {
		if( $(this).val() == $("#householdType").val() ){
			$(this).attr("selected","selected");
		}
	});

	//集体户口
	$('select[name="personnel[collectResidence]"] option').each(function() {
		if( $(this).val() == $("#collectResidence").val() ){
			$(this).attr("selected","selected");
		}
	});

	//区域
	$('select[name="personnel[regionId]"] option').each(function() {
		if( $(this).val() == $("#regionIdSelect").val() ){
			$(this).attr("selected","selected");
		}
	});

	//是否有过往病史
	if($("#isYes").attr("checked")){
		$("#medicalHistory").show();
	}else{
		$("#medicalHistory").hide();
	}

	//是否配置考核方案
	if($("#isAddYes").attr("checked")){
		$("#schemeName").show();
		$("#schemeName").yxcombogrid_hrscheme({
			hiddenId : 'schemeId',
            isFocusoutCheck: false,
			width:450
		});
	}else{
		$("#schemeName").val("");
		$("#schemeId").val("");
		$("#schemeName").hide();
	}

	/***** 工程部扩展信息 ******/

	//职位选择
	$("#jobName").yxcombogrid_position({
		hiddenId : 'jobId',
		isShowButton : false,
		width:350,
		gridOptions : {
			param:{
				deptId:$("#belongDeptId").val()
			}
		}
	});

	var companyType = $("#companyType").val();
	if(companyType == '') {
		companyType = 1;
	}

	//根据公司类型获取公司数据：1集团，0子公司
	$.ajax({
		type: "POST",
		url: "?model=deptuser_branch_branch&action=getBranchStr",
		data: {"type" :companyType},
		async: false,
		success: function(data){
			if(data != ""){
				$("#companyName").html(data);
			}
		}
	});

	if($("#companyType").val()!=="") {
		//公司类型
		$('select[name="personnel[companyTypeCode]"] option').each(function() {
			if( $(this).val() ==companyType){
				$(this).attr("selected","selected");
			}
		});
		//公司名
		$('select[name="personnel[companyName]"] option').each(function() {
			if( $(this).val() ==$("#company").val()){
				$(this).attr("selected","selected");
			}
		});
	}
});


function checkData(){
	var companyOld=$("#company").val();
	var companyNew=$("#companyName").val();
	if(companyOld!=companyNew){
		if(window.confirm(("是否更新OA帐号的公司信息?(确定:更新|取消:不更新)"))){
			$("#companyChange").val(1);
		}else{
			$("#companyChange").val(0);
		}
	}
	return true;
}