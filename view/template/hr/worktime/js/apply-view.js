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
			display : '����ʱ��',
			width : '45%',
			type : 'statictext'
		},{
			name : 'holidayInfo',
			display : '�Ӱ����',
			width : '45%',
			process : function(v) {
				if (v == 1) {
					return '����';
				} else if (v == 2) {
					return '����';
				} else if (v == 3) {
					return 'ȫ��';
				}
				return '';
			}
		}]
	});
})