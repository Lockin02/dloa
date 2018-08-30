var show_page = function (page) {
	$("#backGrid").yxsubgrid("reload");
};

$(function () {
	$("#backGrid").yxsubgrid({
		model: 'produce_plan_back',
		param: {
			createId: $("#userId").val()
		},
		title: '�ҵ������������뵥',
		isAddAction: false,
		isEditAction: false,
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
			name: 'pickingCode',
			display: '���ϵ����',
			sortable: true,
			width: 120,
			process: function (v, row) {
				return "<a href='#' onclick='showModalWin(\"?model=produce_plan_picking&action=toView&id=" + row.pickingId +
					"\",1)'>" + v + "</a>";
			}
		}, {
			name: 'docCode',
			display: '���ݱ��',
			sortable: true,
			width: 120,
			process: function (v, row) {
				return "<a href='#' onclick='showModalWin(\"?model=produce_plan_back&action=toView&id=" + row.id +
					"\",1)'>" + v + "</a>";
			}
		}, {
			name: 'docStatus',
			display: '����״̬',
			sortable: true,
			process: function (v) {
				switch (v) {
				case '0':
					return "δȷ��";
					break;
				case '1':
					return "��ȷ��";
					break;
				case '2':
					return "���";
					break;
				default:
					return "--";
				}
			}
		}, {
			name: 'docDate',
			display: '��������',
			sortable: true
		}, {
			name: 'createName',
			display: '������',
			sortable: true
		}, {
			name: 'remark',
			display: '��ע',
			sortable: true,
			width: 250
		}],

		// ���ӱ������
		subGridOptions: {
			url: '?model=produce_plan_backitem&action=pageJson',
			param: [{
				paramId: 'backId',
				colId: 'id'
			}],
			colModel: [{
				name: 'productCode',
				display: '���ϱ���',
				sortable: true,
				width: 150
			}, {
				name: 'productName',
				display: '��������',
				width: 200,
				sortable: true
			}, {
				name: 'pattern',
				display: '����ͺ�',
				sortable: true
			}, {
				name: 'unitName',
				display: '��λ',
				sortable: true
			}, {
				name: 'applyNum',
				display: '��������',
				sortable: true
			}, {
				name: 'backNum',
				display: '�������',
				sortable: true
			}]
		},

		//�����Ҽ��˵�
		menusEx: [{
			text: "�༭",
			icon: 'edit',
			showMenuFn: function (row) {
				if (row.docStatus == '2') {
					return true;
				}
				return false;
			},
			action: function (row) {
				if (row) {
					showModalWin("?model=produce_plan_back&action=toEdit&id=" + row.id, '1');
				}
			}
		},{
			text : "ɾ��",
			icon : 'delete',
			showMenuFn : function(row) {
				if (row.docStatus == '2') {
					return true;
				}
				return false;
			},
			action : function(row) {
				if (window.confirm(("ȷ��Ҫɾ��?"))) {
					$.ajax({
						type : "POST",
						url : "?model=produce_plan_back&action=ajaxdeletes",
						data : {
							id : row.id
						},
						success : function(msg) {
							if (msg == 1) {
								alert('ɾ���ɹ�!');
								show_page(1);
							} else {
								alert("ɾ��ʧ��!");
							}
						}
					});
				}
			}
		}],
		comboEx : [{
			text: '����״̬',
			key: 'docStatus',
			data: [{
				text: 'δȷ��',
				value: '0'
			}, {
				text: '��ȷ��',
				value: '1'
			}, {
				text: '���',
				value: '2'
			}]
		}],
		toViewConfig: {
			toViewFn: function (p, g) {
				if (g) {
					var get = g.getSelectedRow().data('data');
					showModalWin("?model=produce_plan_back&action=toView&id=" + get[p.keyField], '1');
				}
			}
		},
		searchitems: [{
			name: 'docCode',
			display: '���ݱ��'
		}, {
			name: 'docDate',
			display: '��������'
		}, {
			name: 'pickingCode',
			display: '���ϵ����'
		}]
	});
});