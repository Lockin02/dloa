var show_page = function(page) {
	$("#esmasslevelGrid").yxgrid("reload");
};
$(function() {
	$("#esmasslevelGrid").yxgrid({
		model : 'engineering_assess_esmasslevel',
		title : '考核结果配置',
		//列信息
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		}, {
			name : 'name',
			display : '考核结果',
			sortable : true
		}, {
			name : 'upperLimit',
			display : '评分上限',
			sortable : true
		}, {
			name : 'lowerLimit',
			display : '评分下限',
			sortable : true
		}],

		toEditConfig : {
			action : 'toEdit'
		},
		toViewConfig : {
			action : 'toView'
		},
		sortorder : 'ASC'
	});
});