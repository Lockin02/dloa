var show_page = function(page) {
	$("#checktypeGrid").yxgrid("reload");
};
$(function() {
	$("#checktypeGrid").yxgrid({
		model : 'produce_quality_checktype',
		title : '检验方式',
		// 列信息
		colModel : [ {
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		}, {
			name : 'checkType',
			display : '检验方式',
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
			display : "检验方式",
			name : 'checkType'
		} ]
	});
});