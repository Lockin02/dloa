var show_page = function(page) {
	$("#categoryTitle").yxgrid("reload");
	$("#categoryOptions").yxgrid("reload");
};
$(document).ready(function() {
	$("#categoryTitle").yxeditgrid( {
		objName : 'categoryform[titles]',
		url : '?model=yxlicense_license_categorytitle&action=listJson',
		title : 'ҵ��ϸ���б�',
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
			display : 'ϸ������',
			tclass : 'txt',
			sortable : true,
			validation : {
				required : true
			}
		}
//		, {
//			name : 'optionType',
//			display : '����',
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
		title : 'ѡ���б�',
		param : {
			formId : $("#id").val()
		},
		colModel : [{
			name : 'id',
			display : 'id',
			type : "hidden"
		},{
			name : 'optionName',
			display : 'ѡ������',
			tclass : 'txt',
			sortable : true,
			validation : {
				required : true
			}
		}]
	});
});

