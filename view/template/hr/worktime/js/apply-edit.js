$(document).ready(function() {

	//��ȡ�Ѿ�����Ӱ�ļ���
	var holiday = {};
	$.ajax({
		type : "POST",
		url : '?model=hr_worktime_applyequ&action=listJson',
		data : {
			parentId : $("#id").val()
		},
		async : false,
		success : function(data) {
			if (data) {
				holiday = eval("(" + data + ")");
			}
		}
	});

	$("#applyHoliday").yxeditgrid({
		objName : 'apply[equ]',
		dir : 'ASC',
		isAddAndDel : false,
		url : '?model=hr_worktime_setequ&action=listJson',
		param : {
			dir : 'ASC',
			sort : 'holiday',
			parentId : $("#setId").val()
		},
		colModel : [{
			name : 'isApply',
			display : '�Ƿ�����Ӱ�',
			width : '30%',
			type : 'checkbox',
			checkVal : '1',
			process : function($input ,row) {
				$input.change(function (){
					calculateDays();
				});
			}
		},{
			name : 'holiday',
			display : '����ʱ��',
			width : '30%',
			type : 'statictext'
		},{
			name : 'holiday',
			display : '����ʱ��', //��̨�ɻ�ȡ
			type : 'hidden'
		},{
			name : 'holidayInfo',
			display : '�Ӱ����',
			width : '30%',
			type : 'select',
			options : [{
				name : "ȫ��",
				value : "3"
			},{
				name : "����",
				value : "1"
			},{
				name : "����",
				value : "2"
			}],
			process : function($input ,row) {
				$input.change(function (){
					calculateDays();
				});

				var rowNum = $input.data("rowNum");
				for (var i = 0 ;i < holiday.length ;i++) {
					if (row.holiday == holiday[i].holiday) {
						$("#applyHoliday_cmp_isApply" + rowNum).attr("checked" ,"checked");
						$("#applyHoliday_cmp_holidayInfo" + rowNum).val(holiday[i].holidayInfo).trigger('change');
						break;
					}
				};
			}
		}]
	});

});

//�ύ����
function toSubmit(){
	document.getElementById('form1').action="?model=hr_worktime_apply&action=edit&actType=approval";
}