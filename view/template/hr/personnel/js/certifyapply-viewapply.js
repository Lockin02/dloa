$(document).ready(function() {

	$("#certifyapplyexp").yxeditgrid({
		url : '?model=hr_personnel_certifyapplyexp&action=listJson',
		param : {"applyId" : $("#id").val()},
		objName : 'certifyapply[certifyapplyexp]',
		tableClass : 'form_in_table',
		type : 'view',
		colModel : [{
			display : 'id',
			name : 'id',
			type : 'hidden'
		}, {
			display : '起始年',
			name : 'beginYear',
			width : 80
		}, {
			display : '起始月',
			name : 'beginMonth',
			width : 60
		}, {
			display : '截止年',
			name : 'endYear',
			width : 80
		}, {
			display : '截止月',
			name : 'endMonth',
			width : 60
		}, {
			display : '单位名称',
			name : 'unitName',
			width : 230
		}, {
			display : '部门',
			name : 'deptName',
			width : 130
		}, {
			display : '主要专业成果、承担的角色',
			name : 'mainWork'
		}]
	});

//	if($("#actType").val() != ""){
//		$("#auditInfo").hide();
//		$("#auditButton").hide();
//	}
});