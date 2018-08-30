var show_page = function(page) {
	$("#levelGrid").yxgrid("reload");
};
$(function() {
	$("#levelGrid").yxgrid({
		model : 'hr_basicinfo_level',
		title : '��Ա�����ȼ�',
		//����Ϣ
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		}, {
			name : 'personLevel',
			display : '��Ա�ȼ�',
			sortable : true
		}, {
			name : 'esmLevel',
			display : '��ĿԤ��ȼ�',
			sortable : true
		}, {
			name : 'remark',
			display : '��ע',
			width : '300',
			sortable : true
		}],

		toEditConfig : {
			action : 'toEdit'
		},
		toViewConfig : {
			action : 'toView'
		},
		searchitems : [{
			display : "��Ա�ȼ�",
			name : 'personLevel'
		}, {
			display : "��ע",
			name : 'remark'
		}]
	});
});