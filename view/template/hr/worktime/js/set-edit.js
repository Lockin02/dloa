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
			display : '假期时间',
			validation : {
				required : true
			},
			type : 'date',
			width : 90
		},{
			name : 'remark',
			display : '备  注',
			type : 'textarea',
			rows : 1,
			width : 400
		}]
	});

});