var show_page = function(page) {
	$("#esmassindexGrid").yxgrid("reload");
};
$(function() {
	$("#esmassindexGrid").yxgrid({
		model : 'engineering_assess_esmassindex',
		title : '����ָ���',
		//����Ϣ
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		}, {
			name : 'name',
			display : 'ָ������',
			sortable : true,
			width : 120
		}, {
			name : 'detail',
			display : 'ѡ������',
			sortable : true,
			width : 300
		}, {
			name : 'upperLimit',
			display : '����ֵ',
			sortable : true
		}, {
			name : 'lowerLimit',
			display : '��С��ֵ',
			sortable : true
		}, {
			name : 'sortNo',
			display : '�����',
			sortable : true
		}, {
			name : 'remark',
			display : '��ע��Ϣ',
			sortable : true,
			width : 200
		}],
		toEditConfig : {
			action : 'toEdit'
		},
		toViewConfig : {
			action : 'toView'
		},
		searchitems : [{
			display : "ָ������",
			name : 'nameSearch'
		}]
	});
});