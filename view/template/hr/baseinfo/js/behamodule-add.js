$(document).ready(function() {

	$("#behamoduleDetail").yxeditgrid({
		objName : 'behamodule[behamoduledetail]',
		tableClass : 'form_in_table',
		colModel : [{
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