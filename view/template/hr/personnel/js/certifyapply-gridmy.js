var show_page = function (page) {
	$("#certifyapplyGrid").yxgrid("reload");
};

$(function () {
	$("#certifyapplyGrid").yxgrid({
		model: 'hr_personnel_certifyapply',
		action: 'myPageJson',
		title: '�ҵ���ְ�ʸ���֤����',
		isDelAction: false,
		isAddAction: true,
		isEditAction: true,
		isOpButton: false,
		showcheckbox: false,
		bodyAlign: 'center',
		//����Ϣ
		colModel: [{
			display: 'id',
			name: 'id',
			sortable: true,
			hide: true
		}, {
			name: 'userNo',
			display: 'Ա�����',
			sortable: true,
			width: 70,
			hide: true
		}, {
			name: 'userName',
			display: 'Ա������',
			sortable: true,
			width: 60,
			process: function (v, row) {
				return "<a href='#' onclick='showOpenWin(\"?model=hr_personnel_certifyapply&action=toViewApplyPerson&id=" +
					row.id + '&skey=' + row.skey_ + "\")'>" + v + "</a>";
			}
		}, {
			name: 'deptName',
			display: '��������',
			sortable: true,
			hide: true
		}, {
			name: 'applyDate',
			display: '��������',
			sortable: true,
			width: 70
		}, {
			name: 'careerDirectionName',
			display: '����ͨ��',
			sortable: true,
			width: 80
		}, {
			name: 'baseLevelName',
			display: '���뼶��',
			sortable: true,
			width: 50
		}, {
			name: 'baseGradeName',
			display: '���뼶��',
			sortable: true,
			width: 50
		}, {
			name: 'status',
			display: '����״̬',
			sortable: true,
			width: 80,
			process: function (v) {
				switch (v) {
				case '0':
					return 'δ�ύ';
					break;
				case '1':
					return '������';
					break;
				case '2':
					return '��֤�������';
					break;
				case '3':
					return '��֤׼����';
					break;
				case '4':
					return '��֤������';
					break;
				case '5':
					return '��֤������';
					break;
				case '6':
					return '��֤�����';
					break;
				case '7':
					return '��֤��������';
					break;
				case '8':
					return '��֤ʧ��';
					break;
				case '10':
					return '��֤�����';
					break;
				case '11':
					return '���';
					break;
				case '12':
					return '���';
					break;
				default:
					return v;
				}
			}
		}, {
			name: 'backReason',
			display: '���ԭ��',
			sortable: true,
			width: 420
		}],

		toAddConfig: {
			formHeight: 500,
			formWidth: 900,
			toAddFn: function (p, g) {
				showModalWin("?model=hr_personnel_certifyapply&action=toAddApply");
			}
		},
		toEditConfig: {
			showMenuFn: function (row) {
				if (row.status == '0' || row.status == '12') {
					return true;
				}
				return false;
			},
			toEditFn: function (p, g) {
				action: showModalWin("?model=hr_personnel_certifyapply&action=toEditApply&id=" + g.getSelectedRow().data(
					'data')['id']);
			}
		},
		toViewConfig: {
			toViewFn: function (p, g) {
				action: showOpenWin("?model=hr_personnel_certifyapply&action=toViewApplyPerson&id=" + g.getSelectedRow().data(
					'data')['id']);
			}
		},

		menusEx: [{
			text: '�ύ',
			icon: 'add',
			showMenuFn: function (row) {
				if (row.status == '0' || row.status == '12') {
					return true;
				}
				return false;
			},
			action: function (row, rows, grid) {
				$.ajax({
					type: "POST",
					url: "?model=hr_personnel_certifyapply&action=submitApply",
					data: {
						id: row.id
					},
					success: function (msg) {
						if (msg == 1) {
							alert('�ύ�ɹ���');
							show_page(1);
						} else {
							alert("�ύʧ��! ");
						}
					}
				});
			}
		}, {
			text: "ɾ��",
			icon: 'delete',
			showMenuFn: function (row) {
				if (row.status == '0' || row.status == '12') {
					return true;
				}
				return false;
			},
			action: function (row) {
				if (window.confirm(("ȷ��Ҫɾ��?"))) {
					$.ajax({
						type: "POST",
						url: "?model=hr_personnel_certifyapply&action=ajaxdeletes",
						data: {
							id: row.id
						},
						success: function (msg) {
							if (msg == 1) {
								alert('ɾ���ɹ���');
								show_page();
							} else {
								alert("ɾ��ʧ��! ");
							}
						}
					});
				}
			}
		}],

		searchitems: [{
			display: "Ա������",
			name: 'userNameSearch'
		}, {
			display: "��������",
			name: 'applyDateSearch'
		}, {
			display: "����ͨ��",
			name: 'careerDirectionNameSearch'
		}, {
			display: "���뼶��",
			name: 'baseLevelName'
		}, {
			display: "���뼶��",
			name: 'baseGradeName'
		}]
	});
});