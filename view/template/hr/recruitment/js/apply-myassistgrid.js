var show_page = function (page) {
	$("#applyGrid").yxgrid("reload");
};

$(function () {
	$("#applyGrid").yxgrid({
		model: 'hr_recruitment_apply',
		title: 'Э������Ա����',
		isDelAction: false,
		isAddAction: false,
		isEditAction: false,
		showcheckbox: false,
		isOpButton: false,
		bodyAlign: 'center',
		action: 'myHelpPageJson',
		customCode: 'hr_recruitment_apply_myhelp',

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
				if (row.viewType == 1) {
					return "<a href='#' onclick='showOpenWin(\"?model=hr_recruitment_apply&action=toTabView&id=" + row.id +
						"\",1)'>" + v + "</a>";
				} else {
					return "";
				}
			}
		}, {
			name: 'stateC',
			display: '����״̬',
			width: 60
		}, {
			name: 'ExaStatus',
			display: '����״̬',
			sortable: true,
			width: 70
		}, {
			name: 'formManName',
			display: '�����',
			width: 70,
			sortable: true
		}, {
			name: 'resumeToName',
			display: '�ӿ���',
			width: 150,
			sortable: true
		}, {
			name: 'deptName',
			display: '������',
			sortable: true
		}, {
			name: 'workPlace',
			display: '�����ص�',
			width: 70,
			sortable: true
		}, {
			name: 'postTypeName',
			display: 'ְλ����',
			width: 80,
			sortable: true
		}, {
			name: 'positionName',
			display: '����ְλ',
			sortable: true
		}, {
			name: 'positionNote',
			display: 'ְλ��ע',
			sortable: true,
			width: 180,
			process: function (v, row) {
				var tmp = '';
				if (row.developPositionName) {
					tmp += row.developPositionName + '��';
				}
				if (row.network) {
					tmp += row.network + '��';
				}
				if (row.device) {
					tmp += row.device;
				}
				return tmp;
			}
		}, {
			name: 'positionLevel',
			display: '����',
			width: 70,
			sortable: true
		}, {
			name: 'projectGroup',
			display: '������Ŀ��',
			width: 100,
			sortable: true
		}, {
			name: 'isEmergency',
			display: '�Ƿ����',
			sortable: true,
			width: 60,
			process: function (v, row) {
				if (v == "1") {
					return "��"
				} else if (v == "0") {
					return "��";
				} else {
					return "";
				}
			}
		}, {
			name: 'formDate',
			display: '�������',
			width: 80,
			sortable: true
		}, {
			name: 'hopeDate',
			display: 'ϣ������ʱ��',
			sortable: true
		}, {
			name: 'addType',
			display: '��Ա����',
			sortable: true
		}, {
			name: 'leaveManName',
			display: '��ְ/����������',
			sortable: true
		}, {
			name: 'needNum',
			display: '��������',
			width: 60,
			sortable: true,
			process: function (v, row) {
				if (v == "") {
					return 0;
				} else {
					return v;
				}
			}
		}, {
			name: 'entryNum',
			display: '����ְ����',
			width: 60,
			sortable: true,
			process: function (v, row) {
				if (v == "") {
					return 0;
				} else {
					return v;
				}
			}
		}, {
			name: 'beEntryNum',
			display: '����ְ����',
			width: 60,
			sortable: true,
			process: function (v, row) {
				if (v == "") {
					return 0;
				} else {
					return v;
				}
			}
		}, {
			name: 'stopCancelNum',
			display: '��ͣ/ȡ������',
			sortable: true,
			width: 90
		}, {
			name: 'ingtryNum',
			display: '����Ƹ����',
			width: 60,
			sortable: true,
			process: function (v, row) {
				return row.needNum - row.entryNum - row.beEntryNum - row.stopCancelNum;
			}
		}, {
			name: 'recruitManName',
			display: '��Ƹ������',
			width: 70,
			sortable: true
		}, {
			name: 'assistManName',
			display: '��ƸЭ����',
			sortable: true,
			width: 200
		}, {
			name: 'applyReason',
			display: '����ԭ��',
			width: 200,
			sortable: true
		}, {
			name: 'workDuty',
			display: '����ְ��',
			width: 200,
			sortable: true
		}, {
			name: 'jobRequire',
			display: '��ְҪ��',
			width: 200,
			sortable: true
		}, {
			name: 'keyPoint',
			display: '�ؼ�Ҫ��',
			width: 200,
			sortable: true
		}, {
			name: 'attentionMatter',
			display: 'ע������',
			width: 200,
			sortable: true
		}, {
			name: 'leaderLove',
			display: '�����쵼ϲ��',
			width: 200,
			sortable: true
		}, {
			name: 'applyRemark',
			display: '���ȱ�ע',
			sortable: true,
			width: 300
		}],

		lockCol: ['formCode', 'stateC', 'ExaStatus'], //����������

		menusEx: [{
			text: '�޸Ĺؼ�Ҫ��',
			icon: 'edit',
			action: function (row) {
				showModalWin("?model=hr_recruitment_apply&action=toEditKeyPoints&id=" + row.id + "&act=myassistpage", 1);
			}
		}],

		comboEx: [{
			text: '����״̬',
			key: 'state',
			data: [{
				text: 'δ�´�',
				value: '1'
			}, {
				text: '��Ƹ��',
				value: '2'
			}, {
				text: '��ͣ',
				value: '3'
			}, {
				text: '���',
				value: '4'
			}, {
				text: '�ر�',
				value: '5'
			}, {
				text: '����',
				value: '6'
			}, {
				text: 'ȡ��',
				value: '7'
			}]
		}, {
			text: '�Ƿ����',
			key: 'isEmergency',
			data: [{
				text: '��',
				value: '1'
			}, {
				text: '��',
				value: '0'
			}]
		}],

		toEditConfig: {
			action: 'toEdit'
		},
		toViewConfig: {
			toViewFn: function (p, g) {
				if (g) {
					var get = g.getSelectedRow().data('data');
					showOpenWin("?model=hr_recruitment_apply&action=toTabView&id=" + get[p.keyField], '1');
				}
			}
		},

		searchitems: [{
			display: "���ݱ��",
			name: 'formCode'
		}, {
			display: '�����',
			name: 'formManName'
		}, {
			display: '�ӿ���',
			name: 'resumeToNameSearch'
		}, {
			display: "������",
			name: 'deptName'
		}, {
			display: "ְλ����",
			name: 'postTypeName'
		}, {
			display: "����ְλ",
			name: 'positionName'
		}, {
			display: "�����ص�",
			name: 'workPlaceSearch'
		}, {
			display: "����",
			name: 'positionLevelSearch'
		}, {
			display: "������Ŀ��",
			name: 'projectGroupSearch'
		}, {
			display: '���ʱ��',
			name: 'formDate'
		}, {
			display: '����ͨ��ʱ��',
			name: 'ExaDTSea'
		}, {
			display: '��Ա����',
			name: 'addType'
		}, {
			display: '��ְ/����������',
			name: 'leaveManName'
		}, {
			display: '��Ƹ������',
			name: 'recruitManName'
		}, {
			display: '��ƸЭ����',
			name: 'assistManNameSearch'
		}, {
			display: '����ԭ��',
			name: 'applyReasonSearch'
		}, {
			display: '����ְ��',
			name: 'workDutySearch'
		}, {
			display: '��ְҪ��',
			name: 'jobRequireSearch'
		}, {
			display: '�ؼ�Ҫ��',
			name: 'keyPoint'
		}, {
			display: 'ע������',
			name: 'attentionMatter'
		}, {
			display: '�����쵼ϲ��',
			name: 'leaderLove'
		}, {
			display: '���ȱ�ע',
			name: 'applyRemarkSearch'
		}]
	});
});