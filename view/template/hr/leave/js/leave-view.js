$(document).ready(function() {

	$("#handitemList").yxeditgrid({
		objName : 'leave[handitem]',
		url : '?model=hr_leave_handitem&action=listJson',
		param : {'mainId' : $("#id").val()},
		isAddAndDel : false,
		type : 'view',
		colModel : [{
			display : '�������豸��������',
			name : 'handContent',
			width : '70%',
			type : 'statictext'
		}, {
			display : '������',
			name : 'recipientName',
			type : 'statictext'
		}]
	});
})