var show_page = function(page) {
	$("#dimensionGrid").yxgrid("reload");
};
$(function() {
	$("#dimensionGrid").yxgrid({
		model : 'produce_quality_dimension',
		title : '检验项目',
		// 列信息
		colModel : [ {
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		}, {
			name : 'dimName',
			display : '名称',
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
			display : "名称",
			name : 'dimName'
		} ]
	});
});