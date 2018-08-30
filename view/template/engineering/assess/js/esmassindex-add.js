$(document).ready(function() {
	$("#innerTable").yxeditgrid({
		objName : 'esmassindex[esmassoption]',
		title : '指标选项',
		tableClass : 'form_in_table',
		event : {
			removeRow : function(t, rowNum, rowData) {
				countForm();
			}
		},
		colModel : [{
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
});