var show_page = function(page) {
	$("#payconfigGrid").yxgrid("reload");
};
$(function() {
	$("#payconfigGrid").yxgrid({
		model: 'contract_config_payconfig',
		title: '付款条件设置',
		//列信息
		colModel: [{
			display: 'id',
			name: 'id',
			sortable: true,
			hide: true
		},
		{
			name: 'configName',
			display: '付款名称',
			sortable: true
		},
		{
			name: 'isNeedDate',
			display: '是否日期相关',
			sortable: true,
			process : function(v){
				if(v == "1"){
					return '是';
				}else{
					return '否';
				}
			}
		},
		{
			name: 'dateName',
			display: '付款属性',
			sortable: true
		},
		{
			name: 'dateCode',
			display: '付款属性编码',
			sortable: true,
			hide: true
		},
		{
			name: 'days',
			display: '截止天数',
			sortable: true
		},
			{
				name: 'schePct',
				display: '是否可选进度百分比',
				sortable: true,
				process : function(v){
					if(v == "1"){
						return '是';
					}else{
						return '否';
					}
				}
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