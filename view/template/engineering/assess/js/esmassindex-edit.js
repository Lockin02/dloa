$(document).ready(function() {
	$("#innerTable").yxeditgrid({
		objName : 'esmassindex[esmassoption]',
		url : '?model=engineering_assess_esmassoption&action=listJson',
		title : '指标选项',
		tableClass : 'form_in_table',
		param : { 'mainId' : $("#id").val() },
		colModel : [{
			display : 'id',
			name : 'id',
			type : 'hidden'
		}, {
			display : '选项名称',
			name : 'name',
			tclass : 'txt',
			validation : {
				required : true
			}
		}, {
			display : '对应分值',
			name : 'score',
			tclass : 'txt',
			validation : {
				required : true
			},
			event : {
				blur : function(){
					setLimitVal($(this).val());
				}
			}
		}]
	});

	validate({
		"name" : {
			required : true
		}
	});
})