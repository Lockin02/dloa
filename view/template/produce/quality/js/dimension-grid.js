var show_page = function(page) {
	$("#dimensionGrid").yxgrid("reload");
};
$(function() {
	$("#dimensionGrid").yxgrid({
		model : 'produce_quality_dimension',
		title : '������Ŀ',
		// ����Ϣ
		colModel : [ {
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		}, {
			name : 'dimName',
			display : '����',
			sortable : true,
			width : 200
		} ],

		toEditConfig : {
			action : 'toEdit'
		},
		toViewConfig : {
			action : 'toView'
		},
		searchitems : [ {
			display : "����",
			name : 'dimName'
		} ]
	});
});