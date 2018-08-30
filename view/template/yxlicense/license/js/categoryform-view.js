$(document).ready(function() {
	$("#categoryTitle").yxeditgrid( {
		objName : 'categoryform[titles]',
		url : '?model=yxlicense_license_categorytitle&action=listJson',
		type : 'view',
		title : 'ҵ��ϸ���б�',
		param : {
			formId : $("#id").val(),
			'dir' : 'ASC'
		},
		colModel : [ {
			name : 'titleName',
			tclass : 'txt',
			display : 'ϸ������',
			sortable : true,
			validation : {
				required : true
			}
		}, {
			name : 'optionType',
			display : '����',
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
		title : 'ѡ���б�',
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
			display : 'ѡ������',
			tclass : 'txt',
			sortable : true,
			validation : {
				required : true
			}
		},{
			name : 'type',
			display : '����',
			type : 'select',
			process : function(v, row) {
				switch(v){
					case "1" : return "<span>��ѡ</span>";break;
					case "2" : return "<span>�ı�</span>";break;
				}
			}
		}]
	});
});