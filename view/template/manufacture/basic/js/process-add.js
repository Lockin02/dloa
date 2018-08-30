$(document).ready(function () {
	var equTableObj = $("#equInfo");
	equTableObj.yxeditgrid({
		objName: 'process[equ]',
		isFristRowDenyDel: true,
		colModel: [{
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