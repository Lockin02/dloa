$(document).ready(function () {
	$("#isEnable").val($("#isEnable").attr('val')); // 是否启用

	// 获取表头数据
	$.ajax({
		type: 'POST',
		url: '?model=manufacture_basic_productconfig&action=listJson',
		data: {
			processId: $('#id').val(),
			dir: 'ASC'
		},
		success: function (data) {
			if (data && data != 'false') {
				var tHead = eval('(' + data + ')');

				// 先弄表头
				var tHeadObj = $('input[id^=productConfigInfo_cmp_thead]');
				if (tHeadObj.length > tHead.length) { // 少于默认列数
					for (var i = tHeadObj.length; i >= tHead.length; i--) {
						$('#productConfigInfo_cmp_thead' + i).next().trigger('click');
					}
				} else if (tHeadObj.length < tHead.length) { // 多于默认列数
					for (var i = tHeadObj.length; i < tHead.length; i++) {
						$('#productConfigInfo > table > thead > tr > th').last().children().trigger('click');
					}
				}

				// 再弄表头数据
				for (var i = 0; i < tHead.length; i++) {
					$('#productConfigInfo_cmp_thead' + i).val(tHead[i].colName);
				}

				// 获取表格内容数据
				$.ajax({
					type: 'POST',
					url: '?model=manufacture_basic_productconfigItem&action=tableJson',
					data: {
						processId: $('#id').val(),
						dir: 'ASC'
					},
					success: function (data2) {
						if (data2 && data2 != 'false') {
							var tBody = eval('(' + data2 + ')');
							var i, j;
							for (i = 0; i < tBody.length; i++) {
								if (i > 0) {
									$('#productConfigInfo > table > thead > tr > th').first().children().trigger('click');
								}
								j = 0;
								$.each(tBody[i] ,function(k ,v) {
									$('#productConfigInfo_cmp_column' + j + i).val(v);
									j++;
								});
							}
						}
					}
				});
			}
		}
	});

	var equTableObj = $("#equInfo");
	equTableObj.yxeditgrid({
		objName: 'process[equ]',
		url: '?model=manufacture_basic_processequ&action=listJson',
		param: {
			parentId: $("#id").val(),
			dir: 'ASC'
		},
		isFristRowDenyDel: true,
		colModel: [{
			display: 'id',
			name: 'id',
			type: 'hidden'
		}, {
			display: '工  序',
			name: 'process',
			width: '15%',
			validation: {
				required: true
			}
		}, {
			display: '项目名称',
			name: 'processName',
			width: '30%',
			validation: {
				required: true
			},
			process: function ($input) {
				var rowNum = $input.data("rowNum");
				$input.yxcombogrid_processequ({
					hiddenId: 'equInfo_cmp_processName' + rowNum,
					gridOptions: {
						event: {
							row_dblclick: function (e, row, data) {
								equTableObj.yxeditgrid("getCmpByRowAndCol", rowNum, "process").val(data.process);
								equTableObj.yxeditgrid("getCmpByRowAndCol", rowNum, "processTime").val(data.processTime);
								equTableObj.yxeditgrid("getCmpByRowAndCol", rowNum, "recipient").val(data.recipient);
								equTableObj.yxeditgrid("getCmpByRowAndCol", rowNum, "recipientId").val(data.recipientId);
								equTableObj.yxeditgrid("getCmpByRowAndCol", rowNum, "remark").val(data.remark);
							}
						}
					}
				});
			}
		}, {
			display: '工序时间（秒）',
			name: 'processTime',
			width: '10%',
			validation: {
				required: true,
				custom: ['percentageNum']
			}
		}, {
			display: '接收人',
			name: 'recipient',
			width: 180,
			readonly: true,
			process: function ($input) {
				var rowNum = $input.data("rowNum");
				$input.yxselect_user({
					mode: 'check',
					hiddenId: 'equInfo_cmp_recipientId' + rowNum
				});
			}
		}, {
			display: '接收人ID',
			name: 'recipientId',
			type: 'hidden'
		}, {
			display: '备注',
			name: 'remark',
			type: 'textarea',
			width: '20%'
		}]
	});
});