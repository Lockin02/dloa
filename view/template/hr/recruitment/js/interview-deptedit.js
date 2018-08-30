$(document).ready(function() {
//	$("#useJobName").yxcombogrid_jobs({
//				hiddenId : 'useJobId',
//				width : 280
//	});
	//��ѡ��Ա�ȼ�
	$("#personLevel").yxcombogrid_personlevel({
		hiddenId : 'personLevelId',
		width : 280,
		gridOptions : {
			showcheckbox : false,
			param:{status:0}
		}
	});

	//ȷ����
	$("#useManager").yxselect_user({
		hiddenId : 'useManagerId',
		mode : 'check'
	});

	//ָ����ʦ
	$("#tutor").yxselect_user({
		hiddenId : 'tutorId'
	});


	///���Խ��
	$('select[name="interview[useInterviewResult]"] option').each(function() {
		if( $(this).val() == $("#useInterviewResultSelect").val() ){
			$(this).attr("selected","selected'");
		}
	});

	///��˾
	$('select[name="interview[sysCompanyId]"] option').each(function() {
		if( $(this).val() == $("#sysCompanyIdSelect").val() ){
			$(this).attr("selected","selected'");
		}
	});

	///����
	$('select[name="interview[useAreaId]"] option').each(function() {
		if( $(this).val() == $("#useAreaIdSelect").val() ){
			$(this).attr("selected","selected'");
		}
	});

	///�Ƿ񱾵ػ�
	$('select[name="interview[isLocal]"] option').each(function() {
		if( $(this).val() == $("#isLocalSelect").val() ){
			$(this).attr("selected","selected'");
		}
	});

	///�칫���������豸����
	$('select[name="interview[useDemandEqu]"] option').each(function() {
		if( $(this).val() == $("#useDemandEquSelect").val() ){
			$(this).attr("selected","selected'");
		}
	});

	var hintArr = '����д����ҵ����Э�顷������׼';
	//ѡ��ǩ������ҵ����Э�顷
	$("#useSign").change(function(){
		if($(this).val() == '��'){
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

	///ǩ������ҵ����Э�顷
	$('select[name="interview[useSign]"] option').each(function() {
		if( $(this).val() == $("#useSignSelect").val() ){
			$(this).attr("selected","selected'");
			if ($(this).val() == '��') {
				$("#compensation").show();
			} else {
				$("#compensation").hide();
			}
			$("#useSign").change();
		}
	});

	///��Ŀ����
	$('#projectType').val($('#projectTypeResultSelect').val());
	$('select[name="interview[projectType]"] option').each(function() {
		if( $(this).val() == $("#projectTypeSelect").val() ){
			$(this).attr("selected","selected'");
		}
	});

	//�����Ƿ񰴹�˾��׼
	$checkVal=$("#isCompanyStandardHidden").val();
	$("#isCompanyStandard"+$checkVal).attr('checked',true);
	if($("#isCompanyStandard0").attr('checked'))
		getRadio();


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

//��֤�Ƿ���ѡ����Ŀ����
 	function checkProjectType(){
 		if($("#projectType").val()==""){
 			alert("����ѡ����Ŀ����");
 		}
 	}

 	//�ύУ������
function checkData(){
	if($("#isLocal").val()=='��'&&($("#levelSubsidy").val()==''||$("#areaSubsidy").val()=='')){
		alert("����д������������������");
		return false;
	}
	else{
		return true;
	}
}

 	   //����
function toEdit(){
	document.getElementById('form1').action = "?model=hr_recruitment_interview&action=deptedit&editType=edit";
}
