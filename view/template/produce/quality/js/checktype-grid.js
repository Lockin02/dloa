var show_page = function(page) {
	$("#checktypeGrid").yxgrid("reload");
};
$(function() {
	$("#checktypeGrid").yxgrid({
		model : 'produce_quality_checktype',
		title : '���鷽ʽ',
		// ����Ϣ
		colModel : [ {
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		}, {
			name : 'checkType',
			display : '���鷽ʽ',
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
			display : "���鷽ʽ",
			name : 'checkType'
		} ]
	});
});