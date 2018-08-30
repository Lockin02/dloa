var show_page = function (page) {
	$("#entryNoticeGrid").yxgrid("reload");
};

$(function () {
	$("#entryNoticeGrid").yxgrid({
		model: 'hr_recruitment_entryNotice',
		title: '¼��֪ͨ',
		isDelAction: false,
		isEditAction: false,
		isAddAction: false,
		showcheckbox: false,
		isOpButton: false,
		bodyAlign: 'center',
		param: {
			stateArr: "2,3"
		},

		buttonsEx: [{
			name: 'expport',
			text: "����",
			icon: 'excel',
			action: function (row) {
				showThickboxWin("?model=hr_recruitment_entryNotice&action=toExport&placeValuesBefore&TB_iframe=true&modal=false&height=350&width=700");
			}
		}],

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
				return "<a href='#' onclick='showOpenWin(\"?model=hr_recruitment_entryNotice&action=toView&id=" + row.id
					+ "\",1)'>" + v + "</a>";
			}
		}, {
			name: 'formDate',
			display: '��������',
			sortable: true,
			width: 80
		}, {
			name: 'interviewType',
			display: '��������',
			sortable: true,
			width: 70,
			process: function (v) {
				if (v == 1) {
					return "��Ա����";
				} else if (v == 2) {
					return "�ڲ��Ƽ�";
				}
			}
		}, {
			name: 'resumeCode',
			display: '�������',
			sortable: true,
			process: function (v, row) {
				return "<a href='#' onclick='showOpenWin(\"?model=hr_recruitment_resume&action=toRead&code=" + v + "\",1)'>" +
					v + "</a>";
			}
		}, {
			name: 'hrSourceType2Name',
			display: '������ԴС��',
			sortable: true
		}, {
			name: 'userName',
			display: '����',
			sortable: true,
			width: 60
		}, {
			name: 'entryDate',
			display: '��ְ����',
			sortable: true,
			width: 80
		}, {
			name: 'stateC',
			display: '״̬',
			width: 60
		}, {
			name: 'sex',
			display: '�Ա�',
			sortable: true,
			width: 50
		}, {
			name: 'phone',
			display: '��ϵ�绰',
			sortable: true
		}, {
			name: 'applyCode',
			display: 'ְλ������',
			sortable: true,
			width: 110,
			process: function (v, row) {
				return "<a href='#' onclick='showOpenWin(\"?model=hr_recruitment_employment&action=toView&id=" + row.applyId +
					"\",1)'>" + v + "</a>";
			}
		}, {
			name: 'email',
			display: '��������',
			sortable: true,
			width: 110
		}, {
			name: 'positionsName',
			display: 'ӦƸ��λ',
			sortable: true
		}, {
			name: 'developPositionName',
			display: '�з�ְλ',
			sortable: true,
			width: 60
		}, {
			name: 'deptName',
			display: '���˲���',
			sortable: true
		}, {
			name: 'workPlace',
			display: '�����ص�',
			sortable: true,
			width: 80,
			process: function (v, row) {
				return row.workProvince + ' - ' + row.workCity;
			}
		}, {
			name: 'useHireTypeName',
			display: '¼����ʽ',
			sortable: true

		}, {
			name: 'useAreaName',
			display: '���������֧������',
			sortable: true
		}, {
			name: 'assistManName',
			display: '��ְЭ����',
			sortable: true
		}, {
			name: 'useDemandEqu',
			display: '�����豸',
			sortable: true
		}, {
			name: 'useSign',
			display: 'ǩ������ҵ����Э�顷',
			sortable: true
		}, {
			name: 'probation',
			display: '������',
			sortable: true,
			width: 60
		}, {
			name: 'contractYear',
			display: '��ͬ����',
			sortable: true,
			width: 60
		}, {
			name: 'hrSourceType1Name',
			display: '������Դ����',
			sortable: true
		}, {
			name: 'hrJobName',
			display: '¼��ְλ����',
			sortable: true
		}, {
			name: 'hrIsManageJob',
			display: '�Ƿ�����',
			sortable: true,
			width: 80
		}, {
			name: 'leaveReason',
			display: 'Ա����ְ����ԭ��',
			sortable: true,
			width: 250
		}],

		lockCol: ['formCode', 'formDate', 'userName'], //����������

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
					showModalWin('?model=hr_recruitment_apply&action=toView&id=' + row.sourceId, '1');
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
			display: "��������",
			name: 'formDate'
		}, {
			display: "�������",
			name: 'resumeCode'
		}, {
			display: "������ԴС��",
			name: 'hrSourceType2Name'
		}, {
			display: "����",
			name: 'userName'
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
			display: "ְλ������",
			name: 'applyCode'
		}, {
			display: "��������",
			name: 'email'
		}, {
			display: "ӦƸ��λ",
			name: 'positionsName'
		}, {
			display: "�з�ְλ",
			name: 'developPositionName'
		}, {
			display: "���˲���",
			name: 'deptName'
		}, {
			display: "�����ص�",
			name: 'workPlace'
		}, {
			display: "¼����ʽ",
			name: 'useHireTypeName'
		}, {
			display: "���������֧������",
			name: 'useAreaName'
		}, {
			display: "��ְЭ����",
			name: 'assistManName'
		}, {
			display: "�����豸",
			name: 'useDemandEqu'
		}, {
			display: "ǩ������ҵ����Э�顷",
			name: 'useSign'
		}, {
			display: "������",
			name: 'probation'
		}, {
			display: "��ͬ����",
			name: 'contractYear'
		}, {
			display: "������Դ����",
			name: 'hrSourceType1Name'
		}, {
			display: "¼��ְλ����",
			name: 'hrJobName'
		}, {
			display: "�Ƿ�����",
			name: 'hrIsManageJob'
		}]
	});
});