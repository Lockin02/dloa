$(document).ready(function () {
	$("#handitemList").yxeditgrid({
		url : '?model=hr_leave_handitem&action=listJson',
		param : {
			'mainId' : $("#id").val()
		},
		objName : 'leave[handitem]',
		isAddOneRow : true,
		colModel : [{
			display : '�������豸��������',
			name : 'handContent',
			width : 600,
			type : 'txt'
		},{
			display : '������',
			name : 'recipientName',
			type : 'text',
			readonly : true,
			process : function($input ,rowData) {
				var rowNum = $input.data("rowNum");
				var g = $input.data("grid");
				$input.yxselect_user({
					hiddenId : 'handitemList_cmp_recipientId' + rowNum,
					event : {
						'select' : function(e ,row ,data) {
							setinfo();
						}
					}
				});
			}
		},{
			display : '������Id',
			name : 'recipientId',
			type : 'hidden'
		}]
	});
});