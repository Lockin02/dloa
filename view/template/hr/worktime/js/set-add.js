$(document).ready(function() {

	$("#holidayInfo").yxeditgrid({
		objName : 'set[equ]',
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