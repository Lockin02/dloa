$(document).ready(function() {
	$("#innerTable").yxeditgrid({
		objName : 'esmassindex[esmassoption]',
		url : '?model=engineering_assess_esmassoption&action=listJson',
		title : 'ָ��ѡ��',
		tableClass : 'form_in_table',
		param : { 'mainId' : $("#id").val() },
		colModel : [{
			display : 'id',
			name : 'id',
			type : 'hidden'
		}, {
			display : 'ѡ������',
			name : 'name',
			tclass : 'txt',
			validation : {
				required : true
			}
		}, {
			display : '��Ӧ��ֵ',
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