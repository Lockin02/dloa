var show_page = function(page) {
	$("#checksettingGrid").yxgrid("reload");
};
$(function() {
	$("#checksettingGrid").yxgrid({
		model : 'contract_checksetting_checksetting',
		title : '验收管理设置',
		//列信息
		colModel : [ {
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		}, {
			name : 'clause',
			display : '验收条款',
			sortable : true,
			width : 100
		}, {
			name : 'dateName',
			display : '验收时间节点',
			sortable : true
		}, {
			name : 'dateCode',
			display : '验收时间节点编码',
			sortable : true,
			hide : true
		}, {
			name : 'days',
			display : '缓冲天数',
			sortable : true
		}, {
			name : 'description',
			display : '说明',
			sortable : true,
			width : 300
		} ],

		toEditConfig : {
			action : 'toEdit'
		},
		toViewConfig : {
			action : 'toView'
		},
		searchitems : [ {
			display : "搜索字段",
			name : 'XXX'
		} ]
	});
});