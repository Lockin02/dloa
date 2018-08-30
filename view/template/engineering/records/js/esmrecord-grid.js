var show_page = function() {
	$("#esmrecordGrid").yxgrid("reload");
};

$(function() {
	var buttonsArr = [];

	// ���ݲ���
	$.ajax({
		type: 'POST',
		url: '?model=engineering_project_esmproject&action=getLimits',
		data: {
			limitName: '���ݱ�-����'
		},
		async: false,
		success: function(data) {
			if (data == 1) {
				buttonsArr.push({
					name: 'edit',
					text: "���ݲ���",
					icon: 'copy',
					items: [{
						text: '���ݸ���',
						icon: 'copy',
						action: function() {
							if (confirm('ȷ�ϸ���������')) {
								showThickboxWin('?model=engineering_records_esmrecord&action=updateRecord'
								+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=200&width=600');
							}
						}
					}, {
						text: "����汾",
						icon: 'save',
						action: function() {
							showThickboxWin('?model=engineering_records_esmrecord&action=toSetUsing'
							+ '&nowVersion=' + $("#nowVersion").val()
							+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=280&width=550');
						}
					}]
				});
			}
		}
	});

	// ���ݵ���
	$.ajax({
		type: 'POST',
		url: '?model=engineering_project_esmproject&action=getLimits',
		data: {
			limitName: '���ݱ�-����'
		},
		async: false,
		success: function(data) {
			if (data == 1) {
				buttonsArr.push({
					name: 'export',
					text: "�б����ݵ���",
					icon: 'excel',
					action: function() {
						var gridObject = $("#esmrecordGrid");
						var searchConditionKey = "";
						var searchConditionVal = "";
						for (var t in gridObject.data('yxgrid').options.searchParam) {
							if (t != "") {
								searchConditionKey += t;
								searchConditionVal += gridObject.data('yxgrid').options.searchParam[t];
							}
						}
						var searchSql = gridObject.data('yxgrid').getAdvSql();
						var searchArr = [];
						searchArr[0] = searchSql;
						searchArr[1] = searchConditionKey;
						searchArr[2] = searchConditionVal;
						window.open("?model=engineering_records_esmrecord&action=exportExcel"
							+ "&searchConditionKey="
							+ searchConditionKey
							+ "&searchConditionVal=" + searchConditionVal
							+ "&version="
							+ $("#nowVersion").val()
						);
					}
				});
			}
		}
	});

	buttonsArr.push({
		text: "����",
		icon: 'delete',
		action: function() {
			history.go(0)
		}
	});

	$("#esmrecordGrid").yxgrid({
		model: 'engineering_records_esmrecord',
		title: '��Ŀ���ܱ�-���ݱ�',
		param: {version: $("#nowVersion").val()},
		isDelAction: false,
		isAddAction: false,
		isViewAction: false,
		isEditAction: false,
		showcheckbox: false,
		customCode: 'esmrecordGrid',
		isOpButton: false,
		leftLayout: true,
		//����Ϣ
		colModel: [{
			display: 'id',
			name: 'id',
			sortable: true,
			hide: true
		}, {
			name: 'projectName',
			display: '��Ŀ����',
			sortable: true,
			width: 140,
			process: function(v, row) {
				return (row.contractId == "0" || row.contractId == "") && row.contractType != 'GCXMYD-04' ?
				"<span style='color:blue' title='δ������ͬ�ŵ���Ŀ'>" + v + "</span>" : v;
			}
		}, {
			name: 'projectCode',
			display: '��Ŀ���',
			sortable: true,
			width: 120,
			process: function(v, row) {
				return "<a href='javascript:void(0)' onclick='toView(" + row.projectId + ")'>" + v + "</a>";
			}
		}, {
			name: 'statusName',
			display: '��Ŀ״̬',
			sortable: true,
			width: 60
		}, {
			name: 'productLineName',
			display: 'ִ������',
			sortable: true,
			width: 60
		}, {
			name: 'officeName',
			display: '����',
			sortable: true,
			width: 70
		}, {
			name: 'province',
			display: 'ʡ��',
			sortable: true,
			width: 70
		}, {
			name: 'exgross',
			display: 'ë����',
			process: function(v, row) {
				if (row.contractType == 'GCXMYD-04') {
					return '--';
				} else {
					if (v == '') return '����';
					if (v * 1 >= 0) {
						return v + " %";
					} else {
						return "<span class='red'>" + v + " %</span>";
					}
				}
			},
			width: 60
		}, {
			name: 'projectProcess',
			display: '���̽���',
			process: function(v) {
				return formatProgress(v);
			},
			width: 60
		}, {
			name: 'feeAllProcess',
			display: '���ý���',
			process: function(v) {
				return formatProgress(v);
			},
			width: 60
		}, {
			name: 'feeAll',
			display: '�ܾ���',
			process: function(v) {
				return moneyFormat2(v);
			},
			width: 70
		}, {
			name: 'budgetAll',
			display: '��Ԥ��',
			process: function(v) {
				return moneyFormat2(v);
			},
			width: 80
		}, {
			name: 'planBeginDate',
			display: 'Ԥ�ƿ�ʼ',
			sortable: true,
			width: 80
		}, {
			name: 'planEndDate',
			display: 'Ԥ�ƽ���',
			sortable: true,
			width: 80
		}, {
			name: 'country',
			display: '����',
			sortable: true,
			width: 70,
			hide: true
		}, {
			name: 'city',
			display: '����',
			sortable: true,
			width: 70,
			hide: true
		}, {
			name: 'attributeName',
			display: '��Ŀ����',
			width: 70,
			process: function(v, row) {
				switch (row.attribute) {
					case 'GCXMSS-01' :
						return "<span class='red'>" + v + "</span>";
					case 'GCXMSS-02' :
						return "<span class='blue'>" + v + "</span>";
					case 'GCXMSS-03' :
						return "<span class='green'>" + v + "</span>";
					default :
						return v;
				}
			},
			hide: true
		}, {
			name: 'budgetField',
			display: '�ֳ�Ԥ��',
			sortable: true,
			process: function(v) {
				return moneyFormat2(v);
			},
			width: 80,
			hide: true
		}, {
			name: 'budgetPerson',
			display: '����Ԥ��',
			sortable: true,
			process: function(v) {
				return moneyFormat2(v);
			},
			width: 80,
			hide: true
		}, {
			name: 'budgetEqu',
			display: '�豸Ԥ��',
			sortable: true,
			process: function(v) {
				return moneyFormat2(v);
			},
			width: 80,
			hide: true
		}, {
			name: 'budgetOutsourcing',
			display: '���Ԥ��',
			sortable: true,
			process: function(v) {
				return moneyFormat2(v);
			},
			width: 80,
			hide: true
		}, {
			name: 'budgetOther',
			display: '����Ԥ��',
			sortable: true,
			process: function(v) {
				return moneyFormat2(v);
			},
			width: 80,
			hide: true
		}, {
			name: 'feeField',
			display: '�ֳ�����',
			process: function(v) {
				return moneyFormat2(v);
			},
			width: 80,
			hide: true
		}, {
			name: 'feePerson',
			display: '��������',
			process: function(v) {
				return moneyFormat2(v);
			},
			width: 80,
			hide: true
		}, {
			name: 'feeEqu',
			display: '�豸����',
			process: function(v) {
				return moneyFormat2(v);
			},
			width: 80,
			hide: true
		}, {
			name: 'feeOutsourcing',
			display: '�������',
			sortable: true,
			process: function(v) {
				return moneyFormat2(v);
			},
			width: 80,
			hide: true
		}, {
			name: 'feeOther',
			display: '��������',
			process: function(v) {
				return moneyFormat2(v);
			},
			width: 80,
			hide: true
		}, {
			name: 'feeFieldProcess',
			display: '�ֳ����ý���',
			process: function(v) {
				return formatProgress(v);
			},
			width: 110,
			hide: true
		}, {
			name: 'contractTypeName',
			display: 'Դ������',
			sortable: true,
			hide: true
		}, {
			name: 'contractId',
			display: '������ͬid',
			sortable: true,
			hide: true
		}, {
			name: 'contractCode',
			display: '������ͬ���(Դ�����)',
			sortable: true,
			width: 160,
			hide: true
		}, {
			name: 'rObjCode',
			display: 'ҵ����',
			sortable: true,
			width: 120,
			hide: true
		}, {
			name: 'contractMoney',
			display: '��ͬ���',
			sortable: true,
			process: function(v, row) {
				if (row.contractType == 'GCXMYD-04') {
					return '--';
				} else {
					return moneyFormat2(v);
				}
			},
			width: 80,
			hide: true
		}, {
			name: 'customerName',
			display: '�ͻ�����',
			sortable: true,
			hide: true
		}, {
			name: 'depName',
			display: '��������',
			sortable: true,
			hide: true
		}, {
			name: 'actBeginDate',
			display: 'ʵ�ʿ�ʼ',
			sortable: true,
			width: 80,
			hide: true
		}, {
			name: 'actEndDate',
			display: 'ʵ�����',
			sortable: true,
			width: 80,
			hide: true
		}, {
			name: 'managerName',
			display: '��Ŀ����',
			sortable: true,
			hide: true
		}, {
			name: 'ExaStatus',
			display: '����״̬',
			sortable: true,
			width: 70,
			hide: true
		}, {
			name: 'ExaDT',
			display: '��������',
			sortable: true,
			width: 80,
			hide: true
		}, {
			name: 'categoryName',
			display: '��Ŀ���',
			sortable: true,
			width: 80,
			hide: true
		}, {
			name: 'updateTime',
			display: '�������',
			sortable: true,
			width: 120,
			hide: true
		}],
		lockCol: ['projectName', 'projectCode'],//����������
		buttonsEx: buttonsArr,
		searchitems: [{
			display: '���´�',
			name: 'officeName'
		}, {
			display: '��Ŀ���',
			name: 'projectCodeSearch'
		}, {
			display: '��Ŀ����',
			name: 'projectName'
		}, {
			display: '��Ŀ����',
			name: 'managerName'
		}, {
			display: 'ҵ����',
			name: 'rObjCodeSearch'
		}, {
			display: '������ͬ��',
			name: 'contractCodeSearch'
		}],
		// ����״̬���ݹ���
		comboEx: [{
			text: "��Ŀ����",
			key: 'attribute',
			datacode: 'GCXMSS'
		}, {
			text: "��Ŀ״̬",
			key: 'status',
			datacode: 'GCXMZT'
		}]
	});

	var M = new Date();
	var Year = M.getFullYear();
	var Year2 = Year - 2;
	var Year1 = Year - 1;
	$("#view").append("<br/><div id='versionNum' class='red'>���°汾��: V<span>" + $("#maxVersion").val() + "</span></div>").
		append("<tr><td class='form_text_left'>�汾���</td>" +
		"<td class='form_view_right'>" +
		"<select class='selectauto' id='storeYear' style='width: 100%;' onchange='createVersionNum()'>" +
		"<option value='0'>" + "...ѡ��..." + "</option>" +
		"<option value='" + Year + "'>" + Year + "��</option>" +
		"<option value='" + Year1 + "'>" + Year1 + "��</option>" +
		"<option value='" + Year2 + "'>" + Year2 + "��</option>" +
		"</select></td></tr>").
		append("<tr><td class='form_text_left'>�汾�·�</td>" +
		"<td class='form_view_right'>" +
		"<select class='selectauto' id='storeMonth' style='width: 100%;' onchange='createVersionNum()'>" +
		"<option value='0'>" + "...ѡ��..." + "</option>" +
		"<option value='1'>1��</option><option value='2'>2��</option><option value='3'>3��</option><option value='4'>4��</option>" +
		"<option value='5'>5��</option><option value='6'>6��</option><option value='7'>7��</option><option value='8'>8��</option>" +
		"<option value='9'>9��</option><option value='10'>10��</option><option value='11'>11��</option><option value='12'>12��</option>" +
		"</select></td></tr>");
});

//�����鿴�汾��
function createVersionNum() {
	var storeYear = $("#storeYear").val();
	var storeMonth = $("#storeMonth").val();

	$.ajax({
		type: "POST",
		url: "?model=engineering_records_esmrecord&action=getVersionArr",
		data: {storeYear: storeYear, storeMonth: storeMonth},
		async: false,
		success: function(data) {
			$("#view").append("<div id='verSelect'></div>");
			if (data != 0) {
				$("#verSelect").html("<tr><td class='form_text_left'>�汾��</td>" +
				"<td class='form_view_right'>" +
				"<select class='selectauto' id='version' style='width: 100%;' onchange='setVersion()'>" +
				data +
				"</select></td></tr>");
			} else {
				$("#verSelect").html("<tr><td class='form_text_left'>�汾��</td>" +
				"<td class='form_view_right'>" +
				"<select class='selectauto' id='version' style='width: 100%;' onchange='setVersion()'>" +
				"<option>��������</option>" +
				"</select></td></tr>");
			}
		}
	});
}

//���ò�ѯ�汾����
function setVersion() {
	var version = $("#version").val();
	if (version != '0') {
		$("#versionNum").html("<div id='versionNum' class='red'>���°汾��: V<span>" + $("#maxVersion").val() +
		"</span></div>" + "<div id='versionNum' class='blue'>��ǰ�汾��: V<span>" + version + "</span></div>");
	}

	$("#nowVersion").val(version);
	var listGrid = $("#esmrecordGrid").data('yxgrid');
	listGrid.options.extParam['version'] = version;
	listGrid.reload();
}

// ��Ŀ�鿴
function toView(projectId) {
	$.ajax({
		type: "POST",
		url: "?model=engineering_project_esmproject&action=md5RowAjax",
		data: {id: projectId},
		async: false,
		success: function(data) {
			showModalWin("?model=engineering_project_esmproject&action=viewTab&id=" + projectId + '&skey=' + data);
		}
	});
}

//�����б������ʾ
function formatProgress(value) {
	if (value) {
		return '<div style="width:auto;height:auto;border:1px solid #ccc;padding: 0px;">'
			+ '<div style="width:'
			+ value
			+ '%;background:#66FF66;white-space:nowrap;padding: 0px;">'
			+ value + '%' + '</div>'
			+ '</div>';
	} else {
		return '';
	}
}