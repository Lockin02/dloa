$(document).ready(function() {

	$("#handoverList").yxeditgrid({
		objName : 'handover[formwork]',
		url : '?model=hr_leave_handoverlist&action=addItemJson',
		param : {'handoverId' : $("#id").val() , 'recipientIdArr' : $("#userId").val()},
		isAddAndDel : false,
		type : 'view',
		colModel : [ {
			display : 'id',
			name : 'id',
			type : 'hidden'
		},{
			display : 'handoverId',
			name : 'handoverId',
			type : 'hidden'

		},{
			display : '工作及设备交接事项',
			name : 'items'
		}, {
			display : '接收人',
			name : 'recipientName'
		}, {
			display : '确认状态',
			name : 'affstate',
			width : 80,
			process : function (v) {
				if (v == 1) {
				   return "<span style='color:blue'>√</span>";
				}else{
				   return "<span style='color:red'>×</span>";
				}
			}

		}, {
			display : '确认时间',
			name : 'affTime'
		}, {
			display : '遗失财务',
			name : 'lose'
		}, {
			display : '金额',
			name : 'deduct'
		}, {
			display : '备注',
			name : 'remark'
		}]
	});
})