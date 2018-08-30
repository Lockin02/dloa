$(document).ready(function() {
	$("#categoryTitle").yxeditgrid( {
		objName : 'categoryform[titles]',
		url : '?model=yxlicense_license_categorytitle&action=listJson',
		type : 'view',
		title : '业务细分列表',
		param : {
			formId : $("#id").val(),
			'dir' : 'ASC'
		},
		colModel : [ {
			name : 'titleName',
			tclass : 'txt',
			display : '细分名称',
			sortable : true,
			validation : {
				required : true
			}
		}, {
			name : 'optionType',
			display : '类型',
			tclass : 'txt',
			type : 'hidden',
			sortable : true
		}]
	});
});
$(document).ready(function() {
	$("#categoryOptions").yxeditgrid( {
		objName : 'categoryform[options]',
		url : '?model=yxlicense_license_categoryoptions&action=listJson',
		type : 'view',
		title : '选项列表',
		param : {
			formId : $("#id").val(),
			'dir' : 'ASC'
		},
		colModel : [{
			name : 'id',
			display : 'id',
			type : "hidden"
		},{
			name : 'optionName',
			display : '选项名称',
			tclass : 'txt',
			sortable : true,
			validation : {
				required : true
			}
		},{
			name : 'type',
			display : '类型',
			type : 'select',
			process : function(v, row) {
				switch(v){
					case "1" : return "<span>勾选</span>";break;
					case "2" : return "<span>文本</span>";break;
				}
			}
		}]
	});
});