$(document).ready(function () {
	var equTableObj = $("#equInfo");
	equTableObj.yxeditgrid({
		objName: 'process[equ]',
		isFristRowDenyDel: true,
		colModel: [{
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