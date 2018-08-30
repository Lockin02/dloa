$(document).ready(function() {

	$("#handoverList").yxeditgrid({
		objName : 'handover[formwork]',
		url : '?model=hr_leave_handoverlist&action=addItemJson',
		param : {'handoverId' : $("#id").val() ,'affstate' : 1},
		isAddAndDel : false,
		colModel : [ {
			display : 'id',
			name : 'id',
			type : 'hidden'
		},{
			display : '�������豸��������',
			name : 'items',
			type : 'statictext'
		},{
			display : '�������豸��������',
			name : 'items',
			type : 'hidden'
		},{
			display : '������',
			name : 'recipientName',
			width : 120,
			type : 'statictext'
		},{
			display : '������id',
			name : 'recipientId',
			type : 'hidden'
		},{
			display : '��ʧ����',
			name : 'lose',
			type : 'statictext'
		},{
			display : '���',
			name : 'deduct',
			width : 120,
			type : 'statictext'
		},{
			display : '��ע',
			name : 'remark',
			type : 'statictext'
		},{
			display : '�Ƿ������ǰȷ��',
			name : 'isKey',
			width : 110,
			type : 'statictext',
			process : function (v) {
				if (v == 'on') {
				   return "<span style='color:blue'>��</span>";
				}else{
				   return "<span style='color:red'>��</span>";
				}
			}
		},{
			display : 'ȷ��״̬',
			name : 'affstate',
			width : 80,
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
			name : 'restart',
			width : 80,
			type : 'checkbox',
			process : function (v ,row) {
				if (row.affstate != 1) {
					var num = $("#handoverList").yxeditgrid("getCurRowNum") - 1;
					$("#handoverList_cmp_restart" + num).hide();
				}
			}

		}]
	});
});

function checkData() {
	var tmp = 0;
	$("input[id^='handoverList_cmp_restart']:checkbox").each(function () {
		if ($(this).attr("checked")) {
			tmp = 1;
		}
	});
	if (tmp == 0) {
		alert("��ѡ���������");
		return false;
	}else {
		return true;
	}
}