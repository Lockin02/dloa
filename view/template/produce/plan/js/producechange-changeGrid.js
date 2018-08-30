var show_page = function (page) {
	$("#producechangeGrid").yxgrid("reload");
};

$(function () {
	$("#producechangeGrid").yxgrid({
		model: 'produce_plan_producechange',
		title: '�����ƻ������¼',
		param: {
			planId: $('#planId').val()
		},
		comboEx : [{
			text : '�������',
			key : 'changeType',
			data : [{
				text : '����',
				value : 'add'
			}, {
				text : '���',
				value : 'change'
			}, {
				text : 'ȡ��',
				value : 'cancel'
			}]
		}],
		isAddAction: false,
		isEditAction: false,
		isViewAction: false,
		isDelAction: false,
		isOpButton: false,
		showcheckbox: false,
		//����Ϣ
		colModel: [{
			display: 'id',
			name: 'id',
			sortable: true,
			hide: true
		}, {
			name: 'planCode',
			display: '�����ƻ�����',
			sortable: true,
			width: 120
		}, {
			name: 'createName',
			display: '�����',
			sortable: true,
			width: 120
		}, {
			name: 'createTime',
			display: '���ʱ��',
			sortable: true
		}, {
			name: 'changeType',
			display: '�������',
			sortable: true,
			process: function (v, row) {
				switch (v) {
				case 'change':
					return "���";
					break;
				case 'add':
					return "����";
					break;
				case 'cancel':
					return "ȡ��";
					break;
				default:
					return "--";
				}
			}
		}, {
			name: 'remark',
			display: '�����ע',
			sortable: true,
			width: 250
		}],

		searchitems: [{
			display: "�����",
			name: 'createName'
		}]
	});
});