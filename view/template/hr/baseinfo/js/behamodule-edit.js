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
			display : '模块id',
			name : 'moduleId',
			value :  $("#id").val(),
			type : 'hidden'
		}, {
			display : '模块id',
			name : 'moduleName',
			value :  $("#moduleName").val(),
			type : 'hidden'
		}, {
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