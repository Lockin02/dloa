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
			display : '��ʼ��',
			name : 'beginYear',
			width : 80
		}, {
			display : '��ʼ��',
			name : 'beginMonth',
			width : 60
		}, {
			display : '��ֹ��',
			name : 'endYear',
			width : 80
		}, {
			display : '��ֹ��',
			name : 'endMonth',
			width : 60
		}, {
			display : '��λ����',
			name : 'unitName',
			width : 230
		}, {
			display : '����',
			name : 'deptName',
			width : 130
		}, {
			display : '��Ҫרҵ�ɹ����е��Ľ�ɫ',
			name : 'mainWork'
		}]
	});

//	if($("#actType").val() != ""){
//		$("#auditInfo").hide();
//		$("#auditButton").hide();
//	}
});