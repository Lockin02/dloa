var show_page = function (page) {
	$("#interviewGrid").yxgrid("reload");
};

$(function () {
	//��ͷ��ť����
	buttonsArr = [{
		name: 'add',
		text: '������������',
		icon: 'add',
		action: function () {
			showOpenWin('?model=hr_recruitment_interview&action=toAdd');
		}
	}, {
		name: 'add',
		text: "������������",
		icon: 'add',
		action: function (row) {
			showModalWin("?model=hr_recruitment_interview&action=toInterviewAdd", '1');
		}
	}, {
		name: 'view',
		text: "�߼�����",
		icon: 'view',
		action: function (row) {
			showThickboxWin("?model=hr_recruitment_interview&action=toSearch&placeValuesBefore&TB_iframe=true&modal=false&height=370&width=900");
		}
	}];


	excelInArr = {
		name: 'exportIn',
		text: "����",
		icon: 'excel',
		action: function (row) {
			showThickboxWin("?model=hr_recruitment_interview&action=toExcelIn&placeValuesBefore&TB_iframe=true&modal=false&height=400&width=600");
		}
	};

	$.ajax({
		type: 'POST',
		url: '?model=hr_recruitment_interview&action=getLimits',
		data: {
			'limitName': '����Ȩ��'
		},
		async: false,
		success: function (data) {
			if (data == 1) {
				buttonsArr.push(excelInArr);
			}
		}
	});

	var urseId = $("#userid").val();
	$("#interviewGrid").yxgrid({
		model: 'hr_recruitment_interview',
		title: '���Լ�¼',
		isDelAction: false,
		isEditAction: false,
		isAddAction: false,
		showcheckbox: false,
		isOpButton: false,
		param: {
			createId: urseId
		},
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
				return "<a href='#' onclick='showModalWin(\"?model=hr_recruitment_interview&action=toView&id=" + row.id + '&skey=' + row.skey_ + "\",1)'>" + v + "</a>";
			}
		}, {
			name: 'formDate',
			display: '��������',
			width: 70,
			sortable: true
		}, {
			name: 'userName',
			display: '����',
			width: 60,
			sortable: true
		}, {
			name: 'sexy',
			display: '�Ա�',
			width: 50,
			sortable: true
		}, {
			name: 'positionsName',
			display: 'ӦƸ��λ',
			sortable: true
		}, {
			name: 'deptState',
			display: '����ȷ��״̬',
			sortable: true,
			width: 70,
			process: function (v) {
				if (v == 1) {
					return "��ȷ��";
				} else {
					return "δȷ��";
				}
			}
		}, {
			name: 'hrState',
			display: '������Դȷ��״̬',
			sortable: true,
			width: 95,
			process: function (v) {
				if (v == 1) {
					return "��ȷ��";
				} else {
					return "δȷ��";
				}
			}
		}, {
			name: 'stateC',
			display: '״̬',
			width: 60
		}, {
			name: 'ExaStatus',
			display: '���״̬',
			width: 60,
			sortable: true
		}, {
			name: 'entryState',
			display: '��ְ״̬',
			width: 60,
			sortable: true,
			process: function (v) {
				switch (v) {
					case '1':
						return '����ְ';
					case '2':
						return '����ְ';
					case '3':
						return '������ְ';
					default:
						return '';
				}
			}
		}, {
			name: 'deptName',
			display: '���˲���',
			sortable: true
		}, {
			name: 'useInterviewResult',
			display: '���Խ��',
			width: 70,
			sortable: true,
			process: function (v) {
				if (v == 0) {
					return "�����˲�";
				} else {
					return "����¼��";
				}
			}
		}, {
			name: 'hrSourceType1Name',
			display: '������Դ����',
			sortable: true
		}, {
			name: 'hrSourceType2Name',
			display: '������ԴС��',
			sortable: true
		}],

		lockCol: ['formCode', 'formDate', 'userName'], //����������

		toViewConfig: {
			toViewFn: function (p, g) {
				var c = p.toViewConfig;
				var w = c.formWidth ? c.formWidth : p.formWidth;
				var h = c.formHeight ? c.formHeight : p.formHeight;
				var rowObj = g.getSelectedRow();
				if (rowObj) {
					var rowData = rowObj.data('data');
					var keyUrl = "";
					if (rowData['skey_']) {
						keyUrl = "&skey=" + rowData['skey_'];
					}
					showModalWin("?model=hr_recruitment_interview&action=toview&id=" + rowData[p.keyField] + keyUrl, 1);
				} else {
					alert('��ѡ��һ�м�¼��');
				}
			}
		},

		menusEx: [{
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
					showThickboxWin("controller/common/readview.php?itemtype=oa_hr_recruitment_interview&pid=" + row.id
						+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=350&width=800");
				}
			}
		}, {
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
					showModalWin('?model=hr_recruitment_resume&action=toView&id=' + row.resumeId
						+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=800', '1');
				}
			}
		}, {
			name: 'resume',
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
					showModalWin('?model=hr_recruitment_employment&action=toView&id=' + row.applyId
						+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=800', '1');
				}
			}
		}, {
			name: 'operationLog',
			text: '������־',
			icon: 'view',
			action: function (row, rows, grid) {
				showThickboxWin("?model=syslog_operation_logoperation&action=businessView&pkValue=" + row.id
					+ "&tableName=oa_hr_recruitment_interview"
					+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=850");
			}
		}, {
			text: '��д�������',
			icon: 'add',
			action: function (row) {
				showOpenWin("?model=hr_recruitment_interview&action=todeptedit&id=" + row.id, '1');
			},
			showMenuFn: function (row) {
				if (row.deptState == 0)
					return true;
				else
					return false;
			}
		}, {
			text: 'ȷ��HR��Ϣ',
			icon: 'add',
			action: function (row) {
				showOpenWin("?model=hr_recruitment_interview&action=tolastedit&id=" + row.id, '1');
			},
			showMenuFn: function (row) {
				if (row.hrState == 0 && row.deptState == 1) {
					return true;
				} else {
					return false;
				}
			}
		}, {
			text: '��������',
			icon: 'edit',
			action: function (row) {
				$.ajax({
					url: '?model=hr_recruitment_investigation&action=isToEdit',
					type: 'post',
					data: {
						"id": row.id
					},
					success: function (data) {
						data = eval("(" + data + ")");
						if (data.consultationName != null) {
							location = "?model=hr_recruitment_investigation&action=toEdit&id=" + row.id;
						} else {
							location = "?model=hr_recruitment_interview&action=toInvestigation&id=" + row.id;
						}
					}
				})
			}
		}, {
			text: '�ύ����',
			icon: 'add',
			showMenuFn: function (row) { //������ȷ��HRδȷ�ϣ��Ե�ר��������ִ������
				if (row.hrState == 0 && row.deptState == 1
						&& ((row.ExaStatus == "δ�ύ" || row.ExaStatus == "���") || (row.ExaStatus == '���' && row.changeTip == '1'))
						&& (row.parentDeptId == '130' || row.parentDeptId == '131')
						&& row.postType == 'YPZW-WY') {
					return true;
				} else {
					return false;
				}
			},
			action: function (row, rows, grid) {
				if (row) {
					location = 'controller/hr/recruitment/ewf_interview_notLocal_index.php?actTo=ewfSelect&billId=' + row.id
					+ '&billDept=' + row.deptId
					+ '&examCode=oa_hr_recruitment_interview&formName=������������';
				} else {
					alert("��ѡ��һ������");
				}
			}
		}, {
			text: '�ύ����',
			icon: 'add',
			showMenuFn: function (row) { //������ȷ�ϲ�����HRδȷ�ϣ��Ե�ר��������ִ������
				if (row.hrState == 1 && row.deptState == 1
						&& (row.ExaStatus == "���" || (row.ExaStatus == '���' && row.changeTip == '1'))
						&& (row.parentDeptId == '130' || row.parentDeptId == '131')
						&& row.postType == 'YPZW-WY' && row.state == '') {
					return true;
				} else {
					return false;
				}
			},
			action: function (row, rows, grid) {
				if (row) {
					location = 'controller/hr/recruitment/ewf_interview_fwmanage_index.php?actTo=ewfSelect&billId=' + row.id
					+ '&billDept=' + row.deptId
					+ '&examCode=oa_hr_recruitment_interview&formName=������������';
				} else {
					alert("��ѡ��һ������");
				}
			}
		}, {
			text: '�ύ����',
			icon: 'add',
			showMenuFn: function (row) {
				if (row.hrState == 1 && row.deptState == 1
						&& ((row.ExaStatus == "δ�ύ" || row.ExaStatus == "���") || (row.ExaStatus == '���' && row.changeTip == '1'))
						|| (row.parentDeptId == '130' && row.postType != 'YPZW-WY')) {
					return true;
				} else {
					return false;
				}
			},
			action: function (row, rows, grid) {
				if (row) {
					if ((row.parentDeptId == '130' || row.parentDeptId == '131') && row.postType != 'YPZW-WY') {
						location = 'controller/hr/recruitment/ewf_interview_fwmanage_index.php?actTo=ewfSelect&billId=' + row.id
						+ '&billDept=' + row.deptId
						+ '&examCode=oa_hr_recruitment_interview&formName=������������';
					} else {
						location = 'controller/hr/recruitment/ewf_interview_index.php?actTo=ewfSelect&billId=' + row.id
						+ '&billDept=' + row.deptId
						+ '&examCode=oa_hr_recruitment_interview&formName=������������';
					}
				} else {
					alert("��ѡ��һ������");
				}
			}
		}, {
			text: '�༭',
			icon: 'edit',
			showMenuFn: function (row) {
				if (row.ExaStatus == "δ�ύ" || row.ExaStatus == "���") {
					return true;
				} else {
					return false;
				}
			},
			action: function (row) {
				showModalWin("?model=hr_recruitment_interview&action=toManagerEdit&id=" + row.id);
			}
		}, {
			text: '�༭',
			icon: 'edit',
			showMenuFn: function (row) {
				if (row.ExaStatus == "���" && row.changeTip == '0') {
					return true;
				} else {
					return false;
				}
			},
			action: function (row) {
				showModalWin("?model=hr_recruitment_interview&action=toManagerEdit&audit=true&id=" + row.id);
			}
		}, {
			text: '��������',
			icon: 'edit',
			showMenuFn: function (row) {
				if (row.ExaStatus == "��������") {
					return true;
				} else {
					return false;
				}
			},
			action: function (row) {
				if (confirm('ȷ��Ҫ����������')) {
					$.ajax({
						type: "POST",
						url: "?model=common_workflow_workflow&action=isAuditedContract",
						data: {
							billId: row.id,
							examCode: 'oa_hr_recruitment_interview'
						},
						success: function (msg) {
							if (msg == '1') {
								alert('�����Ѿ�����������Ϣ�����ܳ���������');
								return false;
							} else {
								location = 'controller/hr/recruitment/ewf_interview_index.php?actTo=delWork&billId=' + row.id
								+ '&examCode=oa_hr_recruitment_interview&formName=������������';
							}
						}
					});
				}
			}
		}, {
			text: 'תΪ�����˲�',
			icon: 'edit',
			showMenuFn: function (row) {
				if (row.deptState == 1 && row.useInterviewResult != 0 && row.state != "2") {
					return true;
				} else {
					return false;
				}
			},
			action: function (row) {
				if (window.confirm(("ȷ��Ҫ�ύ��?"))) {
					$.ajax({
						type: "POST",
						url: "?model=hr_recruitment_interview&action=change",
						data: {
							id: row.id,
							type: row.interviewType
						},
						success: function (msg) {
							if (msg == 1) {
								alert('�ύ�ɹ���');
								show_page();
							}
						}
					});
				}
			}
		}, {
			text: 'תΪ����¼��',
			icon: 'edit',
			showMenuFn: function (row) {
				if (row.deptState == 1 && row.useInterviewResult == 0 && row.state != "2"
						&& (row.ExaStatus != "δ�ύ" || row.ExaStatus != "���")) {
					return true;
				} else {
					return false;
				}
			},
			action: function (row, rows, grid) {
				showModalWin("?model=hr_recruitment_interview&action=toManagerEdit&changeHire=true&id=" + row.id, '1');
			}
		}, {
			text: '����¼��֪ͨ',
			icon: 'add',
			showMenuFn: function (row) {
				if (row.ExaStatus == "���" && row.state == "1" && row.useInterviewResult == "1" && row.hrState == "1") {
					return true;
				} else {
					return false;
				}
			},
			action: function (row, rows, grid) {
				showModalWin("?model=hr_recruitment_entryNotice&action=toadd&id=" + row.id, '1');
			}
		}, {
			text: '����������',
			icon: 'add',
			action: function (row, rows, grid) {
				showModalWin("?model=hr_recruitment_interview&action=toManagerEdit&isCopy=true&id=" + row.id);
			}
		}],

		buttonsEx: buttonsArr,

		searchitems: [{
			display: '���ݱ��',
			name: 'formCode'
		}, {
			display: '��������',
			name: 'formDate'
		}, {
			display: '����',
			name: 'userNameSearch'
		}, {
			display: '�Ա�',
			name: 'sexy'
		}, {
			display: 'ӦƸ��λ',
			name: 'positionsNameSearch'
		}, {
			display: '���˲���',
			name: 'deptNamSearche'
		}, {
			display: '������Դ����',
			name: 'hrSourceType1Name'
		}, {
			display: '������ԴС��',
			name: 'hrSourceType2Name'
		}]
	});
});