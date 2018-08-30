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
			display : '�������豸��������',
			name : 'items'
		}, {
			display : '������',
			name : 'recipientName'
		}, {
			display : 'ȷ��״̬',
			name : 'affstate',
			width : 80,
			process : function (v) {
				if (v == 1) {
				   return "<span style='color:blue'>��</span>";
				}else{
				   return "<span style='color:red'>��</span>";
				}
			}

		}, {
			display : 'ȷ��ʱ��',
			name : 'affTime'
		}, {
			display : '��ʧ����',
			name : 'lose'
		}, {
			display : '���',
			name : 'deduct'
		}, {
			display : '��ע',
			name : 'remark'
		}]
	});
})