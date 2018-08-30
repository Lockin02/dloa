$(document).ready(function() {

	$.ajax({
		type : 'POST',
		url: '?model=manufacture_basic_productconfig&action=listJson',
		data: {
			processId: $('#id').val(),
			dir: 'ASC'
		},
		success: function (data) {
			if (data && data != 'false') {
				var tHead = eval('(' + data + ')');
				var tableHead = [];
				$.each(tHead ,function(k ,v) {
					tableHead.push({
						name : v.colCode,
						display : v.colName
					});
				});

				$('#productConfigInfo').yxeditgrid({
					url : '?model=manufacture_basic_productconfigItem&action=tableJson',
					param : {
						processId : $("#id").val(),
						dir : 'ASC'
					},
					type : 'view',
					colModel : tableHead
				});
			}
		}
	});

	$("#equInfo").yxeditgrid({
		objName : 'process[equ]',
		url : '?model=manufacture_basic_processequ&action=listJson',
		param : {
			parentId : $("#id").val(),
			dir : 'ASC'
		},
		type : 'view',
		colModel : [{
			display : 'id',
			name : 'id',
			type : 'hidden'
		},{
			display : '工  序',
			name : 'process',
			width : '15%'
		},{
			display : '项目名称',
			name : 'processName',
			width : '30%',
			align : 'left'
		},{
			display : '工序时间（秒）',
			name : 'processTime',
			width : '10%'
		},{
			display : '接收人',
			name : 'recipient',
			width : 180,
			align : 'left'
		},{
			display : '接收人ID',
			name : 'recipientId',
			type : 'hidden'
		},{
			display : '备注',
			name : 'remark',
			width : '20%',
			align : 'left'
		}]
	});
});

function getTableHead(configCode) {
	var tableHead = [];
	var data = $.ajax({
				type : 'POST',
				url : '?model=produce_task_taskconfig&action=listJson',
				data : {
					taskId : $("#taskId").val(),
					configCode : configCode,
					dir : 'ASC'
				},
				async : false
			}).responseText;
	data = eval("(" + data + ")");

	$.each(data ,function(k ,v) {
		tableHead.push({
			name : v.colCode,
			display : v.colName
		});
	});

	return tableHead;
}