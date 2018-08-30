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
		//列信息
		colModel: [{
			display: 'id',
			name: 'id',
			type: 'hidden'
		}, {
			display: '任务单id',
			name: 'taskId',
			type: 'hidden'
		}, {
			name: 'relDocCode',
			display: '合同编号(源单编号)',
			width: 150,
			type: 'statictext'
		},{
			display: '选择<input type="checkbox" id="boxAll" onclick="checkBoxAll(this);" checked style="width:60px;"/>',
			name: 'statisticsBox',
			width: 60,
			process: function (v, row) {
				return '<input type="checkbox" name="statisticsBox[]" value="' + row.taskId + '&' + row.productCode + '"/>';
			}
		}, {
			name: 'docCode',
			display: '单据编号',
			width: 140,
			type: 'statictext'
		}, {
			name: 'docStatus',
			display: '单据状态',
			width: 60,
			type: 'statictext',
			process: function (v, row) {
				switch (v) {
				case '0':
					return "未接收";
					break;
				case '1':
					return "已接收";
					break;
				case '2':
					return "已制定计划";
					break;
				default:
					return "--";
				}
			}
		}, {
			name: 'productName',
			display: '物料名称',
			width: 200,
			type: 'statictext'
		}, {
			name: 'productCode',
			display: '物料编码',
			width: 100,
			type: 'statictext'
		}, {
			name: 'num',
			display: '数量',
			width: 60,
			type: 'statictext'
		},  {
			name: 'urgentLevel',
			display: '优先级',
			width: 90
		}, {
			name: 'customerName',
			display: '客户名称',
			width: 150,
			type: 'statictext'
		}, {
			name: 'chargeUserName',
			display: '责任人',
			width: 90,
			type: 'statictext'
		}]
	});
});



// 查询
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
		//列信息
		colModel: [{
			display: 'id',
			name: 'id',
			type: 'hidden'
		}, {
			name: 'relDocCode',
			display: '合同编号(源单编号)',
			width: 150,
			type: 'statictext'
		},{
			display: '选择<input type="checkbox" id="boxAll" onclick="checkBoxAll(this);" checked style="width:60px;"/>',
			name: 'statisticsBox',
			width: 60,
			process: function (v, row) {
				return '<input type="checkbox" name="statisticsBox[]" value="' + row.taskId + '&' + row.productCode + '"/>';
			}
		}, {
			name: 'docCode',
			display: '单据编号',
			width: 140,
			type: 'statictext'
		}, {
			name: 'docStatus',
			display: '单据状态',
			width: 60,
			type: 'statictext',
			process: function (v, row) {
				switch (v) {
				case '0':
					return "未确定";
					break;
				case '1':
					return "执行中";
					break;
				case '2':
					return "已完成";
					break;
				case '3':
					return "已关闭";
					break;
				default:
					return "--";
				}
			}
		}, {
			name: 'productName',
			display: '物料名称',
			width: 200,
			type: 'statictext'
		}, {
			name: 'productCode',
			display: '物料编码',
			width: 100,
			type: 'statictext'
		}, {
			name: 'planNum',
			display: '数量',
			width: 60,
			type: 'statictext'
		},  {
			name: 'urgentLevel',
			display: '优先级',
			width: 90
		}, {
			name: 'customerName',
			display: '客户名称',
			width: 150,
			type: 'statictext'
		}, {
			name: 'chargeUserName',
			display: '责任人',
			width: 90,
			type: 'statictext'
		}]
	});
}

// 全选事件
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

// 计算
function caculate() {
	var statisticsBoxObjs = $('[name="statisticsBox[]"]:checked');
	if (statisticsBoxObjs.length == 0) {
		alert('请至少选择一条记录！');
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