$(function () {
	$("#suggestJobName").yxcombogrid_jobs({
		hiddenId : 'suggestJobId',
		width : 280,
		gridOptions : {
			showcheckbox : false,
			param : {"deptId" : $("#deptId").val()}
		}
	});

	//����
	validate({
		"suggestJobName" : {
			required : true
		},
		"suggestSalary" : {
			required : true
		}
	});
})
   //ֱ���ύ����
	function toSubmit(){
		document.getElementById('form1').action = "?model=hr_permanent_examine&action=edit&actType=audit";
	}