var show_page = function (page) {
	$("#applyGrid").yxgrid("reload");
};

$(function () {
	$("#applyGrid").yxgrid({
		model: 'hr_recruitment_apply',
		action: "myListJson",
		title: '�ҵ���Ա����',
		//����Ϣ
		isAddAction: true,
		isEditAction: false,
		isViewAction: false,
		isDelAction: false,
		showcheckbox: false,
		isOpButton: false,
		bodyAlign: 'center',
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
				return "<a href='#' onclick='showOpenWin(\"?model=hr_recruitment_apply&action=toView&id=" + row.id + "\")'>" + v + "</a>";
			}
		}, {
			name: 'stateC',
			display: '״̬',
			width: 60
		}, {
			name: 'ExaStatus',
			display: '����״̬',
			sortable: true,
			width: 70
		}, {
			name: 'deptNameO',
			display: 'ֱ������',
			width: 70,
			sortable: true
		}, {
			name: 'deptNameS',
			display: '��������',
			width: 70,
			sortable: true
		}, {
			name: 'deptNameT',
			display: '��������',
			width: 70,
			sortable: true
		}, {
			name: 'deptNameF',
			display: '�ļ�����',
			width: 70,
			sortable: true
		}, {
			name: 'positionName',
			display: '����ְλ',
			sortable: true
		}, {
			name: 'needNum',
			display: '��������',
			sortable: true,
			width: 60,
			process: function (v) {
				if (v == "") {
					return 0;
				} else {
					return v;
				}
			}
		}, {
			name: 'entryNum',
			display: '����ְ����',
			sortable: true,
			width: 70,
			process: function (v) {
				if (v == "") {
					return 0;
				} else {
					return v;
				}
			}
		}, {
			name: 'beEntryNum',
			display: '����ְ����',
			sortable: true,
			width: 70,
			process: function (v) {
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
			name: 'userName',
			display: '¼������',
			sortable: true,
			width: 200,
			align: 'left',
			process: function (v, row) {
				if (v == '') {
					return row.employName;
				} else if (row.employName == '') {
					return v;
				} else {
					return v + ',' + row.employName;
				}
			}
		}, {
			name: 'createTime',
			display: '¼������',
			sortable: true,
			process: function (v) {
				v = v.substring(0, 10);
				return v;
			}
		}, {
			name: 'projectGroup',
			display: '������Ŀ��',
			sortable: true
		}, {
			name: 'workPlace',
			display: '�����ص�',
			sortable: true,
			width: 150
		}, {
			name: 'hopeDate',
			display: 'ϣ������ʱ��',
			sortable: true
		}, {
			name: 'addType',
			display: '��Ա����',
			sortable: true
		}, {
			name: 'recruitManName',
			display: '��Ƹ������',
			sortable: true
		}, {
			name: 'assistManName',
			display: '��ƸЭ����',
			sortable: true,
			width: 300
		}],

		lockCol: ['formCode', 'stateC', 'ExaStatus'], //����������

		toAddConfig: {
			toAddFn: function (p, g) {
				alert("���ã���OA�����ߣ��뵽��OA�ύ���롣лл��");
				return false;
				showModalWin("?model=hr_recruitment_apply&action=toAdd", 1);
			}
		},

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

		//��չ�Ҽ�
		menusEx: [{
			text: '�鿴',
			icon: 'view',
			action: function (row, rows, grid) {
				if (row) {
					showModalWin("?model=hr_recruitment_apply&action=toView&id=" + row.id + "&skey=" + row['skey_'], 1);
				}
			}
		}, {
			text: '�޸�',
			icon: 'edit',
			showMenuFn: function (row) {
				if (row.ExaStatus == "δ�ύ" || row.ExaStatus == "���") {
					return true;
				}
				return false;
			},
			action: function (row, rows, grid) {
//				if (row) {
//					showModalWin("?model=hr_recruitment_apply&action=toEdit&id=" + row.id + "&skey=" + row['skey_'], 1);
//				}
				if (window.confirm(("���ã���OA�����ߣ���ת����OA�����ύ����?"))) {
					location.href = "index1.php?model=common_otherdatas&action=toSignInAws";
				}
			}
		}, {
			text: '�޸�',
			icon: 'edit',
			showMenuFn: function (row) {
				if (row.ExaStatus == "���") {
					return true;
				}
				return false;
			},
			action: function (row, rows, grid) {
//				if (row) {
//					if (row.deptId == '130' || row.postType == 'YPZW-WY') {
//						showModalWin("?model=hr_recruitment_apply&action=toAuditEdit&id=" + row.id + "&isAudit=no" + "&skey=" + row['skey_'], 1);
//					} else {
//						showModalWin("?model=hr_recruitment_apply&action=toAuditEdit&id=" + row.id + "&skey=" + row['skey_'], 1);
//					}
//				}
				if (window.confirm(("���ã���OA�����ߣ���ת����OA�����ύ����?"))) {
					location.href = "index1.php?model=common_otherdatas&action=toSignInAws";
				}
			}
		}, {
			name: 'sumbit',
			text: '�ύ',
			icon: 'add',
			showMenuFn: function (row) {
				if (row.stateC == '����') {
					return true;
				}
				return false;
			},
			action: function (row, rows, grid) {
				if (row) {
//					if (window.confirm("ȷ��Ҫ�ύ?")) {
//						$.ajax({
//							type: "POST",
//							url: "?model=hr_recruitment_apply&action=ajaxSubmit",
//							data: {
//								id: row.id
//							},
//							success: function (msg) {
//								if (msg == 1) {
//									alert('�ύ�ɹ�!');
//									show_page();
//								} else {
//									alert('�ύʧ��!');
//									show_page();
//								}
//							}
//						});
//					}
					if (window.confirm(("���ã���OA�����ߣ���ת����OA�����ύ����?"))) {
						location.href = "index1.php?model=common_otherdatas&action=toSignInAws";
					}
				} else {
					alert("��ѡ��һ������");
				}
			}
		}, {
			text: 'ɾ��',
			icon: 'delete',
			showMenuFn: function (row) {
				if (row.stateC == "����") {
					return true;
				}
				return false;
			},
			action: function (row, rows, grid) {
				if (row) {
					if (window.confirm("ȷ��Ҫɾ��?")) {
						$.ajax({
							type: "POST",
							url: "?model=hr_recruitment_apply&action=ajaxdeletes",
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
					showThickboxWin("controller/common/readview.php?itemtype=oa_hr_recruitment_apply&pid=" + row.id
						+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=800");
				}
			}
		}, {
			text: '��ͣ',
			icon: 'delete',
			showMenuFn: function (row) {
				if (row.state == 2) {
					return true;
				} else {
					return false;
				}
			},
			action: function (row) {
				if (row) {
					showThickboxWin("?model=hr_recruitment_apply&action=tochangeState&id=" + row.id
						+ "&state=3&placeValuesBefore&TB_iframe=true&modal=false&height=350&width=650");
				}
			}
		}, {
			text: 'ȡ��',
			icon: 'delete',
			showMenuFn: function (row) {
				if (row.state == 2) {
					return true;
				} else {
					return false;
				}
			},
			action: function (row) {
				if (row) {
					showThickboxWin("?model=hr_recruitment_apply&action=tochangeState&id=" + row.id
						+ "&state=7&placeValuesBefore&TB_iframe=true&modal=false&height=350&width=650");
				}
			}
		}, {
			text: '����',
			icon: 'add',
			showMenuFn: function (row) {
				if (row.state == 3) {
					return true;
				} else {
					return false;
				}
			},
			action: function (row) {
				if (row) {
					showThickboxWin("?model=hr_recruitment_apply&action=tochangeState&id=" + row.id
						+ "&state=2&placeValuesBefore&TB_iframe=true&modal=false&height=350&width=650");
				}
			}
		}],

		searchitems: [{
			display: "���ݱ��",
			name: 'formCode'
		}, {
			display: "ֱ������",
			name: 'deptNameO'
		}, {
			display: "��������",
			name: 'deptNameS'
		}, {
			display: "��������",
			name: 'deptNameT'
		}, {
			display: "�ļ�����",
			name: 'deptNameF'
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
			display: "������Ŀ��",
			name: 'projectGroupSearch'
		}, {
			display: '��Ա����',
			name: 'addType'
		}, {
			display: '��Ƹ������',
			name: 'recruitManName'
		}, {
			display: '��ƸЭ����',
			name: 'assistManNameSearch'
		}],

		sortname: 'id',
		sortorder: 'desc'
	});
});