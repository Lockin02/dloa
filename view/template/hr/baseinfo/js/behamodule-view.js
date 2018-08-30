$(document).ready(function() {

	$("#behamoduleDetail").yxeditgrid({
		tableClass : 'form_in_table',
		param : { "moduleId" : $("#id").val()},
		url : '?model=hr_baseinfo_behamoduledetail&action=listJson',
		type : 'view',
		colModel : [{
			display : '行为要项',
			name : 'detailName',
			tclass : 'txt',
			width : 330
		}, {
			display : '备注说明',
			name : 'remark'
		}]
	})
});