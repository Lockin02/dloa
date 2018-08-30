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
		}]
	});
});
$(document).ready(function() {
	$("#categoryOptions").yxeditgrid( {
		objName : 'categoryform[options]',
		url : '?model=yxlicense_license_categoryoptions&action=listJson',
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
			options : [
				{
					"name" : '勾选',
					"value" : '1'
				},
				{
					"name" : '文本',
					"value" : '2'
				}
			]
		}]
	});
	
	if($("#isHideTitleValue").val() == '1') {
		$("#isHideTitle").attr("checked","checked");
	}
});

