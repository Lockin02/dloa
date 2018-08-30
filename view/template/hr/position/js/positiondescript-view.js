$(function() {
	$("#abilityneedTable").yxeditgrid({
		objName : 'positiondescript[ability]',
		url : '?model=hr_position_ability&action=listJson',
		
		delTagName : 'isDelTag',
		type : 'view',
		param : {
			parentId : $("#id").val()
		},
		colModel : [{
			display : '������',
			name : 'featureItem',
			width : 200,
			validation : {
				required : true
			}
		}, {
			display : '��������',
			name : 'contents',
			width : 350,
			validation : {
				required : true
			}
		}]
	});

	$("#workinfoTable").yxeditgrid({
		objName : 'positiondescript[work]',
		url : '?model=hr_position_work&action=listJson',
		delTagName : 'isDelTag',
		type : 'view',
		param : {
			parentId : $("#id").val()
		},
		colModel : [{
			display : '����ְ��',
			name : 'jobContents',
			width : 200,
			validation : {
				required : true
			}
		}, {
			display : '��������',
			name : 'specificContents',
			width : 200,
			validation : {
				required : true
			}
		}, {
			display : 'Ҫ����������ﵽ��Ŀ��',
			name : 'jobTarget',
			width : 500,
			validation : {
				required : true
			}
		}]
	});

});
