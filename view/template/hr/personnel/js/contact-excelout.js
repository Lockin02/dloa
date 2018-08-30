$(document).ready(function() {
	$("#belongDeptName").yxselect_dept({
		hiddenId  :'belongDeptId',
		mode : 'check'
	});
	//��������
	$("#deptNameSSearch").yxselect_dept({
		hiddenId : 'deptIdS'
	});
	//��������
	$("#deptNameTSearch").yxselect_dept({
		hiddenId : 'deptIdT'
	});
    //�ļ�����
    $("#deptNameFSearch").yxselect_dept({
        hiddenId : 'deptIdF'
    });
	//ֱ������
	$("#deptNameSearch").yxselect_dept({
		hiddenId : 'deptId'
	});

	$("#jobName").yxcombogrid_position({
		hiddenId : 'jobId',
		width : 400
	});

	$("#companyTypeCode").bind('change', function() {
		var companyType = $(this).val();
		if($(this).val() != ""){
			$("#companyName").html("");
			//���ݹ�˾���ͻ�ȡ��˾���ݣ�1���ţ�0�ӹ�˾
			$.ajax({
				type : "POST",
				url : "?model=deptuser_branch_branch&action=getBranchStr",
				data : {
					"type" : companyType
				},
				async : true,
				success : function(data){
					if(data != "") {
						$("#companyName").append('<option value=""></option>');
						$("#companyName").append(data);
					}
				}
			});
		}else{
			$("#companyName").html("");
		}
	});

	//Ա��״̬ѡ����¼�
	$("#employeesState").bind('change', function() {
		$("#staffState").empty();
		if($(this).val() == "YGZTZZ") {
			$("#staffState").append('<option value=""></option>');
			GongArr = getData('YGZTZZ');
			addDataToSelect(GongArr, 'staffState');
		} else if($(this).val() == "YGZTLZ") {
			GongArr = getData('YGZTLZ');
			$("#staffState").append('<option value=""></option>');
			addDataToSelect(GongArr, 'staffState');
		}
	});
});