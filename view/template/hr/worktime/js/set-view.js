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
		type : 'view',
		colModel : [{
			name : 'holiday',
			display : '假期时间',
			validation : {
				required : true
			},
			align : 'center',
			width : '15%'
		},{
			name : 'remark',
			display : '备  注',
			align : 'left',
			width : '80%'
		}]
	});

});