var show_page = function(page) {
	$("#standardGrid").yxgrid("reload");
};
$(function() {
	$("#standardGrid").yxgrid({
		model : 'produce_quality_standard',
		title : '质量标准',
		// 列信息
		colModel : [ {
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		}, {
			name : 'standardName',
			display : '质量标准名称',
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
			display : "标准名称",
			name : 'standardName'
		} ]
	});
});