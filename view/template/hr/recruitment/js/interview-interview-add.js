$(document).ready(function() {

	$("#itemTable").yxeditgrid( {
		objName : 'interview[items]',
		isAddOneRow : true,
		colModel : [{
			display : '面试官',
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
			display : '面试官ID',
			name : 'interviewerId',
			type : 'txt',
			type:'hidden'
		},{
			display : '面试日期',
			name : 'interviewDate',
			type : 'date',
			readonly : true,
			validation : {
				required : true
			}
		},{
			display : '笔试评价',
			name : 'useWriteEva',
			type : 'textarea',
			cols : '40',
			rows : '3',
			validation : {
				required : true
			}
		},{
			display : '面试评价',
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
			display : '面试官',
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
			display : '面试官ID',
			name : 'interviewerId',
			type : 'txt',
			type:'hidden'
		},{
			display : '面试日期',
			name : 'interviewDate',
			type : 'date',
			readonly : true,
			validation : {
				required : true
			}
		},{
			display : '面试评价',
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

//暂存功能
function staging() {
	$("[class*=validate[required]]").each(function () {
		$(this).removeClass("validate[required]");
	});
	document.getElementById('form1').action = "?model=hr_recruitment_interview&staging=true&action=interviewadd";
}