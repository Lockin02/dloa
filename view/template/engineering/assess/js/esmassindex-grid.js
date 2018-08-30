var show_page = function(page) {
	$("#esmassindexGrid").yxgrid("reload");
};
$(function() {
	$("#esmassindexGrid").yxgrid({
		model : 'engineering_assess_esmassindex',
		title : '考核指标表',
		//列信息
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		}, {
			name : 'name',
			display : '指标名称',
			sortable : true,
			width : 120
		}, {
			name : 'detail',
			display : '选项内容',
			sortable : true,
			width : 300
		}, {
			name : 'upperLimit',
			display : '最大分值',
			sortable : true
		}, {
			name : 'lowerLimit',
			display : '最小分值',
			sortable : true
		}, {
			name : 'sortNo',
			display : '排序号',
			sortable : true
		}, {
			name : 'remark',
			display : '备注信息',
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
			display : "指标名称",
			name : 'nameSearch'
		}]
	});
});