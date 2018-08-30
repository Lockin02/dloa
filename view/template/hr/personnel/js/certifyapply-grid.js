var show_page = function (page) {
	$("#certifyapplyGrid").yxgrid("reload");
};

//ɾ���ظ������IE�ļ��������⣩
function uniqueArray(a) {
	temp = new Array();
	for (var i = 0; i < a.length; i++) {
		if (!contains(temp, a[i])) {
			temp.length += 1;
			temp[temp.length - 1] = a[i];
		}
	}
	return temp;
}

function contains(a, e) {
	for (j = 0; j < a.length; j++)
		if (a[j] == e) return true;
	return false;
}

$(function () {
	//��ͷ��ť����
	buttonsArr = [{
		name: 'return',
		text: '��֤����ͨ��',
		icon: 'edit',
		action: function (row, rows, grid) {
			if (rows) {
				var checkedRowsIds = $("#certifyapplyGrid").yxgrid("getCheckedRowIds"); //��ȡѡ�е�id
				var states = []; //����״̬����
				$.each(rows, function (i, n) {
					var o = eval(n);
					states.push(o.status);
				});
				states.sort();
				var uniqueState = uniqueArray(states);
				var stateLength = uniqueState.length;
				if (stateLength == 1 && uniqueState[0] == 1) { //�жϵ��ݵ�״̬�Ƿ�Ϊ��δ����������ֻ��һ��״̬
					if (window.confirm("ȷ������ͨ��?")) {
						$.ajax({
							type: "POST",
							url: "?model=hr_personnel_certifyapply&action=aduitPass",
							data: {
								applyIds: checkedRowsIds
							},
							success: function (msg) {
								if (msg == 1) {
									alert('�����ɹ�!');
									show_page();
								} else {
									alert('����ʧ��!');
									show_page();
								}
							}
						});
					}
				} else {
					alert("��ѡ��״̬Ϊ'������'�ĵ���");
				}


			} else {
				alert("��ѡ�񵥾ݡ�");
			}

		}
	}];

	//excel����
	excelOutArr = {
		name: 'exportIn',
		text: "����",
		icon: 'excel',
		action: function (row) {
			showThickboxWin("?model=hr_personnel_certifyapply&action=toExcelIn" +
				"&placeValuesBefore&TB_iframe=true&modal=false&height=400&width=600");
		}
	};
	//excel����
	excelOutButton = {
		name: 'exportIn',
		text: "����",
		icon: 'excel',
		action: function (row) {
			showThickboxWin("?model=hr_personnel_certifyapply&action=toExcelout" +
				"&placeValuesBefore&TB_iframe=true&modal=false&height=700&width=800");
		}
	};
	//excel�������
	exportUpdate = {
		name: 'exportUpdate',
		text: "�������",
		icon: 'excel',
		action: function (row) {
			showThickboxWin("?model=hr_personnel_certifyapply&action=toExcelUpdate" +
				"&placeValuesBefore&TB_iframe=true&modal=false&height=700&width=800");
		}
	};

	$.ajax({
		type: 'POST',
		url: '?model=hr_personnel_personnel&action=getLimits',
		data: {
			'limitName': '����Ȩ��'
		},
		async: false,
		success: function (data) {
			if (data == 1) {
				buttonsArr.push(excelOutArr);
				buttonsArr.push(excelOutButton);
				buttonsArr.push(exportUpdate);
			}
		}
	});

	$("#certifyapplyGrid").yxgrid({
		model: 'hr_personnel_certifyapply',
		action: 'pageJsonForRead',
		title: '��ְ�ʸ���Ϣ',
		isDelAction: false,
		isOpButton: false,
		bodyAlign: 'center',
		param: {
			statusArr: "1,2,3,4,5,6,7,8,9,10,11,12"
		},
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
			width: 70
		}, {
			name: 'userName',
			display: 'Ա������',
			sortable: true,
			width: 60,
			process: function (v, row) {
				return "<a href='#' onclick='showThickboxWin(\"?model=hr_personnel_certifyapply&action=toView&id=" + row.id +
					'&skey=' + row.skey_ +
					"&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=900\")'>" + v + "</a>";
			}
		}, {
			name: 'deptName',
			display: '��������',
			sortable: true
		}, {
			name: 'jobName',
			display: '��ְ��',
			sortable: true,
			width: 80
		}, {
			name: 'nowLevelName',
			display: '��������',
			sortable: true,
			width: 50
		}, {
			name: 'nowGradeName',
			display: '��������',
			sortable: true,
			width: 50
		}, {
			name: 'entryDate',
			display: '��ְ����',
			sortable: true,
			width: 70
		}, {
			name: 'applyDate',
			display: '��������',
			sortable: true,
			width: 70
		}, {
			name: 'careerDirectionName',
			display: '����ͨ��',
			sortable: true,
			width: 70
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
			name: 'baseScore',
			display: '���Ե÷�',
			sortable: true,
			process: function (v) {
				if (v == 0) {
					return '';
				} else {
					return v;
				}
			},
			width: 50
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
			width: 50
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
			width: 50
		}, {
			name: 'finalScore',
			display: '��֤�÷�',
			sortable: true,
			process: function (v) {
				if (v == 0) {
					return '';
				} else {
					return v;
				}
			},
			width: 50
		}, {
			name: 'finalCareerName',
			display: '��֤ͨ��',
			sortable: true,
			width: 80
		}, {
			name: 'finalLevelName',
			display: '��֤����',
			sortable: true,
			width: 50
		}, {
			name: 'finalGradeName',
			display: '��֤����',
			sortable: true,
			width: 50
		}, {
			name: 'finalTitleName',
			display: '��֤��ν',
			sortable: true,
			width: 80
		}, {
			name: 'finalDate',
			display: '��֤�����Ч����',
			sortable: true,
			width: 100
		}, {
			name: 'certifyDirectionName',
			display: '��֤����',
			sortable: true,
			width: 100
		}, {
			name: 'backReason',
			display: '���ԭ��',
			sortable: true,
			width: 220,
			align: 'left'
		}, {
			name: 'remark',
			display: '��ע',
			sortable: true,
			width: 220,
			align: 'left'
		}],

		lockCol: ['userNo', 'userName'], //����������

		buttonsEx: buttonsArr,
		menusEx: [{
			name: 'view',
			text: "�鿴��֤�����",
			icon: 'view',
			action: function (row) {
				showModalWin("?model=hr_personnel_certifyapply&action=toViewApply&id=" + row.id)
			}
		}, {
			name: 'delete',
			text: "���",
			icon: 'delete',
			showMenuFn: function (row) {
				if (row.status == '1') {
					return true;
				}
				return false;
			},
			action: function (row) {
				showThickboxWin("?model=hr_personnel_certifyapply&action=toBackApply&id=" + row.id +
					"&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=450&width=800")
			}
		}],

		toAddConfig: {
			formHeight: 500,
			formWidth: 900
		},
		toEditConfig: {
			action: 'toEdit',
			formHeight: 500,
			formWidth: 900,
			/**
			 * Ĭ�ϵ���༭��ť�����¼�
			 */
			toEditFn: function (p, g) {
				var c = p.toEditConfig;
				var w = c.formWidth ? c.formWidth : p.formWidth;
				var h = c.formHeight ? c.formHeight : p.formHeight;
				var rowObj = g.getSelectedRow();
				if (rowObj) {
					var rowData = rowObj.data('data');
					if (rowData.ExaDT != '' && rowData.ExaStatus != 'δ�ύ') {
						alert('��¼�Ѵ���������Ϣ��������༭');
						return false;
					}
					var keyUrl = "";
					if (rowData['skey_']) {
						keyUrl = "&skey=" + rowData['skey_'];
					}
					showThickboxWin("?model=" + p.model + "&action=" + c.action + c.plusUrl + "&id=" + rowData[p.keyField] +
						keyUrl + "&placeValuesBefore&TB_iframe=true&modal=false&height=" + h + "&width=" + w);
				} else {
					alert('��ѡ��һ�м�¼��');
				}
			}
		},
		toViewConfig: {
			action: 'toView',
			formHeight: 500,
			formWidth: 900
		},
		toDelConfig: {
			text: 'ɾ��',
			/**
			 * Ĭ�ϵ��ɾ����ť�����¼�
			 */
			toDelFn: function (p, g) {
				var rowIds = g.getCheckedRowIds();
				var rowObj = g.getCheckedRows();
				var key = "";
				if (rowObj.length > 0) {
					for (var i = 0; i < rowObj.length; i++) {
						if (rowObj[i].ExaDT != "") {
							alert('��¼[' + rowObj[i].id + ']' + rowObj[i].userName + ' �Ѿ�����������Ϣ��������ɾ��');
							return false;
						}
					}
				}
				if (rowIds[0]) {
					if (window.confirm("ȷ��Ҫɾ��?")) {
						$.ajax({
							type: "POST",
							url: "?model=" + p.model + "&action=" + p.toDelConfig.action + p.toDelConfig.plusUrl,
							data: {
								id: g.getCheckedRowIds()
									.toString(),
								skey: key
							},
							success: function (msg) {
								if (msg == 1) {
									alert('ɾ���ɹ�');
									show_page();
								} else {
									alert('ɾ��ʧ��');
									show_page();
								}
							}
						});
					}
				} else {
					alert('��ѡ��һ�м�¼��');
				}
			}
		},

		//��������
		comboEx: [{
			text: '����ͨ��',
			key: 'careerDirection',
			datacode: 'HRZYFZ'
		}, {
			text: '���뼶��',
			key: 'baseLevel',
			datacode: 'HRRZJB'
		}, {
			text: '��֤���',
			key: 'finalResult',
			data: [{
				text: '��ͨ��',
				value: '0'
			}, {
				text: 'ͨ��',
				value: '1'
			}]
		}, {
			text: '��֤ͨ��',
			key: 'finalCareer',
			datacode: 'HRZYFZ'
		}, {
			text: '��֤����',
			key: 'finalLevel',
			datacode: 'HRRZJB'
		}],

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