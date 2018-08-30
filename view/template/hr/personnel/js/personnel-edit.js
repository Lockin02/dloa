$(document).ready(function() {
	//���֤Ψһ����֤
	$("#identityCard").ajaxCheck({
		url : "?model=hr_personnel_personnel&action=checkRepeat&id=" + $("#id").val(),
		alertText : "* �����֤���Ѵ��ڵ���",
		alertTextOk : "* OK"
	});

	//���֤��Ч����
	var identityCardDate = $("#identityCardDate").val();
	var carDate = identityCardDate.split("-");
	if (carDate.length > 1) {
		$("#identityCardDate0").val(carDate[0]);
		$("#identityCardDate1").val(carDate[1]);
	}

	$("#userName").yxselect_user({
		hiddenId : 'userAccount'
	});

	//�Ա�
	$('select[name="personnel[sex]"] option').each(function() {
		if( $(this).val() == $("#sex").val() ){
			$(this).attr("selected","selected");
		}
	});

	//����״��
	$('select[name="personnel[maritalStatusName]"] option').each(function() {
		if( $(this).val() == $("#maritalStatusName").val() ){
			$(this).attr("selected","selected");
		}
	});

	//����״��
	$('select[name="personnel[birthStatus]"] option').each(function() {
		if( $(this).val() == $("#birthStatus").val() ){
			$(this).attr("selected","selected");
		}
	});

	//��������
	$('select[name="personnel[householdType]"] option').each(function() {
		if( $(this).val() == $("#householdType").val() ){
			$(this).attr("selected","selected");
		}
	});

	//���廧��
	$('select[name="personnel[collectResidence]"] option').each(function() {
		if( $(this).val() == $("#collectResidence").val() ){
			$(this).attr("selected","selected");
		}
	});

	//����
	$('select[name="personnel[regionId]"] option').each(function() {
		if( $(this).val() == $("#regionIdSelect").val() ){
			$(this).attr("selected","selected");
		}
	});

	//�Ƿ��й�����ʷ
	if($("#isYes").attr("checked")){
		$("#medicalHistory").show();
	}else{
		$("#medicalHistory").hide();
	}

	//�Ƿ����ÿ��˷���
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

	/***** ���̲���չ��Ϣ ******/

	//ְλѡ��
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

	//���ݹ�˾���ͻ�ȡ��˾���ݣ�1���ţ�0�ӹ�˾
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
		//��˾����
		$('select[name="personnel[companyTypeCode]"] option').each(function() {
			if( $(this).val() ==companyType){
				$(this).attr("selected","selected");
			}
		});
		//��˾��
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
		if(window.confirm(("�Ƿ����OA�ʺŵĹ�˾��Ϣ?(ȷ��:����|ȡ��:������)"))){
			$("#companyChange").val(1);
		}else{
			$("#companyChange").val(0);
		}
	}
	return true;
}