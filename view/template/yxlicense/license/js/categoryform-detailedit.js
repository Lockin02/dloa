var show_page = function(page) {
	$("#categoryTitle").yxgrid("reload");
	$("#categoryOptions").yxgrid("reload");
};
$(document).ready(function() {
	$("#categoryTitle").yxeditgrid( {
		objName : 'categoryform[titles]',
		url : '?model=yxlicense_license_categorytitle&action=listJson',
		title : '业务细分列表',
		param : {
			formId : $("#id").val(),
			'dir' : 'ASC'
		},
		colModel : [{
			name : 'id',
			display : 'id',
			type : "hidden"
		},{
			name : 'titleName',
			display : '细分名称',
			tclass : 'txt',
			sortable : true,
			validation : {
				required : true
			}
		}
//		, {
//			name : 'optionType',
//			display : '类型',
//			width : 300,
//			sortable : true,
//			validation : {
//				required : true
//			}
//		}
		]
	});
});
$(document).ready(function() {
	$("#categoryOptions").yxeditgrid( {
		objName : 'categoryform[options]',
		url : '?model=yxlicense_license_categoryoptions&action=listJson',
		title : '选择列表',
		param : {
			formId : $("#id").val()
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
		}]
	});
});

