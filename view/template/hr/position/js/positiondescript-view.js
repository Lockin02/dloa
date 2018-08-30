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
			display : '特征项',
			name : 'featureItem',
			width : 200,
			validation : {
				required : true
			}
		}, {
			display : '具体描述',
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
			display : '工作职责',
			name : 'jobContents',
			width : 200,
			validation : {
				required : true
			}
		}, {
			display : '具体任务',
			name : 'specificContents',
			width : 200,
			validation : {
				required : true
			}
		}, {
			display : '要求输出结果或达到的目标',
			name : 'jobTarget',
			width : 500,
			validation : {
				required : true
			}
		}]
	});

});
