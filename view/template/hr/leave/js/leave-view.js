$(document).ready(function() {

	$("#handitemList").yxeditgrid({
		objName : 'leave[handitem]',
		url : '?model=hr_leave_handitem&action=listJson',
		param : {'mainId' : $("#id").val()},
		isAddAndDel : false,
		type : 'view',
		colModel : [{
			display : '工作及设备交接事项',
			name : 'handContent',
			width : '70%',
			type : 'statictext'
		}, {
			display : '接收人',
			name : 'recipientName',
			type : 'statictext'
		}]
	});
})