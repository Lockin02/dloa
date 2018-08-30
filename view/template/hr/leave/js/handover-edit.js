$(document).ready(function() {

	$("#handoverList").yxeditgrid({
		objName : 'handover[formwork]',
		url : '?model=hr_leave_handoverlist&action=addItemJson',
		param : {'handoverId' : $("#id").val()},
		isAddAndDel : false,
		colModel : [{
			display : '�������豸��������',
			name : 'items',
			type : 'statictext'
		}, {
			display : '�������',
			name : 'handoverCondition',
			type : 'statictext'
		}, {
			display : '������',
			name : 'recipientName',
			type : 'statictext'
		}, {
			display : '������Id',
			name : 'recipientId',
//			type : 'txt'
			type : 'hidden'
		}, {
			display : '��ʧ����',
			name : 'lose',
			type : 'statictext'
		}, {
			display : '�ۿ���',
			name : 'deduct',
			type : 'statictext'
		}, {
			display : 'ȷ��״̬',
			name : 'affstate',
			type : 'statictext',
			process : function(v) {
				if(v == "1"){
				   return "<span style='color:blue'>��</span>";
				}else{
				   return "<span style='color:red'>��</span>";
				}
			}
		}]
	});
})