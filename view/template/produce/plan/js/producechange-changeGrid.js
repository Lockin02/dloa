var show_page = function (page) {
	$("#producechangeGrid").yxgrid("reload");
};

$(function () {
	$("#producechangeGrid").yxgrid({
		model: 'produce_plan_producechange',
		title: '生产计划变更记录',
		param: {
			planId: $('#planId').val()
		},
		comboEx : [{
			text : '变更类型',
			key : 'changeType',
			data : [{
				text : '新增',
				value : 'add'
			}, {
				text : '变更',
				value : 'change'
			}, {
				text : '取消',
				value : 'cancel'
			}]
		}],
		isAddAction: false,
		isEditAction: false,
		isViewAction: false,
		isDelAction: false,
		isOpButton: false,
		showcheckbox: false,
		//列信息
		colModel: [{
			display: 'id',
			name: 'id',
			sortable: true,
			hide: true
		}, {
			name: 'planCode',
			display: '生产计划单号',
			sortable: true,
			width: 120
		}, {
			name: 'createName',
			display: '变更人',
			sortable: true,
			width: 120
		}, {
			name: 'createTime',
			display: '变更时间',
			sortable: true
		}, {
			name: 'changeType',
			display: '变更类型',
			sortable: true,
			process: function (v, row) {
				switch (v) {
				case 'change':
					return "变更";
					break;
				case 'add':
					return "新增";
					break;
				case 'cancel':
					return "取消";
					break;
				default:
					return "--";
				}
			}
		}, {
			name: 'remark',
			display: '变更备注',
			sortable: true,
			width: 250
		}],

		searchitems: [{
			display: "变更人",
			name: 'createName'
		}]
	});
});