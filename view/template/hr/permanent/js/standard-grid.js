var show_page = function (page) {
	$("#standardGrid").yxgrid("reload");
};
$(function () {
	$("#standardGrid").yxgrid({
		model : 'hr_permanent_standard',
		title : '试用转正考核项目',
		isOpButton:false,
		//列信息
		colModel : [{
				display : 'id',
				name : 'id',
				sortable : true,
				hide : true
			}, {
				name : 'standard',
				display : '评估项目',
				sortable : true
			}, {
				name : 'standardType',
				display : '评估项目类型',
				sortable : true
			}, {
				name : 'Content',
				display : '备注',
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
				display : "评估项目",
				name : 'standard'
			},{
				display : "评估项目类型",
				name : 'standardType'
			}
		]
	});
});