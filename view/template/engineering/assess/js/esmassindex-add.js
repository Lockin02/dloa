$(document).ready(function() {
	$("#innerTable").yxeditgrid({
		objName : 'esmassindex[esmassoption]',
		title : 'ָ��ѡ��',
		tableClass : 'form_in_table',
		event : {
			removeRow : function(t, rowNum, rowData) {
				countForm();
			}
		},
		colModel : [{
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
});