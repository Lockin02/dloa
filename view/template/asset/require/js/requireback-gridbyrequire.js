var show_page = function(page) {
	$("#requirebackGrid").yxgrid("reload");
};
$(function() {
	$("#requirebackGrid").yxgrid({
		model : 'asset_require_requireback',
		title : '���ԭ��',
		param : {'requireId':$('#requireId').val()},
		showcheckbox : false,
		isAddAction : false,
		isEditAction : false,
		isViewAction : true,
		isDelAction : false,
		//����Ϣ
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		}, {
			name : 'requireId',
			display : '��������Id',
			sortable : true,
			hide : true
		}, {
			name : 'backReason',
			display : '����ԭ��',
			sortable : true
		}, {
			name : 'createName',
			display : '������',
			sortable : true
		}, {
			name : 'createId',
			display : '������id',
			sortable : true,
			hide : true
		}, {
			name : 'createTime',
			display : '��������',
			sortable : true
		}],

		toViewConfig : {
			action : 'toView'
		}
	});
});