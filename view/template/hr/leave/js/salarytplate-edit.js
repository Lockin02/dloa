$(document).ready(function() {

	/*
	 * validate({ "orderNum" : { required : true, custom : 'onlyNumber' } });
	 */$("#itemTable").yxeditgrid({
		objName : 'salarytplate[items]',
		url : '?model=hr_leave_salarytplateitem&action=editItemJson',
		param : {
			mainId : $("#id").val()
		},
		colModel : [ {
			name : 'id',
			display : 'id',
			type : 'hidden'
		}, {
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
	})
})