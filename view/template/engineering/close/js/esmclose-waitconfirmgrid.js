var show_page = function() {
	$("#esmcloseGrid").yxgrid("reload");
};

$(function() {
	$("#esmcloseGrid").yxgrid({
		model: 'engineering_close_esmclose',
		action: 'waitConfirmJson',
		title: '��Ŀ�ر�ȷ��',
		isAddAction: false,
		isEditAction: false,
		isViewAction: false,
		isDelAction: false,
		isOpButton: false,
		//����Ϣ
		colModel: [{
			display: 'id',
			name: 'id',
			sortable: true,
			hide: true
		}, {
			name: 'projectCode',
			display: '��Ŀ���',
			sortable: true,
			width: 130
		}, {
			name: 'projectName',
			display: '��Ŀ����',
			sortable: true,
			width: 150
		}, {
			name: 'applyName',
			display: '������',
			sortable: true
		}, {
			name: 'applyDate',
			display: '��������',
			sortable: true,
			width: 80
		}, {
			name: 'ruleName',
			display: '��Ŀ',
			sortable: true
		}, {
			name: 'content',
			display: '����',
			sortable: true,
			width: 300
		}, {
			name: 'status',
			display: '״̬',
			sortable: true,
			process: function(v) {
				return v == "1" ? "��ȷ��" : "δȷ��";
			},
			width: 70
		}],
		buttonsEx: [{
			text: "ȷ�����",
			icon: 'add',
			action: function (row, rows) {
				if (row) {
					var newIdArr = [];
					for (var i = 0; i < rows.length; i++) {
						if (rows[i].status != '0') {
							alert('���� [' + rows[i].detailId + '] ����δȷ��״̬�����ܽ��в���');
							return false;
						} else {
							newIdArr.push(rows[i].detailId);
						}
					}
					if (newIdArr.length > 0 && confirm('ȷ�Ͻ��д˲���ô��')) {
						$.ajax({
							type: "POST",
							url: "?model=engineering_close_esmclosedetail&action=confirm",
							data: {
								ids: newIdArr.toString()
							},
							success: function (msg) {
								if (msg == "1") {
									alert('ȷ�ϳɹ���');
									show_page();
								} else {
									alert('ȷ��ʧ��!');
								}
							}
						});
					}
				} else {
					alert('����ѡ������һ����¼');
				}
			}
		}],
		//��������
		comboEx: [{
			text: '״̬',
			key: 'dStatus',
			value: 0,
			data: [{
				text: '��ȷ��',
				value: '1'
			}, {
				text: 'δȷ��',
				value: '0'
			}]
		}],
		searchitems: [{
			display: "��Ŀ���",
			name: 'projectCode'
		},{
			display: "������",
			name: 'applyName'
		}]
	});
});