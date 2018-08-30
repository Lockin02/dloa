var show_page = function() {
	$("#esmMyWorklogGrid").yxgrid("reload");
};

$(function() {
	$("#esmMyWorklogGrid").yxgrid({
		model: 'engineering_worklog_esmworklog',
		title: '�ҵĹ�����־',
		action: 'myPageJson',
		isDelAction: false,
		isAddAction: false,
		showcheckbox: false,
		customCode: 'myesmworklog',
		isOpButton: false,
		// ����Ϣ
		colModel: [{
			display: 'id',
			name: 'id',
			sortable: true,
			hide: true
		}, {
			name: 'executionDate',
			display: '����',
			sortable: true,
			width: 70,
			process: function(v, row) {
				return "<a href='javascript:void(0)' onclick='showOpenWin(\"?model=engineering_worklog_esmworklog&action=toView&id=" + row.id + '&skey=' + row.skey_ + "\",1,750,1150)'>" + v + "</a>";
			}
		}, {
			name: 'country',
			display: '����',
			sortable: true,
			width: 70,
			hide: true
		}, {
			name: 'province',
			display: 'ʡ',
			sortable: true,
			width: 70,
			hide: true
		}, {
			name: 'city',
			display: '��',
			sortable: true,
			width: 70,
			hide: true
		}, {
			name: 'provinceCity',
			display: '���ڵ�',
			sortable: true,
			width: 80,
			hide: true
		}, {
			name: 'workStatus',
			display: '����״̬',
			sortable: true,
			width: 70,
			datacode: 'GXRYZT',
			hide: true
		}, {
			name: 'projectName',
			display: '��Ŀ',
			sortable: true,
			process: function(v) {
				return v;
			},
			width: 140
		}, {
			name: 'activityName',
			display: '����',
			sortable: true,
			process: function(v, row) {
				return "<a href='javascript:void(0)' onclick='showActivity(" + row.activityId + ")'>" + v + "</a>";
			}
		}, {
			name: 'workloadAndUnit',
			display: '�����',
			sortable: true,
			width: 60,
			process: function(v, row) {
				return v + " " + row.workloadUnitName;
			}
		}, {
			name: 'workloadDay',
			display: '�����',
			sortable: true,
			width: 60,
			hide: true
		}, {
			name: 'workloadUnitName',
			display: '��������λ',
			sortable: true,
			width: 60,
			hide: true
		}, {
			name: 'workProcess',
			display: '����',
			sortable: true,
			width: 70,
			process: function(v) {
				if (v * 1 == -1) {
					return " -- ";
				} else {
					return v + " %";
				}
			}
		}, {
			name: 'inWorkRate',
			display: 'Ͷ�빤������',
			sortable: true,
			width: 70,
			process: function(v) {
				return v + " %";
			}
		}, {
			name: 'description',
			display: '����������',
			sortable: true,
			width: 150
		}, {
			name: 'remark',
			display: '��ע˵��',
			sortable: true,
			hide: true
		}, {
			name: 'status',
			display: '�ܱ�״̬',
			sortable: true,
			width: 60,
			process: function(v) {
				if (v == "WTJ") {
					return "δ�ύ";
				} else if (v == "YTJ") {
					return "���ύ";
				} else if (v == 'YQR') {
					return "��ȷ��";
				} else {
					return "��ͨ��";
				}
			},
			hide: true
		}, {
			name: 'confirmStatus',
			display: 'ȷ��״̬',
			sortable: true,
			width: 60,
			process: function(v) {
				if (v == "1") {
					return "��ȷ��";
				} else {
					return "δȷ��";
				}
			}
		}, {
			name: 'costMoney',
			display: '¼�����',
			sortable: true,
			width: 70,
			process: function(v, row) {
				if (row.confirmStatus == '0') {
					return "<span class='green' title='δȷ�ϵķ���'>" + moneyFormat2(v) + "</span>";
				} else {
					return "<span class='blue' title='��ȷ�ϵķ���'>" + moneyFormat2(v) + "</span>";
				}
			}
		}, {
			name: 'confirmMoney',
			display: 'ȷ�Ϸ���',
			sortable: true,
			width: 70,
			process: function(v, row) {
				if (row.confirmStatus == '1' && v != row.costMoney) {
					return "<span class='blue'>" + moneyFormat2(v) + "</span>";
				} else {
					return moneyFormat2(v);
				}
			}
		}, {
			name: 'backMoney',
			display: '��ط���',
			sortable: true,
			width: 70,
			process: function(v, row) {
				if (v > 0) {
					return "<span class='red'>" + moneyFormat2(v) + "</span>";
//						return "<a href='javascript:void(0)' style='color:red;' onclick='reeditCost(\"" + row.id + "\")' title='������±༭����'>" + moneyFormat2(v) + "</a>";
				} else {
					return moneyFormat2(v);
				}
			}
		}, {
			name: 'confirmName',
			display: 'ȷ����',
			sortable: true,
			width: 80
		}, {
			name: 'confirmDate',
			display: 'ȷ������',
			sortable: true,
			width: 70
		}, {
			name: 'assessResultName',
			display: '��˽��',
			sortable: true,
			width: 70
		}, {
			name: 'feedBack',
			display: '��˽���',
			sortable: true
		}, {
			name: 'thisActivityProcess',
			display: '�����������',
			sortable: true,
			width: 80,
			hide: true
		}, {
			name: 'thisProjectProcess',
			display: '������Ŀ����',
			sortable: true,
			width: 80,
			hide: true
		}
		],
		toAddConfig: {
			toAddFn: function() {
				var height = 800;
				var width = 1150;
				var url = "?model=engineering_worklog_esmworklog&action=toAdd";
				window.open(url, "����OA",
					'top=0,left=0,menubar=0,toolbar=0,status=1,scrollbars=1,resizable=1,width='
					+ width + ',height=' + height);
			}
		},
		toEditConfig: {
			action: 'toEdit',
			showMenuFn: function(row) {
				return row.status == "WTJ" && row.confirmStatus == "0";
			},
			toEditFn: function(p, g) {
				var c = p.toEditConfig;
				var rowObj = g.getSelectedRow();
				if (rowObj) {
					var rowData = rowObj.data('data');
					var keyUrl = "";
					if (rowData['skey_']) {
						keyUrl = "&skey=" + rowData['skey_'];
					}
					//�ж�
					if (rowData.activityId == "") {
						var height = 800;
						var width = 1150;
						var url = "?model=" + p.model + "&action=" + c.action + c.plusUrl + "&id=" + rowData[p.keyField] + keyUrl;
						window.open(url, "����OA",
							'top=0,left=0,menubar=0,toolbar=0,status=1,scrollbars=1,resizable=1,width='
							+ width + ',height=' + height);
					} else {
						$.ajax({
							type: "POST",
							url: "?model=engineering_project_esmproject&action=isClose",
							data: {"id": rowData.projectId},
							async: false,
							success: function(data) {
								if (data == 1) {
									alert('��Ŀ�ѹرգ������ٶԴ���־���в���');
								} else {
									var height = 800;
									var width = 1150;
									var url = "?model=" + p.model + "&action=" + c.action + c.plusUrl + "&id=" + rowData[p.keyField] + keyUrl;
									window.open(url, "����OA",
										'top=0,left=0,menubar=0,toolbar=0,status=1,scrollbars=1,resizable=1,width='
										+ width + ',height=' + height);
								}
							}
						});
					}
				} else {
					alert('��ѡ��һ�м�¼��');
				}
			}
		},
		toViewConfig: {
			action: 'toView',
			toViewFn: function(p, g) {
				var c = p.toViewConfig;
				var rowObj = g.getSelectedRow();
				if (rowObj) {
					var rowData = rowObj.data('data');
					var keyUrl = "";
					if (rowData['skey_']) {
						keyUrl = "&skey=" + rowData['skey_'];
					}

					var url = "?model=" + p.model + "&action=" + c.action + c.plusUrl + "&id=" + rowData[p.keyField] + keyUrl;
					showOpenWin(url, 1, 700, 1150);
				} else {
					alert('��ѡ��һ�м�¼��');
				}
			}
		},
		buttonsEx: [{
			text: '������־',
			icon: 'add',
			action: function() {
				showOpenWin("?model=engineering_worklog_esmworklog&action=toBatchAdd", 1, 668, 1150, '���ն���־����');
			}
		}, {
			text: '������־',
			icon: 'excel',
			action: function() {
				showThickboxWin("?model=engineering_worklog_esmworklog&action=toExcelIn"
				+ "&placeValuesBefore&TB_iframe=true&modal=false&height=400&width=600")
			}
		}, {
			text: '������־',
			icon: 'excel',
			action: function() {
				showThickboxWin("?model=engineering_worklog_esmworklog&action=toExportMyLog"
				+ "&placeValuesBefore&TB_iframe=true&modal=false&height=400&width=600")
			}
		}/*,{
		 text : '������־����',
		 icon : 'excel',
		 action : function() {
		 showThickboxWin("?model=engineering_worklog_esmworklog&action=toCostExcelIn"
		 + "&placeValuesBefore&TB_iframe=true&modal=false&height=400&width=600")
		 }
		 }*/, {
			text: '����ɾ����־',
			icon: 'delete',
			action: function() {
				showThickboxWin("?model=engineering_worklog_esmworklog&action=toDeleteLog"
				+ "&placeValuesBefore&TB_iframe=true&modal=false&height=400&width=600")
			}
		}],
		menusEx: [{
			text: '�鿴�ܱ�',
			icon: 'view',
			showMenuFn: function(row) {
				return row.weekId != "";
			},
			action: function(row) {
				showOpenWin("?model=engineering_worklog_esmweeklog&action=init&perm=view&id="
				+ row.weekId);
			}
		}, {
			text: 'ɾ��',
			icon: 'delete',
			showMenuFn: function(row) {
				return row.status == "WTJ" && row.confirmStatus == "0";
			},
			action: function(row) {
				if (row.activityId == "") {
					$.ajax({
						type: "POST",
						url: "?model=engineering_worklog_esmworklog&action=ajaxdeletes",
						data: {
							id: row.id
						},
						success: function(msg) {
							if (msg == 1) {
								alert('ɾ���ɹ���');
								show_page();
							} else {
								alert("ɾ��ʧ��! ");
							}
						}
					});
				} else {
					$.ajax({
						type: "POST",
						url: "?model=engineering_project_esmproject&action=isClose",
						data: {"id": row.projectId},
						async: false,
						success: function(data) {
							if (data == 1) {
								alert('��Ŀ�ѹرգ������ٶԴ���־���в���');
							} else {
								if (confirm('ȷ��Ҫɾ����')) {
									$.ajax({
										type: "POST",
										url: "?model=engineering_worklog_esmworklog&action=ajaxdeletes",
										data: {
											id: row.id
										},
										success: function(msg) {
											if (msg == 1) {
												alert('ɾ���ɹ���');
												show_page();
											} else {
												alert("ɾ��ʧ��! ");
											}
										}
									});
								}
							}
						}
					});
				}
			}
		}],
		searchitems: [{
			display: '�����',
			name: 'executionDateSearch'
		}, {
			display: '��������',
			name: 'activityNameSearch'
		}, {
			display: '��Ŀ����',
			name: 'projectNameSearch'
		}, {
			display: '���ڵ�',
			name: 'provinceCitySearch'
		}],
		sortorder: "DESC",
		sortname: "executionDate desc,activityName"
	});
});

//����鿴����ҳ��
//function reeditCost(worklogId) {
//	var url = "?model=engineering_worklog_esmworklog&action=toReeditNew&id=" + worklogId;
//	var height = 800;
//	var width = 1150;
//	window.open(url, "���±༭����",
//		'top=0,left=0,menubar=0,toolbar=0,status=1,scrollbars=1,resizable=1,width='
//		+ width + ',height=' + height);
//}