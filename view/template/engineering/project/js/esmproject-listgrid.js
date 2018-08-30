// 用于新增/修改后回调刷新表格
var show_page = function(page) {
	$("#esmprojectGrid").esmprojectgrid("reload");
};

(function($) {
	$.woo.yxgrid.subclass('woo.esmprojectgrid', {
		options: {
			sortname: "id",
			// 默认搜索顺序
			model: 'engineering_project_esmproject',
			isAddAction: false,
			isDelAction: false,
			isViewAction: false,
			isEditAction: false,
			showcheckbox: false,
			sortorder: "ASC",
			colModel: [{
				display: 'id',
				name: 'id',
				hide: true
			},{
				display: 'id',
				name: 'managerId',
				hide: true
			},
			{
				display: '项目名称',
				name: 'projectName',
				sortable: true,
				width: '180'
			},
			{
				display: '项目编号',
				name: 'projectCode',
				sortable: true,
				width: '130'
			},
			{
				display: '项目进度',
				name: 'effortRate',
				sortable: true,
				process: function(v) {
					return v + ' <font color="blue">%</font>';
				},
				width: '60'
			},
			{
				display: '(预计)开始',
				name: 'planDateStart',
				sortable: true
			},
			{
				display: '(预计)结束',
				name: 'planDateClose',
				sortable: true
			},
			{
				display: '项目状态',
				name: 'status',
				sortable: true,
				width: '60',
				process: function(v) {
					switch (v) {
					case '1':
						return '保存';
						break;
					case '2':
						return '审批中';
						break;
					case '4':
						return '打回';
						break;
					case '6':
						return '执行中';
						break;
					case '7':
						return '完成';
						break;
					case '8':
						return '关闭';
						break;
					case '9':
						return '待接收';
						break;
					case '10':
						return '已接收';
						break;
					default:
						return '保存';
					}
				}
			},
			{
				display: '项目类型',
				name: 'projectType',
				datacode: 'GCXMXZ',
				sortable: true,
				width: '70'
			},
			{
				display: '主要网络',
				name: 'mainNet',
				datacode: 'GCZYWL',
				sortable: true,
				width: '60'
			},
			{
				display: '长/短期',
				name: 'cycle',
				datacode: 'GCCDQ',
				sortable: true,
				width: '60'
			},
			{
				display: '项目经理',
				name: 'managerName',
				sortable: true
			},
			{
				display: '归属',
				name: 'officeName',
				sortable: true,
				width: '70'
			},
			{
				display: '所属省',
				name: 'proName',
				sortable: true,
				width: '70'
			}],
			// 快速搜索
			searchitems: [{
				display: '项目名称',
				name: 'seachProjectName'
			},
			{
				display: '项目编号',
				name: 'seachProjectCode'
			}],
			comboEx: [{
				text: "项目状态",
				key: 'status',
				data : [{
					text : '保存',
					value : '1'
					}, {
					text : '审批中',
					value : '2'
					}
					, {
					text : '打回',
					value : '4'
					}
					, {
					text : '执行中',
					value : '6'
					}, {
					text : '完成',
					value : '7'
					}, {
					text : '关闭',
					value : '8'
					}, {
					text : '待接收',
					value : '9'
					}, {
					text : '已接收',
					value : '10'
					}
				]
			},{
				text: "项目类型",
				key: 'projectType',
				datacode: 'GCXMXZ'
			}]
		}
	});
})(jQuery);