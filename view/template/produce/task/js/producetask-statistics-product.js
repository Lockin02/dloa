$(function () {
	$("#producetaskList").empty().yxeditgrid({
		objName: 'producetask',
		url: '?model=produce_task_configproduct&action=listJsonStatistics',
		param: {taskIds:$("#idStr").val()},
		isAddAndDel: false,
		type: 'view',
		event: {
			reloadData: function (rowDate) {
				checkBoxAll($('#boxAll'));
			}
		},
		//����Ϣ
		colModel: [{
			display: 'id',
			name: 'id',
			type: 'hidden'
		}, {
			display: '����id',
			name: 'taskId',
			type: 'hidden'
		}, {
			name: 'relDocCode',
			display: '��ͬ���(Դ�����)',
			width: 150,
			type: 'statictext'
		},{
			display: 'ѡ��<input type="checkbox" id="boxAll" onclick="checkBoxAll(this);" checked style="width:60px;"/>',
			name: 'statisticsBox',
			width: 60,
			process: function (v, row) {
				return '<input type="checkbox" name="statisticsBox[]" value="' + row.taskId + '&' + row.productCode + '"/>';
			}
		}, {
			name: 'docCode',
			display: '���ݱ��',
			width: 140,
			type: 'statictext'
		}, {
			name: 'docStatus',
			display: '����״̬',
			width: 60,
			type: 'statictext',
			process: function (v, row) {
				switch (v) {
				case '0':
					return "δ����";
					break;
				case '1':
					return "�ѽ���";
					break;
				case '2':
					return "���ƶ��ƻ�";
					break;
				default:
					return "--";
				}
			}
		}, {
			name: 'productName',
			display: '��������',
			width: 200,
			type: 'statictext'
		}, {
			name: 'productCode',
			display: '���ϱ���',
			width: 100,
			type: 'statictext'
		}, {
			name: 'num',
			display: '����',
			width: 60,
			type: 'statictext'
		},  {
			name: 'urgentLevel',
			display: '���ȼ�',
			width: 90
		}, {
			name: 'customerName',
			display: '�ͻ�����',
			width: 150,
			type: 'statictext'
		}, {
			name: 'chargeUserName',
			display: '������',
			width: 90,
			type: 'statictext'
		}]
	});
});



// ��ѯ
function toSearch() {
	var searchArr = [
		'docCode',
		'docDate',
		'urgentLevelCode',
		'productCode',
		'productName',
		'productionBatch',
		'relDocCode',
		'customerName',
		'saleUserName',
		'chargeUserName'
	];

	var idStr = '';
	var param = {};
	for (var i = 0; i < searchArr.length; i++) {
		idStr = searchArr[i].toString();
		if ($.trim($('#' + idStr).val()) != '') {
			param[idStr] = $.trim($('#' + idStr).val());
		}
	}
	var docStatusArr = [];
	$('input[name^="producetask[docStatus]"]').each(function () {
		if ($(this).attr('checked')) {
			docStatusArr.push($(this).val());
		}
	});
	if (docStatusArr.length > 0) {
		param.docStatusIn = docStatusArr.toString();
	}

	$("#producetaskList").empty().yxeditgrid({
		objName: 'producetask',
		url: '?model=produce_task_producetask&action=listJson',
		param: param,
		isAddAndDel: false,
		type: 'view',
		event: {
			reloadData: function (rowDate) {
				checkBoxAll($('#boxAll'));
			}
		},
		//����Ϣ
		colModel: [{
			display: 'id',
			name: 'id',
			type: 'hidden'
		}, {
			name: 'relDocCode',
			display: '��ͬ���(Դ�����)',
			width: 150,
			type: 'statictext'
		},{
			display: 'ѡ��<input type="checkbox" id="boxAll" onclick="checkBoxAll(this);" checked style="width:60px;"/>',
			name: 'statisticsBox',
			width: 60,
			process: function (v, row) {
				return '<input type="checkbox" name="statisticsBox[]" value="' + row.taskId + '&' + row.productCode + '"/>';
			}
		}, {
			name: 'docCode',
			display: '���ݱ��',
			width: 140,
			type: 'statictext'
		}, {
			name: 'docStatus',
			display: '����״̬',
			width: 60,
			type: 'statictext',
			process: function (v, row) {
				switch (v) {
				case '0':
					return "δȷ��";
					break;
				case '1':
					return "ִ����";
					break;
				case '2':
					return "�����";
					break;
				case '3':
					return "�ѹر�";
					break;
				default:
					return "--";
				}
			}
		}, {
			name: 'productName',
			display: '��������',
			width: 200,
			type: 'statictext'
		}, {
			name: 'productCode',
			display: '���ϱ���',
			width: 100,
			type: 'statictext'
		}, {
			name: 'planNum',
			display: '����',
			width: 60,
			type: 'statictext'
		},  {
			name: 'urgentLevel',
			display: '���ȼ�',
			width: 90
		}, {
			name: 'customerName',
			display: '�ͻ�����',
			width: 150,
			type: 'statictext'
		}, {
			name: 'chargeUserName',
			display: '������',
			width: 90,
			type: 'statictext'
		}]
	});
}

// ȫѡ�¼�
function checkBoxAll(obj) {
	var statisticsBoxObjs = $('[name="statisticsBox[]"]');
	if ($(obj).attr('checked')) {
		statisticsBoxObjs.each(function () {
			$(this).attr('checked', true);
		});
	} else {
		statisticsBoxObjs.each(function () {
			$(this).attr('checked', false);
		});
	}
}

// ����
function caculate() {
	var statisticsBoxObjs = $('[name="statisticsBox[]"]:checked');
	if (statisticsBoxObjs.length == 0) {
		alert('������ѡ��һ����¼��');
	} else {
		var idArr = [];
		var codeArr = [];
		statisticsBoxObjs.each(function () {
			id = $(this).val().split("&")[0];
			code = $(this).val().split("&")[1]
			if($.inArray(id, idArr) == -1){
				idArr.push(id);
			}
			if($.inArray(code, codeArr) == -1){
				codeArr.push(code);
			}
		});
		var idStr = idArr.toString();
		var codeStr = codeArr.toString();
		showModalWin("index1.php?model=produce_task_producetask&action=statistics&idStr=" + idStr + "&codeStr=" + codeStr, '2');
	}
}