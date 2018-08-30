var show_page = function(page) {
	$("#dateconfigGrid").yxgrid("reload");
};
$(function() {
	$("#dateconfigGrid").yxgrid({
		model: 'contract_config_dateconfig',
		title: '日期设置',
		//列信息
		colModel: [{
			display: 'id',
			name: 'id',
			sortable: true,
			hide: true
		},
		{
			name: 'fieldName',
			display: '字段名称',
			sortable: true
		},
		{
			name: 'fieldCode',
			display: '字段编码',
			sortable: true
		},
		{
			name: 'fieldDesc',
			display: '字段描述',
			sortable: true,
			width : 200
		}],
		toEditConfig: {
			action: 'toEdit'
		},
		toViewConfig: {
			action: 'toView'
		},
		searchitems: [{
			display: "搜索字段",
			name: 'XXX'
		}]
	});
});