$(document).ready(function() {
	var actType = $("#actType").val();
	if(actType=='audit'){
		$(".txt_btn_a").hide();
	}

	$("#applyHoliday").yxeditgrid({
		objName : 'apply[equ]',
		dir : 'ASC',
		isAddAndDel : false,
		url : '?model=hr_worktime_applyequ&action=listJson',
		param : {
			dir : 'ASC',
			sort : 'holiday',
			parentId : $("#id").val()
		},
		type : 'view',
		colModel : [{
			name : 'holiday',
			display : '假期时间',
			width : '45%',
			type : 'statictext'
		},{
			name : 'holidayInfo',
			display : '加班情况',
			width : '45%',
			process : function(v) {
				if (v == 1) {
					return '上午';
				} else if (v == 2) {
					return '下午';
				} else if (v == 3) {
					return '全天';
				}
				return '';
			}
		}]
	});
})