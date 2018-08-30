$(document).ready(function() {
//	$("#useJobName").yxcombogrid_jobs({
//				hiddenId : 'useJobId',
//				width : 280
//	});
	//单选人员等级
	$("#personLevel").yxcombogrid_personlevel({
		hiddenId : 'personLevelId',
		width : 280,
		gridOptions : {
			showcheckbox : false,
			param:{status:0}
		}
	});

	//确认人
	$("#useManager").yxselect_user({
		hiddenId : 'useManagerId',
		mode : 'check'
	});

	//指定导师
	$("#tutor").yxselect_user({
		hiddenId : 'tutorId'
	});


	///面试结果
	$('select[name="interview[useInterviewResult]"] option').each(function() {
		if( $(this).val() == $("#useInterviewResultSelect").val() ){
			$(this).attr("selected","selected'");
		}
	});

	///公司
	$('select[name="interview[sysCompanyId]"] option').each(function() {
		if( $(this).val() == $("#sysCompanyIdSelect").val() ){
			$(this).attr("selected","selected'");
		}
	});

	///区域
	$('select[name="interview[useAreaId]"] option').each(function() {
		if( $(this).val() == $("#useAreaIdSelect").val() ){
			$(this).attr("selected","selected'");
		}
	});

	///是否本地化
	$('select[name="interview[isLocal]"] option').each(function() {
		if( $(this).val() == $("#isLocalSelect").val() ){
			$(this).attr("selected","selected'");
		}
	});

	///办公电脑需求设备类型
	$('select[name="interview[useDemandEqu]"] option').each(function() {
		if( $(this).val() == $("#useDemandEquSelect").val() ){
			$(this).attr("selected","selected'");
		}
	});

	var hintArr = '请填写《竞业限制协议》补偿标准';
	//选择签订《竞业限制协议》
	$("#useSign").change(function(){
		if($(this).val() == '是'){
			if($.trim($("#compensation").val()) == ''){
				$("#compensation").css({"color":"#999"});
				$("#compensation").val(hintArr);
			}
			$("#compensation").show();
		}else {
			$("#compensation").hide();
		}
	})
	$("#compensation").focus(function(){
		if($.trim($(this).val()) == hintArr) {
			$(this).val("");
			$(this).css({"color":"#000"});
		}
	})
	$("#compensation").blur(function(){
		if($.trim($(this).val()) == '') {
			$(this).val(hintArr);
			$(this).css({"color":"#999"});
		}
	})

	///签订《竞业限制协议》
	$('select[name="interview[useSign]"] option').each(function() {
		if( $(this).val() == $("#useSignSelect").val() ){
			$(this).attr("selected","selected'");
			if ($(this).val() == '是') {
				$("#compensation").show();
			} else {
				$("#compensation").hide();
			}
			$("#useSign").change();
		}
	});

	///项目类型
	$('#projectType').val($('#projectTypeResultSelect').val());
	$('select[name="interview[projectType]"] option').each(function() {
		if( $(this).val() == $("#projectTypeSelect").val() ){
			$(this).attr("selected","selected'");
		}
	});

	//配置是否按公司标准
	$checkVal=$("#isCompanyStandardHidden").val();
	$("#isCompanyStandard"+$checkVal).attr('checked',true);
	if($("#isCompanyStandard0").attr('checked'))
		getRadio();


	$("#projectType").bind('change',function(){//项目类型
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
		}else if($(this).val()=="YFXM"){//研发类型项目
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
		"useJobName" : {
			required : true
		},
		"useHireType" : {
			required : true
		},
		"useAreaName" : {
			required : true
		},
		"useTrialWage" : {
			required : true
		},
		"useFormalWage" : {
			required : true
		},
		"useInterviewResult" : {
			required : true
		},
		"useDemandEqu" : {
			required : true
		},
		"sysCompanyId" : {
			required : true
		},
		"useDemandEqu" : {
			required : true
		},
		"useAreaId" : {
			required : true
		},
		"useManager" : {
			required : true
		},
		"personLevel" : {
			required : true
		}
	})
})

function getRadio(){
	if($("#computerConfiguration").length>0){
		//alert($("#computerConfiguration").length);
		$("#computerConfiguration").remove();
		return;
	}
	var input = document.createElement("input");
    input.type = "text";
    input.id = "computerConfiguration";
    input.name = "interview[computerConfiguration]";
    input.value=$("#computerConfigurations").val();
    document.getElementById("setAttr").appendChild(input);
}
function closeRadio(){
	if($("#computerConfiguration").length>0){
		//alert($("#computerConfiguration").length);
		$("#computerConfiguration").remove();
		return;
	}
}

//验证是否已选择项目类型
 	function checkProjectType(){
 		if($("#projectType").val()==""){
 			alert("请先选择项目类型");
 		}
 	}

 	//提交校验数据
function checkData(){
	if($("#isLocal").val()=='是'&&($("#levelSubsidy").val()==''||$("#areaSubsidy").val()=='')){
		alert("请填写技术级别补助和区域补助");
		return false;
	}
	else{
		return true;
	}
}

 	   //保存
function toEdit(){
	document.getElementById('form1').action = "?model=hr_recruitment_interview&action=deptedit&editType=edit";
}
