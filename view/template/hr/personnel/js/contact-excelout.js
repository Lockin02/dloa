$(document).ready(function() {
	$("#belongDeptName").yxselect_dept({
		hiddenId  :'belongDeptId',
		mode : 'check'
	});
	//二级部门
	$("#deptNameSSearch").yxselect_dept({
		hiddenId : 'deptIdS'
	});
	//三级部门
	$("#deptNameTSearch").yxselect_dept({
		hiddenId : 'deptIdT'
	});
    //四级部门
    $("#deptNameFSearch").yxselect_dept({
        hiddenId : 'deptIdF'
    });
	//直属部门
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
			//根据公司类型获取公司数据：1集团，0子公司
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

	//员工状态选择绑定事件
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