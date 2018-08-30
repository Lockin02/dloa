$(document).ready(function() {

	$("#holidayInfo").yxeditgrid({
		objName : 'set[equ]',
		dir : 'ASC',
		url : '?model=hr_worktime_setequ&action=listJson',
		param : {
			dir : 'ASC',
			sort : 'holiday',
			parentId : $("#id").val()
		},
		isFristRowDenyDel : true,
		colModel : [{
			name : 'holiday',
			display : '����ʱ��',
			validation : {
				required : true
			},
			type : 'date',
			width : 90
		},{
			name : 'remark',
			display : '��  ע',
			type : 'textarea',
			rows : 1,
			width : 400
		}]
	});

});