$(document).ready(function() {

	$("#handoverList").yxeditgrid({
		objName : 'handover[formwork]',
		url : '?model=hr_leave_handoverlist&action=addItemJson',
		param : {'handoverId' : $("#id").val() ,'affstate' : 0},
		//isAddAndDel : false,
		colModel : [ {
			display : 'id',
			name : 'id',
			type : 'hidden'
		},{
			display : '工作及设备交接事项',
			name : 'items',
			width : 230,
			validation : {
				required : true
			}
		},{
			display : '接收人Id',
			name : 'recipientId',
			type : 'hidden'
		},{
			display : '接收人',
			name : 'recipientName',
			width : 160,
			process : function ($input ,row) {
				var rowNum = $input.data("rowNum");
				var g = $input.data("grid");
				$input.yxselect_user({
					hiddenId : 'handoverList_cmp_recipientId' + rowNum,
					mode : 'check'
				});
			},
			validation : {
				required : true
			},
			readonly : true
		},{
//			display : '遗失财务',
//			name : 'lose'
//		},{
//			display : '扣款金额',
//			name : 'deduct',
//			width : 120
//		},{
//			display : '备注',
//			name : 'remark',
//			type : 'textarea'
//		},{
			display : '是否必须提前确认',
			name : 'isKey',
			width : 100,
			type : 'checkbox',
			value : 'on'
		},{
			display : '确认状态',
			name : 'affstate',
			width : 100,
			type : 'statictext',
			process : function (v) {
				if (v == 1) {
				   return "<span style='color:blue'>√</span>";
				}else{
				   return "<span style='color:red'>×</span>";
				}
			}
		},{
			display : '排序',
			name : 'sort',
			width : 100
		},{
			display : '是否发送邮件',
			name : 'mailAffirm',
			width : 100,
			type : 'checkbox'
		},{
			display : '发送前提',
			name : 'sendPremise',
			width : 100
		}]
	});
})