$(document).ready(function() {

	$("#handoverList").yxeditgrid({
		objName : 'handover[formwork]',
		url : '?model=hr_leave_handoverlist&action=addItemJson',
		param : {'handoverId' : $("#id").val()},
		isAddAndDel : false,
		colModel : [{
			display : '工作及设备交接事项',
			name : 'items',
			type : 'statictext'
		}, {
			display : '交接情况',
			name : 'handoverCondition',
			type : 'statictext'
		}, {
			display : '接收人',
			name : 'recipientName',
			type : 'statictext'
		}, {
			display : '接收人Id',
			name : 'recipientId',
//			type : 'txt'
			type : 'hidden'
		}, {
			display : '遗失财务',
			name : 'lose',
			type : 'statictext'
		}, {
			display : '扣款金额',
			name : 'deduct',
			type : 'statictext'
		}, {
			display : '确认状态',
			name : 'affstate',
			type : 'statictext',
			process : function(v) {
				if(v == "1"){
				   return "<span style='color:blue'>√</span>";
				}else{
				   return "<span style='color:red'>×</span>";
				}
			}
		}]
	});
})