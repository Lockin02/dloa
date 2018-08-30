$(document).ready(function() {

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
			}
		}]
	});

});
//�ύ����
function toSubmit(){
	document.getElementById('form1').action="?model=hr_worktime_apply&action=add&actType=approval";
}