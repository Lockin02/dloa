$(document).ready(function() {

	$("#behamoduleDetail").yxeditgrid({
		tableClass : 'form_in_table',
		param : { "moduleId" : $("#id").val()},
		url : '?model=hr_baseinfo_behamoduledetail&action=listJson',
		type : 'view',
		colModel : [{
			display : '��ΪҪ��',
			name : 'detailName',
			tclass : 'txt',
			width : 330
		}, {
			display : '��ע˵��',
			name : 'remark'
		}]
	})
});