$(function () {
	$("#suggestJobName").yxcombogrid_jobs({
		hiddenId : 'suggestJobId',
		width : 280,
		gridOptions : {
			showcheckbox : false,
			param : {"deptId" : $("#deptId").val()}
		}
	});

	//必填
	validate({
		"suggestJobName" : {
			required : true
		},
		"suggestSalary" : {
			required : true
		}
	});
})
   //直接提交审批
	function toSubmit(){
		document.getElementById('form1').action = "?model=hr_permanent_examine&action=edit&actType=audit";
	}