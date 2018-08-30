$(document).ready(function() {

	$("#itemTable").yxeditgrid( {
		objName : 'interview[items]',
		isAddOneRow : true,
		colModel : [{
			display : '���Թ�',
			name : 'interviewer',
			validation : {
				required : true
			},
			readonly : true,
			process : function($input) {
				var rowNum = $input.data("rowNum");
				$input.yxselect_user({
					hiddenId : 'itemTable_cmp_interviewerId' + rowNum
				});
			}
		},{
			display : '���Թ�ID',
			name : 'interviewerId',
			type : 'txt',
			type:'hidden'
		},{
			display : '��������',
			name : 'interviewDate',
			type : 'date',
			readonly : true,
			validation : {
				required : true
			}
		},{
			display : '��������',
			name : 'useWriteEva',
			type : 'textarea',
			cols : '40',
			rows : '3',
			validation : {
				required : true
			}
		},{
			display : '��������',
			name : 'interviewEva',
			type : 'textarea',
			cols : '40',
			rows : '3',
			validation : {
				required : true
			}
		}]
	});

	$("#humanResource").yxeditgrid( {
		objName : 'interview[humanResources]',
		isAddOneRow : true,
		colModel : [{
			display : 'id',
			name : 'id',
			type : 'hidden'
		},{
			display : '���Թ�',
			name : 'interviewer',
			validation : {
				required : true
			},
			readonly : true,
			process : function($input) {
				var rowNum = $input.data("rowNum");
				$input.yxselect_user({
					hiddenId : 'humanResource_cmp_interviewerId' + rowNum
				});
			}
		},{
			display : '���Թ�ID',
			name : 'interviewerId',
			type : 'txt',
			type:'hidden'
		},{
			display : '��������',
			name : 'interviewDate',
			type : 'date',
			readonly : true,
			validation : {
				required : true
			}
		},{
			display : '��������',
			name : 'interviewEva',
			type : 'textarea',
			cols : '40',
			rows : '3',
			validation : {
				required : true
			}
		}]
	})
});

//�ݴ湦��
function staging() {
	$("[class*=validate[required]]").each(function () {
		$(this).removeClass("validate[required]");
	});
	document.getElementById('form1').action = "?model=hr_recruitment_interview&staging=true&action=interviewadd";
}