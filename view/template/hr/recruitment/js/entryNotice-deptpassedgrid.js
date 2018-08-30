var show_page = function (page) {
	$("#entryNoticeGrid").yxgrid("reload");
};

$(function () {
	$("#entryNoticeGrid").yxgrid({
		model: 'hr_recruitment_entryNotice',
		title: '������ְ����',
		isDelAction: false,
		isEditAction: false,
		isAddAction: false,
		showcheckbox: false,
		isOpButton: false,
		bodyAlign: 'center',
		param: {
			stateArr: "2,3"
		},

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
			process: function (v, row) {
				return "<a href='#' onclick='showOpenWin(\"?model=hr_recruitment_entryNotice&action=toView&id=" + row.id +
					"\",1)'>" + v + "</a>";
			},
			width: 120
		}, {
			name: 'resumeCode',
			display: '�������',
			sortable: true,
			process: function (v, row) {
				return "<a href='#' onclick='showOpenWin(\"?model=hr_recruitment_resume&action=toRead&code=" +
					v + "\",1)'>" + v + "</a>";
			},
			width: 90
		}, {
			name: 'hrSourceType2Name',
			display: '������ԴС��',
			sortable: true
		}, {
			name: 'entryDate',
			display: '��ְ����',
			sortable: true,
			width: 70
		}, {
			name: 'userName',
			display: '����',
			sortable: true,
			width: 60
		}, {
			name: 'stateC',
			display: '״̬',
			width: 80
		}, {
			name: 'assistManName',
			display: '��ְЭ����',
			sortable: true,
			width: 60
		}, {
			name: 'sex',
			display: '�Ա�',
			sortable: true,
			width: 60,
			hide: true
		}, {
			name: 'phone',
			display: '��ϵ�绰',
			sortable: true,
			hide: true
		}, {
			name: 'email',
			display: '��������',
			sortable: true,
			hide: true
		}, {
			name: 'deptName',
			display: '���˲���',
			sortable: true,
			width: 80
		}, {
			name: 'workPlace',
			display: '�����ص�',
			sortable: true,
			width: 80,
			process: function (v, row) {
				return row.workProvince + ' - ' + row.workCity;
			}
		}, {
			name: 'socialPlace',
			display: '�籣�����',
			sortable: true,
			width: 60
		}, {
			name: 'hrJobName',
			display: '¼��ְλ',
			sortable: true,
			width: 80
		}, {
			name: 'hrIsManageJob',
			display: '�Ƿ�����',
			sortable: true,
			hide: true,
			hide: true
		}, {
			name: 'useHireTypeName',
			display: '¼����ʽ',
			sortable: true,
			width: 60
		}, {
			name: 'useAreaName',
			display: '���������֧������',
			sortable: true
		}, {
			name: 'sysCompanyName',
			display: '������˾',
			sortable: true,
			width: 60
		}, {
			name: 'personLevel',
			display: '�����ȼ�',
			sortable: true,
			width: 60
		}, {
			name: 'probation',
			display: '������(��)',
			sortable: true,
			width: 60
		}, {
			name: 'contractYear',
			display: '��ͬ����(��)',
			sortable: true,
			width: 60
		}, {
			name: 'useSign',
			display: 'ǩ������ҵ����Э�顷',
			sortable: true,
			width: 110
		}, {
			name: 'entryRemark',
			display: '��ְ���ȱ�ע',
			sortable: true
		}, {
			name: 'formDate',
			display: '��������',
			sortable: true,
			width: 80,
			hide: true
		}, {
			name: 'applyCode',
			display: 'ְλ������',
			sortable: true,
			process: function (v, row) {
				return "<a href='#' onclick='showOpenWin(\"?model=hr_recruitment_employment&action=toView&id=" +
					row.applyId +
					"\")'>" + v + "</a>";
			},
			hide: true
		}, {
			name: 'developPositionName',
			display: '�з�ְλ',
			sortable: true,
			width: 60,
			hide: true
		}, {
			name: 'useDemandEqu',
			display: '�����豸',
			sortable: true,
			hide: true
		}, {
			name: 'leaveReason',
			display: 'Ա����ְ����ԭ��',
			sortable: true,
			width: 250
		}],

		lockCol: ['formCode', 'userName', 'stateC'], //����������

		toViewConfig: {
			toViewFn: function (p, g) {
				var rowObj = g.getSelectedRow();
				if (rowObj) {
					var rowData = rowObj.data('data');
					showOpenWin("?model=hr_recruitment_entryNotice&action=toView&id=" + rowData[p.keyField]);
				} else {
					alert('��ѡ��һ�м�¼��');
				}
			}
		},

		// ��չ�Ҽ�
		menusEx: [{
			name: 'resume',
			text: '�鿴��������',
			icon: 'view',
			showMenuFn: function (row) {
				if (row.resumeId > 0) {
					return true;
				}
				return false;
			},
			action: function (row, rows, grid) {
				if (row) {
					showModalWin('?model=hr_recruitment_resume&action=toView&id=' + row.resumeId, '1');
				}
			}
		}, {
			name: 'jobApply',
			text: '�鿴����ְλ����',
			icon: 'view',
			showMenuFn: function (row) {
				if (row.applyId > 0) {
					return true;
				}
				return false;
			},
			action: function (row, rows, grid) {
				if (row) {
					showModalWin('?model=hr_recruitment_employment&action=toView&id=' + row.applyId, '1');
				}
			}
		}, {
			name: 'apply',
			text: '�鿴������Ա����',
			icon: 'view',
			showMenuFn: function (row) {
				if (row.sourceId > 0) {
					return true;
				}
				return false;
			},
			action: function (row, rows, grid) {
				if (row) {
					showModalWin('?model=hr_recruitment_apply&action=toView&id=' + row.sourceId,
						'1');
				}
			}
		}, {
			text: '�鿴������ְԭ��',
			icon: 'view',
			showMenuFn: function (row) {
				if (row.state == 3) {
					return true;
				}
				return false;
			},
			action: function (row, rows, grid) {
				if (row) {
					showThickboxWin('?model=hr_recruitment_entryNotice&action=toViewCancel&id=' + row.id +
						'&placeValuesBefore&TB_iframe=true&modal=false&height=350&width=700'
					);
				}
			}
		}, {
			text: '�鿴��ְԭ��',
			icon: 'view',
			showMenuFn: function (row) {
				if (row.state == 2 && row.departReason != '') {
					return true;
				}
				return false;
			},
			action: function (row, rows, grid) {
				if (row) {
					showThickboxWin("?model=hr_recruitment_entryNotice&action=toViewDepart&id=" + row.id +
						"&placeValuesBefore&TB_iframe=true&modal=false&height=300&width=700");
				}
			}
		}, {
			text: '�༭������ְԭ��',
			icon: 'edit',
			showMenuFn: function (row) {
				if (row.state == 3) {
					return true;
				}
				return false;
			},
			action: function (row, rows, grid) {
				if (row) {
					showThickboxWin("?model=hr_recruitment_entryNotice&action=toEditCancel&id=" + row.id +
						"&placeValuesBefore&TB_iframe=true&modal=false&height=350&width=700");
				}
			}
		}, {
			text: '����ְλ�����',
			icon: 'edit',
			showMenuFn: function (row) {
				if (row.applyId == 0 || row.applyId == '') {
					return true;
				}
				return false;
			},
			action: function (row, rows, grid) {
				if (row) {
					showThickboxWin("?model=hr_recruitment_entryNotice&action=toLinkApply&id=" + row.id +
						"&placeValuesBefore&TB_iframe=true&modal=false&height=350&width=650"
					);
				}
			}
		}, {
			text: '��д��ְԭ��',
			icon: 'add',
			showMenuFn: function (row) {
				if (row.state == 2) {
					return true;
				}
				return false;
			},
			action: function (row, rows, grid) {
				if (row) {
					showThickboxWin("?model=hr_recruitment_entryNotice&action=toAddDepart&id=" + row.id +
						"&placeValuesBefore&TB_iframe=true&modal=false&height=300&width=700");
				}
			}
		}],

		searchitems: [{
			display: "���ݱ��",
			name: 'formCode'
		}, {
			display: "����",
			name: 'userName'
		}, {
			display: "�������",
			name: 'resumeCode'
		}, {
			display: "������ԴС��",
			name: 'hrSourceType2Name'
		}, {
			display: "��ְ����",
			name: 'entryDate'
		}, {
			display: "�Ա�",
			name: 'sex'
		}, {
			display: "��ϵ�绰",
			name: 'phone'
		}, {
			display: "��������",
			name: 'email'
		}, {
			display: "��ְЭ����",
			name: 'assistManName'
		}, {
			display: "���˲���",
			name: 'deptName'
		}, {
			display: "�����ص�",
			name: 'workPlace'
		}, {
			display: "�籣�����",
			name: 'socialPlace'
		}, {
			display: "¼��ְλ",
			name: 'hrJobName'
		}, {
			display: "�Ƿ�����",
			name: 'hrIsManageJob'
		}, {
			display: "���������֧������",
			name: 'useAreaName'
		}, {
			display: "ǩ������ҵ����Э�顷",
			name: 'useSign'
		}, {
			display: "������",
			name: 'probation'
		}, {
			display: "��ͬ����",
			name: 'contractYear'
		}]
	});
});