var show_page = function (page) {
	$("#standardGrid").yxgrid("reload");
};
$(function () {
	$("#standardGrid").yxgrid({
		model : 'hr_permanent_standard',
		title : '����ת��������Ŀ',
		isOpButton:false,
		//����Ϣ
		colModel : [{
				display : 'id',
				name : 'id',
				sortable : true,
				hide : true
			}, {
				name : 'standard',
				display : '������Ŀ',
				sortable : true
			}, {
				name : 'standardType',
				display : '������Ŀ����',
				sortable : true
			}, {
				name : 'Content',
				display : '��ע',
				width:400,
				sortable : true
			}
		],

		toEditConfig : {
			action : 'toEdit'
		},
		toViewConfig : {
			action : 'toView'
		},
		searchitems : [{
				display : "������Ŀ",
				name : 'standard'
			},{
				display : "������Ŀ����",
				name : 'standardType'
			}
		]
	});
});