$(document).ready(function() {
	$("#itemTable").yxeditgrid({
		objName : 'salarytplate[items]',
		url : '?model=hr_leave_salarytplateitem&action=editItemJson',
		type : 'view',
		param : {
			mainId : $("#id").val()
		},
		colModel : [ {
			name : 'salaryContent',
			tclass : 'txt',
			display : '��������',
			validation : {
				required : true
			}
		}, {
			name : 'remark',
			tclass : 'txt',
			display : '��ע˵��',
			validation : {
				required : true
			}
		} ]
	});
})