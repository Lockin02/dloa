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
			display : '�������豸��������',
			name : 'items',
			width : 230,
			validation : {
				required : true
			}
		},{
			display : '������Id',
			name : 'recipientId',
			type : 'hidden'
		},{
			display : '������',
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
//			display : '��ʧ����',
//			name : 'lose'
//		},{
//			display : '�ۿ���',
//			name : 'deduct',
//			width : 120
//		},{
//			display : '��ע',
//			name : 'remark',
//			type : 'textarea'
//		},{
			display : '�Ƿ������ǰȷ��',
			name : 'isKey',
			width : 100,
			type : 'checkbox',
			value : 'on'
		},{
			display : 'ȷ��״̬',
			name : 'affstate',
			width : 100,
			type : 'statictext',
			process : function (v) {
				if (v == 1) {
				   return "<span style='color:blue'>��</span>";
				}else{
				   return "<span style='color:red'>��</span>";
				}
			}
		},{
			display : '����',
			name : 'sort',
			width : 100
		},{
			display : '�Ƿ����ʼ�',
			name : 'mailAffirm',
			width : 100,
			type : 'checkbox'
		},{
			display : '����ǰ��',
			name : 'sendPremise',
			width : 100
		}]
	});
})