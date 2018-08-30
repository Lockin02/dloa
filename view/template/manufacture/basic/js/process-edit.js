$(document).ready(function () {
	$("#isEnable").val($("#isEnable").attr('val')); // �Ƿ�����

	// ��ȡ��ͷ����
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

				// ��Ū��ͷ
				var tHeadObj = $('input[id^=productConfigInfo_cmp_thead]');
				if (tHeadObj.length > tHead.length) { // ����Ĭ������
					for (var i = tHeadObj.length; i >= tHead.length; i--) {
						$('#productConfigInfo_cmp_thead' + i).next().trigger('click');
					}
				} else if (tHeadObj.length < tHead.length) { // ����Ĭ������
					for (var i = tHeadObj.length; i < tHead.length; i++) {
						$('#productConfigInfo > table > thead > tr > th').last().children().trigger('click');
					}
				}

				// ��Ū��ͷ����
				for (var i = 0; i < tHead.length; i++) {
					$('#productConfigInfo_cmp_thead' + i).val(tHead[i].colName);
				}

				// ��ȡ�����������
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
			display: '��  ��',
			name: 'process',
			width: '15%',
			validation: {
				required: true
			}
		}, {
			display: '��Ŀ����',
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
			display: '����ʱ�䣨�룩',
			name: 'processTime',
			width: '10%',
			validation: {
				required: true,
				custom: ['percentageNum']
			}
		}, {
			display: '������',
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
			display: '������ID',
			name: 'recipientId',
			type: 'hidden'
		}, {
			display: '��ע',
			name: 'remark',
			type: 'textarea',
			width: '20%'
		}]
	});
});