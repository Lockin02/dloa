$(function() {

	//�����嵥
	if ($("#isSubAppChange").val() == '1') {
		var param = {
			'contractId' : $("#contractId").val(),
			'isDel' : '0',
			'isBorrowToorder' : '0'
		};
	} else {
		var param = {
			'contractId' : $("#contractId").val(),
			'isTemp' : '0',
			'isDel' : '0',
			'isBorrowToorder' : '0'
		};
	}
	$("#equInfo").yxeditgrid({
		objName : 'contract[equ]',
		url : '?model=contract_contract_equ&action=deliveryListJson',
		type : 'view',
		tableClass : 'form_in_table',
		param : param,
		colModel : [{
			display : '���ϱ��',
			name : 'productCode',
			tclass : 'txt'
		},{
			display : '��������',
			name : 'productName',
			tclass : 'txt'
		},{
			display : '����Id',
			name : 'productId',
			type : 'hidden'
		},{
			display : '��������',
			name : 'number',
			tclass : 'txtshort'
		},{
			display : '�������',
			name : 'exeNum',
			tclass : 'txtshort',
			process : function (v) {
				if (v > 0) {
					return v;
				} else {
					return 0;
				}
			}
		},{
			display : '��;����',
			name : 'onwayAmount',
			tclass : 'txtshort'
		}]
	});

	//�����´�����
	if ($("#productIds").val() != '') {
		var noParam = {
			noProductIds : $("#productIds").val()
		}
		$.extend(noParam ,param);
	} else {
		var noParam = param;
	}
	$("#unneedInfo").yxeditgrid({
		objName : 'contract[unneed]',
		url : '?model=contract_contract_equ&action=deliveryListJson',
		type : 'view',
		tableClass : 'form_in_table',
		param : noParam,
		colModel : [{
			display : '���ϱ��',
			name : 'productCode',
			tclass : 'txt'
		},{
			display : '��������',
			name : 'productName',
			tclass : 'txt'
		},{
			display : '����Id',
			name : 'productId',
			type : 'hidden'
		},{
			display : '��������',
			name : 'number',
			tclass : 'txtshort'
		},{
			display : '��ִ������',
			name : 'executedNum',
			tclass : 'txtshort'
		},{
			display : 'δִ������',
			name : 'backNum',
			tclass : 'txtshort',
			process : function (v ,row) {
				return row.number - row.backNum;
			}
		},{
			display : '�������',
			name : 'exeNum',
			tclass : 'txtshort',
			process : function (v) {
				if (v > 0) {
					return v;
				} else {
					return 0;
				}
			}
		},{
			display : '��;����',
			name : 'onwayAmount',
			type : 'txtshort'
		},{
			display : '��;����Ԥ�Ƶ�����',
			name : 'daohuoqi',
			type : 'txtshort'
		}]
	});

	//�ɹ�����
	$("#basicInfo").yxeditgrid({
		objName : 'contract[equipment]',
		url : '?model=purchase_plan_equipment&action=deliveryListJson',
		type : 'view',
		tableClass : 'form_in_table',
		param : {
			basicIds : $("#basicIds").val() ? $("#basicIds").val() : 0
		},
		colModel : [{
			display : '���ϱ��',
			name : 'productNumb',
			tclass : 'txt'
		},{
			display : '��������',
			name : 'productName',
			tclass : 'txt'
		},{
			display : '����Id',
			name : 'productId',
			type : 'hidden'
		},{
			display : '��������',
			name : 'amountAllOld',
			tclass : 'txtshort'
		},{
			display : '��ִ������',
			name : 'amountIssued',
			tclass : 'txtshort'
		},{
			display : 'δִ������',
			name : 'failNum',
			tclass : 'txtshort',
			process : function (v ,row) {
				return row.amountAll - row.amountIssued;
			}
		},{
			display : '�������',
			name : 'exeNum',
			tclass : 'txtshort',
			process : function (v) {
				if (v > 0) {
					return v;
				} else {
					return 0;
				}
			}
		},{
			display : '��;����',
			name : 'onwayAmount',
			tclass : 'txtshort'
		},{
			display : '�ɹ���������',
			name : 'amountAll',
			type : 'txtshort'
		},{
			display : '�ɹ�Ԥ�Ƶ�����',
			name : 'dateHope',
			type : 'txtshort'
		},{
			display : '�������',
			name : 'stockNum',
			type : 'txtshort'
		}]
	});

	//��������
	$("#produceapplyInfo").yxeditgrid({
		objName : 'contract[produceapply]',
		url : '?model=produce_apply_produceapplyitem&action=contractListJson',
		type : 'view',
		tableClass : 'form_in_table',
		param : {
			mainIdArr : $("#produceapplyIds").val() ? $("#produceapplyIds").val() : 0,
			groupBy : 'c.id'
		},
		colModel : [{
			display : '���ϱ��',
			name : 'productCode',
			tclass : 'txt'
		},{
			display : '��������',
			name : 'productName',
			tclass : 'txt'
		},{
			display : '����Id',
			name : 'productId',
			type : 'hidden'
		},{
			display : '��������',
			name : 'number',
			tclass : 'txtshort'
		},{
			display : '��ִ������',
			name : 'issuedProNum',
			tclass : 'txtshort'
		},{
			display : 'δִ������',
			name : 'failNum',
			tclass : 'txtshort',
			process : function (v ,row) {
				return row.number - row.issuedProNum;
			}
		},{
			display : '�������',
			name : 'exeNum',
			tclass : 'txtshort',
			process : function (v) {
				if (v > 0) {
					return v;
				} else {
					return 0;
				}
			}
		},{
			display : '������������',
			name : 'produceNum',
			type : 'txtshort'
		},{
			display : '����Ԥ�����ʱ��',
			name : 'planEndDate',
			type : 'txtshort'
		},{
			display : '�������',
			name : 'stockNum',
			type : 'txtshort'
		}]
	});

	//����������
	$("#encryptionInfo").yxeditgrid({
		objName : 'contract[encryption]',
		url : '?model=stock_delivery_encryptionequ&action=listJson',
		type : 'view',
		tableClass : 'form_in_table',
		param : {
			sourceDocId : $("#contractId").val()
		},
		colModel : [{
			display : '���ϱ��',
			name : 'productCode',
			tclass : 'txt'
		},{
			display : '��������',
			name : 'productName',
			tclass : 'txt'
		},{
			display : '����Id',
			name : 'productId',
			type : 'hidden'
		},{
			display : '��������',
			name : 'needNum',
			tclass : 'txtshort'
		},{
			display : '��ִ������',
			name : 'finshNum',
			tclass : 'txtshort'
		},{
			display : 'δִ������',
			name : 'failNum',
			tclass : 'txtshort',
			process : function (v ,row) {
				return row.needNum - row.finshNum;
			}
		},{
			display : '�������',
			name : 'exeNum',
			tclass : 'txtshort',
			process : function (v) {
				if (v > 0) {
					return v;
				} else {
					return 0;
				}
			}
		},{
			display : '��������������',
			name : 'produceNum',
			type : 'txtshort'
		},{
			display : '������Ԥ�����ʱ��',
			name : 'planFinshDate',
			type : 'txtshort'
		},{
			display : '�������',
			name : 'putNum',
			type : 'txtshort'
		}]
	});
});