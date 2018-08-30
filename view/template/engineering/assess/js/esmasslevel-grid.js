var show_page = function(page) {
	$("#esmasslevelGrid").yxgrid("reload");
};
$(function() {
	$("#esmasslevelGrid").yxgrid({
		model : 'engineering_assess_esmasslevel',
		title : '���˽������',
		//����Ϣ
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		}, {
			name : 'name',
			display : '���˽��',
			sortable : true
		}, {
			name : 'upperLimit',
			display : '��������',
			sortable : true
		}, {
			name : 'lowerLimit',
			display : '��������',
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