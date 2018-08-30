$(document).ready(function () {
	$("#handitemList").yxeditgrid({
		url : '?model=hr_leave_handitem&action=listJson',
		param : {
			'mainId' : $("#id").val()
		},
		objName : 'leave[handitem]',
		isAddOneRow : true,
		colModel : [{
			display : '工作及设备交接事项',
			name : 'handContent',
			width : 600,
			type : 'txt'
		},{
			display : '接收人',
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
			display : '接收人Id',
			name : 'recipientId',
			type : 'hidden'
		}]
	});
});