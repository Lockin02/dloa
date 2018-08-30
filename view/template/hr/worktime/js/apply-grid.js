var show_page = function (page) {
	$("#applyGrid").yxgrid("reload");
};

$(function () {
	$("#applyGrid").yxgrid({
		model: 'hr_worktime_apply',
		title: '�����ڼ��������',
		bodyAlign: 'center',
		isDelAction: false,
		showcheckbox: false,
		param: {
			userAccount: $("#userAccount").val()
		},
		menusEx: [{
			name: 'aduit',
			text: '�������',
			icon: 'view',
			showMenuFn: function (row) {
				if (row.ExaStatus == 'δ�ύ') {
					return false;
				}
				return true;
			},
			action: function (row, rows, grid) {
				if (row) {
					showThickboxWin("controller/common/readview.php?itemtype=oa_hr_worktime_apply&pid=" + row.id +
						"&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=800");
				}
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
					switch (row.wageLevelCode) {
					case "GZJBFGL":
						var auditType = '5';
						break; //�ǹ����
					case "GZJBJL":
						var auditType = '15';
						break; //��������
					case "GZJBZJ":
						var auditType = '25';
						break; //�ܼ�
					case "GZJBZG":
						var auditType = '35';
						break; //����
					case "GZJBFZ":
						var auditType = '35';
						break; //����
					}
					var billArea = '';
					$.ajax({
						type: 'POST',
						url: '?model=engineering_officeinfo_range&action=getRangeByProvinceAndDept',
						data: {
							provinceId: row.workProvinceId,
							deptId: row.belongDeptId
						},
						async: false,
						success: function (data) {
							billArea = data;
						}
					});
					showThickboxWin("controller/hr/worktime/ewf_index1.php?actTo=ewfSelect&billId=" + row.id
						+ "&billDept=" + row.belongDeptId + "&flowMoney=" + auditType + "&billArea=" + billArea + "&proSid=" + row.projectManagerId
						+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=450&width=600"');
				} else {
					alert("��ѡ��һ������");
				}
			}
		}, {
			name: 'cancel',
			text: '��������',
			icon: 'delete',
			showMenuFn: function (row) {
				if (row.ExaStatus == '��������') {
					return true;
				}
				return false;
			},
			action: function (row, rows, grid) {
				var ewfurl = 'controller/hr/worktime/ewf_index1.php?actTo=delWork&billId=';
				$.ajax({
					type: "POST",
					url: "?model=common_workflow_workflow&action=isAudited",
					data: {
						billId: row.id,
						examCode: 'oa_hr_worktime_apply'
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
									url: ewfurl,
									data: {
										"billId": row.id
									},
									async: false,
									success: function (data) {
										if (data) {
											alert('���سɹ���');
											$("#applyGrid").yxgrid("reload");
										}
									}
								});
							}
						}
					}
				});
			}
		}, {
			name: 'delete',
			text: 'ɾ��',
			icon: 'delete',
			showMenuFn: function (row) {
				if (row.ExaStatus == 'δ�ύ') {
					return true;
				}
				return false;
			},
			action: function (row, rows, grid) {
				if (row) {
					if (window.confirm("ȷ��Ҫɾ��?")) {
						$.ajax({
							type: "POST",
							url: "?model=hr_worktime_apply&action=ajaxdeletes",
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
		}],

		//����Ϣ
		colModel: [{
			display: 'id',
			name: 'id',
			sortable: true,
			hide: true
		}, {
			name: 'applyCode',
			display: '���뵥��',
			width: 180,
			sortable: true,
			process: function (v, row) {
				return
					'<a href="javascript:void(0)" title="����鿴" onclick="javascript:showThickboxWin(\'?model=hr_worktime_apply&action=toView&id=' +
					row.id + '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=600&width=800\')">' +
					"<font color = 'blue'>" + v + "</font>" + '</a>';
			}
		}, {
			name: 'userAccount',
			display: 'Ա���˻�',
			sortable: true,
			hide: true
		}, {
			name: 'userNo',
			display: 'Ա�����',
			width: 60,
			sortable: true
		}, {
			name: 'userName',
			display: 'Ա������',
			width: 60,
			sortable: true
		}, {
			name: 'deptName',
			display: 'ֱ������',
			width: 80,
			sortable: true
		}, {
			name: 'deptNameS',
			display: '��������',
			width: 80,
			sortable: true
		}, {
			name: 'deptNameT',
			display: '��������',
			width: 80,
			sortable: true
		}, {
			name: 'deptNameF',
			display: '�ļ�����',
			width: 80,
			sortable: true
		}, {
			name: 'jobName',
			display: 'ְλ',
			width: 100,
			sortable: true
		}, {
			name: 'applyDate',
			display: '��������',
			width: 70,
			sortable: true
		}, {
			name: 'holiday',
			display: '�Ӱ�ʱ��',
			width: 100,
			sortable: true,
			process: function (v) {
				var str = v.split(',');
				var holiday = '';
				var holidayInfo = '';
				var rs = '';
				for (var i = 0; i < str.length; i++) {
					holiday = str[i].substr(0, 10);
					holidayInfo = str[i].substr(-1);
					if (holidayInfo == '1') {
						holidayInfo = '����';
					} else if (holidayInfo == '2') {
						holidayInfo = '����';
					} else if (holidayInfo == '3') {
						holidayInfo = 'ȫ��';
					} else {
						holidayInfo = '';
					}
					rs += holiday + '&nbsp&nbsp' + holidayInfo + '<br>';
				}
				return rs;
			}
		}, {
			name: 'workBegin',
			display: '�ϰ࿪ʼʱ��',
			width: 70,
			sortable: true
		}, {
			name: 'beginIdentify',
			display: '��ʼ��/����',
			width: 65,
			sortable: true,
			process: function (v) {
				if (v == 1) {
					return '����';
				} else if (v == 2) {
					return '����';
				}
				return '';
			}
		}, {
			name: 'workEnd',
			display: '�ϰ����ʱ��',
			width: 70,
			sortable: true
		}, {
			name: 'endIdentify',
			display: '������/����',
			width: 65,
			sortable: true,
			process: function (v) {
				if (v == 1) {
					return '����';
				} else if (v == 2) {
					return '����';
				}
				return '';
			}
		}, {
			name: 'dayNo',
			display: '����',
			width: 40,
			sortable: true
		}, {
			name: 'workContent',
			display: '�ϰദ��������',
			width: 150,
			sortable: true
		}, {
			name: 'workProvince',
			display: '����ʡ��',
			width: 50,
			sortable: true
		}, {
			name: 'workProvinceId',
			display: '����ʡ��ID',
			sortable: true,
			hide: true
		}, {
			name: 'projectManager',
			display: '��Ŀ����',
			width: 50,
			sortable: true
		}, {
			name: 'projectManagerId',
			display: '��Ŀ����ID',
			sortable: true,
			hide: true
		}, {
			name: 'ExaStatus',
			display: '����״̬',
			width: 50,
			sortable: true
		}, {
			name: 'ExaDT',
			display: '����ʱ��',
			sortable: true
		}],

		lockCol: ['userName', 'userNo'], //����������

		toAddConfig: {
			toAddFn: function() {
				alert("���ã���OA�����ߣ��뵽��OA�ύ���롣лл��");
				return false;
			},
			formHeight: 600
		},
		toEditConfig: {
			showMenuFn: function (row) {
				if (row.ExaStatus == 'δ�ύ' || row.ExaStatus == '���') {
					return true;
				}
				return false;
			},
			action: 'toEdit',
			formWidth: 700,
			formHeight: 600
		},
		toDelConfig: {
			showMenuFn: function (row) {
				if (row.ExaStatus == 'δ�ύ') {
					return true;
				}
				return false;
			},
			formWidth: 700
		},
		toViewConfig: {
			formHeight: 800,
			action: 'toView'
		},

		//��������
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
			}, {
				text: '���',
				value: '���'
			}]
		}],

		searchitems: [{
			display: "���뵥��",
			name: 'applyCodeS'
		}, {
			display: "ֱ������",
			name: 'deptName'
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
			display: "��������",
			name: 'applyDate'
		}, {
			display: "�Ӱ�ʱ��",
			name: 'holiday'
		}]
	});
});