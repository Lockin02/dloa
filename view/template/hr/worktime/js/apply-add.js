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
			display : '是否申请加班',
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
			display : '假期时间',
			width : '30%',
			type : 'statictext'
		},{
			name : 'holiday',
			display : '假期时间', //后台可获取
			type : 'hidden'
		},{
			name : 'holidayInfo',
			display : '加班情况',
			width : '30%',
			type : 'select',
			options : [{
				name : "全天",
				value : "3"
			},{
				name : "上午",
				value : "1"
			},{
				name : "下午",
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
//提交审批
function toSubmit(){
	document.getElementById('form1').action="?model=hr_worktime_apply&action=add&actType=approval";
}