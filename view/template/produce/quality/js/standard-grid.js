var show_page = function(page) {
	$("#standardGrid").yxgrid("reload");
};
$(function() {
	$("#standardGrid").yxgrid({
		model : 'produce_quality_standard',
		title : '������׼',
		// ����Ϣ
		colModel : [ {
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		}, {
			name : 'standardName',
			display : '������׼����',
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
			display : "��׼����",
			name : 'standardName'
		} ]
	});
});