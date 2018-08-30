$(document).ready(function() {

	$("#handoverList").yxeditgrid({
		objName : 'handover[formwork]',
		url : '?model=hr_leave_handoverlist&action=addItemJson',
		param : {'handoverId' : $("#id").val() , 'recipientIdArr' : $("#userId").val(),'affstate':'0'},
		isAddAndDel : false,
		colModel : [ {
			display : 'id',
			name : 'id',
//			type : 'txt'
			type : 'hidden'
		},{
			display : 'handoverId',
			name : 'handoverId',
//			type : 'txt'
			type : 'hidden'

		},{
			display : '�������豸��������',
			name : 'items',
			readonly : "readonly",
			type : 'statictext'
		},{
			display : '�������豸��������',
			name : 'items',
			type : 'hidden'
		}, {
			display : '�������',
			name : 'handoverCondition',
			type : 'hidden',
			tclass : 'txtlong'
		}, {
			display : '������',
			name : 'recipientName',
			readonly : "readonly",
			type : 'statictext'
		}, {
			display : 'ȷ��״̬',
			name : 'affstate',
			type : 'checkbox'

		}, {
			display : '������Id',
			name : 'recipientId',
//			type : 'txt'
			type : 'hidden'
		}, {
			display : '��ʧ����',
			name : 'lose',
			type : 'txt',
			event : {
				blur : function() {
					var thisVal = $.trim($(this).val());
					if(thisVal!=''){
						var rowNum = $(this).data("rowNum");
						var g = $(this).data("grid");
						var affstate = g.getCmpByRowAndCol(rowNum,'affstate').attr("checked");
						if(!affstate){
							alert("�빴ѡ'ȷ��״̬',����ý�����ȷ����Ч.");
						}
					}
				}
			}
		}, {
			display : '���',
			name : 'deduct',
			type : 'txt',
			event : {
				blur : function() {
					var thisVal = $.trim($(this).val());
					if(thisVal!=''){
						var rowNum = $(this).data("rowNum");
						var g = $(this).data("grid");
						var affstate = g.getCmpByRowAndCol(rowNum,'affstate').attr("checked");
						if(!affstate){
							alert("�빴ѡ'ȷ��״̬',����ý�����ȷ����Ч.");
						}
					}
				}
			}
		}, {
			display : '��ע',
			name : 'remark',
			type : 'textarea',
			event : {
				blur : function() {
					var thisVal = $.trim($(this).val());
					if(thisVal!=''){
						var rowNum = $(this).data("rowNum");
						var g = $(this).data("grid");
						var affstate = g.getCmpByRowAndCol(rowNum,'affstate').attr("checked");
						if(!affstate){
							alert("�빴ѡ'ȷ��״̬',����ý�����ȷ����Ч.");
						}
					}
				}
			}
		},{
			display : '����',
			name : 'sort',
			type : 'hidden'
		},{
			display : '�Ƿ����ʼ�',
			name : 'mailAffirm',
			type : 'hidden'
		},{
			display : '����ǰ��',
			name : 'sendPremise',
			type : 'hidden'
		}]
	});
});

function checkData() {
	var tmp = 0;
	$("input[id^='handoverList_cmp_affstate']:checkbox").each(function () {
		if ($(this).attr("checked")) {
			tmp = 1;
		}
	});
	if (tmp == 0) {
		alert("�빴ѡ'ȷ��״̬',����ý�����ȷ����Ч.");
		return false;
	}else {
		return true;
	}
}