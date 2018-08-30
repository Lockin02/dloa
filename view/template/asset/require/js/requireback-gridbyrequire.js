var show_page = function(page) {
	$("#requirebackGrid").yxgrid("reload");
};
$(function() {
	$("#requirebackGrid").yxgrid({
		model : 'asset_require_requireback',
		title : '打回原因',
		param : {'requireId':$('#requireId').val()},
		showcheckbox : false,
		isAddAction : false,
		isEditAction : false,
		isViewAction : true,
		isDelAction : false,
		//列信息
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		}, {
			name : 'requireId',
			display : '需求申请Id',
			sortable : true,
			hide : true
		}, {
			name : 'backReason',
			display : '撤回原因',
			sortable : true
		}, {
			name : 'createName',
			display : '创建人',
			sortable : true
		}, {
			name : 'createId',
			display : '创建人id',
			sortable : true,
			hide : true
		}, {
			name : 'createTime',
			display : '创建日期',
			sortable : true
		}],

		toViewConfig : {
			action : 'toView'
		}
	});
});