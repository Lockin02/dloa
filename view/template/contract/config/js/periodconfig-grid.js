var show_page = function(page) {
	$("#periodconfigGrid").yxgrid("reload");
};
$(function() {
	$("#periodconfigGrid").yxgrid({
		model: 'contract_config_periodconfig',
		title: '回款奖惩期间',
		//列信息
		colModel: [{
			display: 'id',
			name: 'id',
			sortable: true,
			hide: true
		},
		{
			name: 'periodName',
			display: '期间名称',
			sortable: true
		},
		{
			name: 'periodTypeName',
			display: '期间类型',
			sortable: true
		},
		{
			name: 'periodType',
			display: '期间类型编码',
			sortable: true,
			hide: true
		},
		{
			name: 'beginDays',
			display: '开始天数',
			sortable: true
		},
		{
			name: 'endDays',
			display: '结束天数',
			sortable: true
		},
		{
			name: 'description',
			display: '说明',
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