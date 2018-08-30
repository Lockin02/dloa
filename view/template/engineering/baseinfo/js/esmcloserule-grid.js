var show_page = function() {
	$("#esmcloseruleGrid").yxgrid("reload");
};

$(function() {
	$("#esmcloseruleGrid").yxgrid({
		model: 'engineering_baseinfo_esmcloserule',
		title: '项目关闭规则',
		//列信息
		colModel: [{
			display: 'id',
			name: 'id',
			sortable: true,
			hide: true
		}, {
			name: 'ruleName',
			display: '名目',
			sortable: true
		}, {
			name: 'content',
			display: '描述',
			sortable: true,
			width: 300
		}, {
			name: 'confirmName',
			display: '确认人',
			sortable: true
		}, {
			name: 'status',
			display: '状态',
			sortable: true,
			process: function(v) {
				return v == "0" ? "关闭" : "启用";
			},
			width: 80
		}, {
			name: 'isCustom',
			display: '自定义',
			sortable: true,
			process: function(v) {
				return v == "1" ? "是" : "否";
			},
			width: 80
		}, {
			name: 'isNeed',
			display: '必填',
			sortable: true,
			process: function(v) {
				return v == "1" ? "是" : "否";
			},
			width: 80
		}],
		toEditConfig: {
			action: 'toEdit'
		},
		toViewConfig: {
			action: 'toView'
		},
		sortorder: 'ASC'
	});
});