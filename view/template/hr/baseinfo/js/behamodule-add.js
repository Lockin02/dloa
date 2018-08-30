$(document).ready(function() {

	$("#behamoduleDetail").yxeditgrid({
		objName : 'behamodule[behamoduledetail]',
		tableClass : 'form_in_table',
		colModel : [{
			display : '行为要项',
			name : 'detailName',
			tclass : 'txt',
			validation : {
				required : true
			}
		}, {
			display : '备注说明',
			name : 'remark',
			tclass : 'txtlong'
		}]
	})

	/**
	 * 验证信息
	 */
	validate({
		"moduleName" : {
			required : true
		}
	});
});