var show_page = function (page) {
	$("#certifyapplyGrid").yxgrid("reload");
};

$(function () {
	//��ͷ��ť����
	buttonsArr = [{
		name: 'view',
		text: "�߼���ѯ",
		icon: 'view',
		action: function () {
			alert('������δ�������');
		}
	}];

	//��ͷ��ť����
	excelOutArr = {
		name: 'exportIn',
		text: "����",
		icon: 'excel',
		action: function (row) {
			showThickboxWin("?model=hr_personnel_certifyapply&action=toExcelIn" +
				"&placeValuesBefore&TB_iframe=true&modal=false&height=400&width=600")
		}
	};

	buttonsArr.push(excelOutArr);

	$("#certifyapplyGrid").yxgrid({
		model: 'hr_personnel_certifyapply',
		title: '��ְ�ʸ���Ϣ',
		param: {
			userNo: $('#userNo').val()
		},
		showcheckbox: false,
		isAddAction: false,
		isEditAction: false,
		isDelAction: false,
		//����Ϣ
		colModel: [{
			display: 'id',
			name: 'id',
			sortable: true,
			hide: true
		}, {
			name: 'userNo',
			display: 'Ա�����',
			sortable: true
		}, {
			name: 'userAccount',
			display: 'Ա���˺�',
			sortable: true,
			hide: true
		}, {
			name: 'userName',
			display: 'Ա������',
			sortable: true,
			process: function (v, row) {
				return "<a href='#' onclick='showThickboxWin(\"?model=hr_personnel_certifyapply&action=toView&id=" + row.id +
					'&skey=' + row.skey_ +
					"&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=900\")'>" + v + "</a>";
			}
		}, {
			name: 'deptName',
			display: '��������',
			sortable: true,
			hide: true
		}, {
			name: 'deptId',
			display: '����Id',
			sortable: true,
			hide: true
		}, {
			name: 'applyDate',
			display: '��������',
			sortable: true,
			width: 80
		}, {
			name: 'careerDirectionName',
			display: '����ͨ��',
			sortable: true,
			width: 80
		}, {
			name: 'baseLevelName',
			display: '���뼶��',
			sortable: true,
			width: 70
		}, {
			name: 'baseGradeName',
			display: '���뼶��',
			sortable: true,
			width: 70
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
			name: 'ExaStatus',
			display: '�������',
			sortable: true,
			width: 70
		}, {
			name: 'ExaDT',
			display: '��������',
			sortable: true,
			hide: true,
			width: 70
		}, {
			name: 'baseScore',
			display: '���Ե÷�',
			sortable: true,
			width: 70
		}, {
			name: 'baseResult',
			display: '���Խ��',
			sortable: true,
			process: function (v) {
				if (v == '1') {
					return 'ͨ��';
				} else if (v == '0') {
					return '��ͨ��';
				}
			},
			width: 70
		}, {
			name: 'finalResult',
			display: '��֤���',
			sortable: true,
			process: function (v) {
				if (v == '1') {
					return 'ͨ��';
				} else if (v == '0') {
					return '��ͨ��';
				}
			},
			width: 70
		}, {
			name: 'finalScore',
			display: '��֤�÷�',
			sortable: true,
			width: 70
		}, {
			name: 'finalCareerName',
			display: '��֤ͨ��',
			sortable: true,
			width: 80
		}, {
			name: 'finalLevelName',
			display: '��֤����',
			sortable: true,
			width: 70
		}, {
			name: 'finalTitleName',
			display: '��֤��ν',
			sortable: true,
			width: 80
		}, {
			name: 'finalGradeName',
			display: '��֤����',
			sortable: true,
			width: 70
		}, {
			name: 'finalDate',
			display: '��֤�����Ч����',
			sortable: true,
			width: 70
		}],

		lockCol: ['userNo', 'userName'], //����������

		toEditConfig: {
			action: 'toEdit',
			formHeight: 500,
			formWidth: 900
		},
		toViewConfig: {
			action: 'toView',
			formHeight: 500,
			formWidth: 900
		},

		searchitems: [{
			display: "Ա�����",
			name: 'userNoSearch'
		}, {
			display: "Ա������",
			name: 'userNameSearch'
		}, {
			display: "��������",
			name: 'deptName'
		}, {
			display: "��������",
			name: 'applyDateSearch'
		}, {
			display: "����ͨ��",
			name: 'careerDirectionNameSearch'
		}, {
			display: "��֤����",
			name: 'certifyDirection'
		}, {
			display: "��֤�����Ч����",
			name: 'finalDateSearch'
		}, {
			display: "��ע",
			name: 'remark'
		}]
	});
});