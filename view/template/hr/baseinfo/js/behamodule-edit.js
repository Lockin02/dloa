$(document).ready(function() {

	$("#behamoduleDetail").yxeditgrid({
		objName : 'behamodule[behamoduledetail]',
		param : { "moduleId" : $("#id").val()},
		url : '?model=hr_baseinfo_behamoduledetail&action=listJson',
		tableClass : 'form_in_table',
		colModel : [{
			display : 'id',
			name : 'id',
			type : 'hidden'
		}, {
			display : 'ģ��id',
			name : 'moduleId',
			value :  $("#id").val(),
			type : 'hidden'
		}, {
			display : 'ģ��id',
			name : 'moduleName',
			value :  $("#moduleName").val(),
			type : 'hidden'
		}, {
			display : '��ΪҪ��',
			name : 'detailName',
			tclass : 'txt',
			validation : {
				required : true
			}
		}, {
			display : '��ע˵��',
			name : 'remark',
			tclass : 'txtlong'
		}]
	})

	/**
	 * ��֤��Ϣ
	 */
	validate({
		"moduleName" : {
			required : true
		}
	});
});