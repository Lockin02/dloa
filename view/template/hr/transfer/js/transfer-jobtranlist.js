var show_page = function (page) {
	$("#transferGrid").yxgrid("reload");
};

$(function () {
	var userId = $('#userId').val();
	$("#transferGrid").yxgrid({
		model: 'hr_transfer_transfer',
		action: 'pageJson',
		param: {
			listId: userId
		},
		title: '������¼',
		isAddAction: true,
		isEditAction: false,
		isViewAction: false,
		isDelAction: false,
		showcheckbox: false,
		isOpButton: false,
		bodyAlign: 'center',

		//����Ϣ
		colModel: [{
			display: 'id',
			name: 'id',
			sortable: true,
			hide: true
		}, {
			name: 'formCode',
			display: '���ݱ��',
			sortable: true,
			width: 120,
			process: function (v, row) {
				return "<a href='#' onclick='location=\"?model=hr_transfer_transfer&action=toViewJobTran&id=" + row.id +
					"\"'>" + v + "</a>";
			}
		}, {
			name: 'userNo',
			display: 'Ա�����',
			width: 80,
			sortable: true
		}, {
			name: 'userName',
			display: 'Ա������',
			sortable: true,
			width: 70
		}, {
			name: 'stateC',
			display: '����״̬',
			width: 70
		}, {
			name: 'ExaStatus',
			display: '����״̬',
			sortable: true,
			width: 70
		}, {
			name: 'transferTypeName',
			display: '��������',
			sortable: true,
			width: 200
		}, {
			name: 'entryDate',
			display: '��ְ����',
			sortable: true,
			width: 80
		}, {
			name: 'applyDate',
			display: '��������',
			sortable: true,
			width: 80
		}, {
			name: 'preUnitTypeName',
			display: '����ǰ��λ',
			sortable: true,
			hide: true
		}, {
			name: 'preUnitName',
			display: '����ǰ��˾',
			sortable: true
		}, {
			name: 'afterUnitTypeName',
			display: '������λ����',
			sortable: true,
			hide: true
		}, {
			name: 'afterUnitName',
			display: '������˾',
			sortable: true
		}, {
			name: 'preBelongDeptName',
			display: '����ǰ��������',
			sortable: true
		}, {
			name: 'afterBelongDeptName',
			display: '��������������',
			sortable: true
		}, {
			name: 'preDeptNameS',
			display: '����ǰ��������',
			hide: true
		}, {
			name: 'preDeptNameT',
			display: '����ǰ��������',
			hide: true
		}, {
			name: 'afterDeptNameS',
			display: '�������������',
			hide: true
		}, {
			name: 'afterDeptNameT',
			display: '��������������',
			hide: true
		}, {
			name: 'preJobName',
			display: '����ǰְλ',
			sortable: true
		}, {
			name: 'afterJobName',
			display: '������ְλ',
			sortable: true
		}, {
			name: 'preUseAreaName',
			display: '����ǰ��������',
			sortable: true
		}, {
			name: 'afterUseAreaName',
			display: '�������������',
			sortable: true
		}, {
			name: 'prePersonClass',
			display: '����ǰ��Ա����',
			sortable: true
		}, {
			name: 'afterPersonClass',
			display: '��������Ա����',
			sortable: true
		}, {
			name: 'reason',
			display: '����ԭ��',
			sortable: true,
			hide: true,
			width: 130
		}, {
			name: 'remark',
			display: '��ע˵��',
			sortable: true,
			hide: true,
			width: 130
		}],

		lockCol: ['formCode', 'userNo', 'userName'], //����������

		toViewConfig: {
			action: 'toViewJobTran',
			formHeight: 500,
			formWidth: 900
		},
		toAddConfig: {
			toAddFn : function(p, g){
				alert("���ã���OA�����ߣ��뵽��OA�ύ���롣лл��");
				return false;
			},
			action: 'toAddJobTran',
			formHeight: 500,
			formWidth: 900
		},

		//��չ�Ҽ��˵�
		menusEx: [{
			text: '�鿴',
			icon: 'view',
			action: function (row, rows, grid) {
				if (row) {
					showThickboxWin("?model=hr_transfer_transfer&action=toViewJobTran&id=" + row.id +
						"&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=600&width=800");
				}
			}
		}, {
			text: '�޸�',
			icon: 'edit',
			showMenuFn: function (row) {
				if ((row.stateC == "δ�ύ" || row.ExaStatus == "���") && (row.managerId == userId || row.userAccount == userId)) {
					return true;
				}
				return false;
			},
			action: function (row) {
				if (row) {
					showThickboxWin("?model=hr_transfer_transfer&action=toEditJobTran&id=" + row.id +
						"&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=600&width=800");
				}
			}
		}, {
			text: 'ɾ��',
			icon: 'delete',
			showMenuFn: function (row) {
				if ((row.stateC == "δ�ύ" || row.ExaStatus == "���") && (row.managerId == userId || row.userAccount == userId)) {
					return true;
				}
				return false;
			},
			action: function (row, rows, grid) {
				if (row) {
					if (window.confirm("ȷ��Ҫɾ��?")) {
						$.ajax({
							type: "POST",
							url: "?model=hr_transfer_transfer&action=ajaxdeletes",
							data: {
								id: row.id
							},
							success: function (msg) {
								if (msg == 1) {
									alert('ɾ���ɹ�!');
									show_page();
								} else {
									alert('ɾ��ʧ��!');
									show_page();
								}
							}
						});
					}
				}
			}
		}, {
			text: '�ύ',
			icon: 'add',
			showMenuFn: function (row) {
				if ((row.stateC == "δ�ύ" || row.ExaStatus == "���") && (row.managerId == userId || row.userAccount == userId)) {
					return true;
				}
				return false;
			},
			action: function (row) {
				if (window.confirm("ȷ��Ҫ�ύ?")) {
					$.ajax({
						type: "POST",
						url: "?model=hr_transfer_transfer&action=ajaxSubmit",
						data: {
							id: row.id
						},
						success: function (msg) {
							if (msg == 1) {
								alert('�ύ�ɹ�!');
								show_page();
							} else {
								alert('�ύʧ��!');
								show_page();
							}
						}
					});
				}
			}
		}, {
			text: 'Ա�����',
			icon: 'edit',
			showMenuFn: function (row) {
				if (row.employeeOpinion != 1 && row.userAccount == userId && row.ExaStatus == '���') {
					return true;
				}
				return false;
			},
			action: function (row) {
				if (row) {
					location = "?model=hr_transfer_transfer&action=toOpinionView&id=" + row.id;
				}
			}
		}, {
			name: 'aduit',
			text: '�������',
			icon: 'view',
			showMenuFn: function (row) {
				if (row.ExaStatus == '���' || row.ExaStatus == '���' || row.ExaStatus == '��������') {
					return true;
				}
				return false;
			},
			action: function (row, rows, grid) {
				if (row) {
					showThickboxWin("controller/common/readview.php?itemtype=oa_hr_personnel_transfer&pid=" + row.id +
						"&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=800");
				}
			}
		}],

		comboEx: [{
			text: '����״̬',
			key: 'ExaStatus',
			data: [{
				text: 'δ�ύ',
				value: 'δ�ύ'
			}, {
				text: '��������',
				value: '��������'
			}, {
				text: '���',
				value: '���'
			}]
		}],

		/**
		 * ��������
		 */
		searchitems: [{
			display: '���ݱ��',
			name: 'formCode'
		}, {
			display: 'Ա�����',
			name: 'userNoSearch'
		}, {
			display: 'Ա������',
			name: 'userNameSearch'
		}, {
			display: '��ְ����',
			name: 'entryDate'
		}, {
			display: '��������',
			name: 'applyDate'
		}, {
			display: '����ǰ��˾',
			name: 'preUnitName'
		}, {
			display: '����ǰ��������',
			name: 'preBelongDeptName'
		}, {
			display: '������˾',
			name: 'afterUnitName'
		}, {
			display: '��������������',
			name: 'afterBelongDeptName'
		}, {
			display: '����ǰְλ',
			name: 'preJobName'
		}, {
			display: '������ְλ',
			name: 'afterJobName'
		}, {
			display: '����ǰ��������',
			name: 'preUseAreaName'
		}, {
			display: '�������������',
			name: 'afterUseAreaName'
		}, {
			display: '����ǰ��Ա����',
			name: 'prePersonClass'
		}, {
			display: '��������Ա����',
			name: 'afterPersonClass'
		}]
	});
});