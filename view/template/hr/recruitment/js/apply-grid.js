var show_page = function (page) {
	$("#applyGrid").yxgrid("reload");
};

$(function () {

	buttonsArr = [];

	//��ͷ��ť����
	excelInArr = {
		name: 'exportOut',
		text: "����",
		icon: 'excel',
		action: function (row) {
			showThickboxWin("?model=hr_recruitment_apply&action=toExcelIn&placeValuesBefore&TB_iframe=true&modal=false&height=400&width=900");
		}
	};

	//��ͷ��ť����
	excelOutArr = {
		name: 'exportOut',
		text: "����",
		icon: 'excel',
		action: function (row) {
			showThickboxWin("?model=hr_recruitment_apply&action=toExcelOut&placeValuesBefore&TB_iframe=true&modal=false&height=500&width=900");
		}
	};

	//��ͷ��ť����
	highSearch = {
		name: 'view',
		text: "�߼�����",
		icon: 'view',
		action: function (row) {
			showThickboxWin("?model=hr_recruitment_apply&action=toSearch&placeValuesBefore&TB_iframe=true&modal=false&height=450&width=950");
		}
	};

	$.ajax({
		type: 'POST',
		url: '?model=hr_recruitment_apply&action=getLimits',
		data: {
			'limitName': '����Ȩ��'
		},
		async: false,
		success: function (data) {
			if (data = 1) {
				buttonsArr.push(excelOutArr);
				buttonsArr.push(excelInArr);
				buttonsArr.push(highSearch);
			}
		}
	});

	$("#applyGrid").yxgrid({
		model: 'hr_recruitment_apply',
		action: "pageJsonList",
		title: '��Ա����',
		isDelAction: false,
		isAddAction: false,
		isEditAction: false,
		showcheckbox: false,
		param: {
			state_d: '0'
		},
		isOpButton: false,
		bodyAlign: 'center',
		customCode: 'hr_recruitment_apply_grid',

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
					return "<a href='#' onclick='showOpenWin(\"?model=hr_recruitment_apply&action=toTabView&id=" + row.id + "\",1)'>" + v + "</a>";
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
			width: 40
		}, {
			name: 'formManName',
			display: '�����',
			width: 70,
			sortable: true
		}, {
			name: 'resumeToName',
			display: '�ӿ���',
			width: 70,
			sortable: true
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
		// 	name: 'tutor',
		// 	display: '��ʦ',
		// 	width: 60,
		// 	sortable: true
		// }, {
		// 	name: 'computerConfiguration',
		// 	display: '��������',
		// 	width: 60,
		// 	sortable: true
		// }, {
			name: 'formDate',
			display: '�������',
			width: 80,
			sortable: true
		}, {
			name: 'ExaDT',
			display: '����ͨ��ʱ��',
			width: 120,
			sortable: true,
			process: function (v, row) {
				if (row.state >= 1 && row.state <= 7) {
					return v;
				} else {
					return '';
				}
			}
		}, {
			name: 'assignedDate',
			display: '�´�����',
			width: 80,
			sortable: true
		}, {
			name: 'createTime',
			display: '¼������',
			sortable: true,
			process: function (v) {
				v = v.substring(0, 10);
				return v;
			}
		}, {
			name: 'createTime',
			display: '��һ��offerʱ��',
			sortable: true,
			process: function (v) {
				v = v.substring(0, 10);
				return v;
			}
		}, {
			name: 'lastOfferTime',
			display: '���һ��offerʱ��',
			sortable: true,
			process: function (v) {
				v = v.substring(0, 10);
				return v;
			}
		}, {
			name: 'entryDate',
			display: '����ʱ��',
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
			name: 'editHeadTime',
			display: '�޸ĸ�����ʱ��',
			width: 130,
			sortable: true
		}, {
			name: 'userName',
			display: '¼������',
			sortable: true,
			width: 200,
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

		comboEx: [{
			text: '����״̬',
			key: 'state',
			data: [{
				text: '�ύ',
				value: '8'
			}, {
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

		menusEx: [{
			text: '�޸�',
			icon: 'edit',
			showMenuFn: function (row) {
				if (row.stateC == "�ύ" && row.ExaStatus == 'δ�ύ') {
					return true;
				}
				return false;
			},
			action: function (row, rows, grid) {
				if (row) {
					showModalWin("?model=hr_recruitment_apply&action=toEdit&id=" + row.id + "&skey=" + row['skey_'] + "&editFromApply=1", 1);
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
				if (row) {
					if (row.deptId == '130' || row.postType == 'YPZW-WY') {
						showModalWin("?model=hr_recruitment_apply&action=toAuditEdit&id=" + row.id + "&isAudit=no" + "&skey=" + row['skey_'], 1);
					} else {
						showModalWin("?model=hr_recruitment_apply&action=toAuditEdit&id=" + row.id + "&skey=" + row['skey_'], 1);
					}
				}
			}
		}, {
			text: '�޸Ĺؼ�Ҫ��',
			icon: 'edit',
			action: function (row) {
				if (row) {
					showModalWin("?model=hr_recruitment_apply&action=toEditKeyPoints&id=" + row.id + "&act=page", 1);
				}
			}
		}, {
			text: '���为����',
			icon: 'edit',
			showMenuFn: function (row) {
				if (row.state == 1) {
					return true;
				} else {
					return false;
				}
			},
			action: function (row) {
				showThickboxWin("?model=hr_recruitment_apply&action=toGive&id=" + row.id + "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=900");
			}
		}, {
			text: '�޸ĸ�����',
			icon: 'edit',
			showMenuFn: function (row) {
				if (row.state == 2) {
					return true;
				} else {
					return false;
				}
			},
			action: function (row) {
				showThickboxWin("?model=hr_recruitment_apply&action=toEditHead&id=" + row.id + "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=450&width=800");
			}
		}, {
			text: '�޸�¼������',
			icon: 'edit',
			showMenuFn: function (row) {
				if (row.state != 4 && row.ExaStatus == '���') {
					return true;
				} else {
					return false;
				}
			},
			action: function (row) {
				showThickboxWin("?model=hr_recruitment_apply&action=toEditEmploy&id=" + row.id + "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=450&width=900");
			}
		}, {
			name: 'sumbit',
			text: '�ύ����',
			icon: 'edit',
			showMenuFn: function (row) {
				if (row.ExaStatus == 'δ�ύ' || row.ExaStatus == '���') {
					return true;
				}
				return false;
			},
			action: function (row, rows, grid) {
				if (row) {
					showThickboxWin("?model=hr_recruitment_apply&action=ewf&id=" + row.id + '&examCode=oa_hr_recruitment_apply&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=450&width=600"');
				} else {
					alert("��ѡ��һ������");
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
					showThickboxWin("controller/common/readview.php?itemtype=oa_hr_recruitment_apply&pid=" + row.id + "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=800");
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
						+ "&state=3&placeValuesBefore&TB_iframe=true&modal=false&height=400&width=750");
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
						+ "&state=7&placeValuesBefore&TB_iframe=true&modal=false&height=400&width=750");
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
						+ "&state=2&placeValuesBefore&TB_iframe=true&modal=false&height=400&width=750");
				}
			}
		}, {
			text: '��������',
			icon: 'delete',
			showMenuFn: function (row) {
				if (row.ExaStatus == '��������') {
					return true;
				} else {
					return false;
				}
			},
			action: function (row) {
				if (row) {
					$.ajax({
						type: "POST",
						url: "?model=common_workflow_workflow&action=isAudited",
						data: {
							billId: row.id,
							examCode: 'oa_hr_recruitment_apply'
						},
						success: function (msg) {
							if (msg == '1') {
								alert('�����Ѿ�����������Ϣ�����ܳ���������');
								$("#leaveGrid").yxgrid("reload");
								return false;
							} else {
								if (confirm('ȷ��Ҫ����������')) {
									$.ajax({
										type: "GET",
										url: "?model=hr_recruitment_apply&action=delewf",
										data: {
											id: row.id
										},
										async: false,
										success: function (ewfurl) {
											if (ewfurl) {
												$.ajax({
													type: "GET",
													url: ewfurl,
													data: {
														"billId": row.id
													},
													async: false,
													success: function (msg) {
														if (msg) {
															alert('���سɹ���');
															$("#applyGrid").yxgrid("reload");
														}
													}
												})
											}
										}
									});
								}
							}
						}
					});
				}
			}
		}, {
			text: '��ص���',
			icon: 'delete',
			showMenuFn: function (row) {
				if (row.ExaStatus == 'δ�ύ' && row.stateC == '�ύ') {
					return true;
				} else {
					return false;
				}
			},
			action: function (row) {
				if (row) {
					$.ajax({
						type: "POST",
						url: "?model=hr_recruitment_apply&action=getBack",
						data: {
							id: row.id
						},
						success: function (msg) {
							if (msg) {
								alert("�����ɹ���");
								$("#applyGrid").yxgrid("reload");
							} else {
								alert("����ʧ�ܣ�");
								$("#applyGrid").yxgrid("reload");
							}
						}
					});
				}
			}
		}, {
			text: '������ͣ��¼',
			icon: 'view',
			showMenuFn: function (row) {
				if (row.stopStart != '') {
					return true;
				} else {
					return false;
				}
			},
			action: function (row) {
				if (row) {
					showThickboxWin("?model=hr_recruitment_apply&action=toViewStartstop&id=" + row.id + "&placeValuesBefore&TB_iframe=true&modal=false&height=450&width=900");
				}
			}
		}, {
			text: 'ȡ����Ƹԭ��',
			icon: 'view',
			showMenuFn: function (row) {
				if (row.state == 7) {
					return true;
				} else {
					return false;
				}
			},
			action: function (row) {
				if (row) {
					showThickboxWin("?model=hr_recruitment_apply&action=toViewCancel&id=" + row.id + "&placeValuesBefore&TB_iframe=true&modal=false&height=450&width=900");
				}
			}
		}],

		buttonsEx: buttonsArr,

		toViewConfig: {
			toViewFn: function (p, g) {
				if (g) {
					var get = g.getSelectedRow().data('data');
					showOpenWin("?model=hr_recruitment_apply&action=toTabView&id=" + get [p.keyField] + "&ExaStatus=" + get ['ExaStatus'], '1');
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
			display: '¼������',
			name: 'createTimeSea'
		}, {
			display: '����ʱ��',
			name: 'entryDateSea'
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
			display: '¼������',
			name: 'userName'
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