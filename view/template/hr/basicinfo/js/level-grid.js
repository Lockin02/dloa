var show_page = function(page) {
	$("#levelGrid").yxgrid("reload");
};
$(function() {
	$("#levelGrid").yxgrid({
		model : 'hr_basicinfo_level',
		title : '人员技术等级',
		//列信息
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		}, {
			name : 'personLevel',
			display : '人员等级',
			sortable : true
		}, {
			name : 'esmLevel',
			display : '项目预算等级',
			sortable : true
		}, {
			name : 'remark',
			display : '备注',
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
			display : "人员等级",
			name : 'personLevel'
		}, {
			display : "备注",
			name : 'remark'
		}]
	});
});