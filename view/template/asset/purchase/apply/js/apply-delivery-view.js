$(document).ready(function() {

	$("#purchaseProductTable").yxeditgrid({
		objName : 'apply[applyItem]',
		url : '?model=asset_purchase_apply_applyItem&action=listJson',
		delTagName : 'isDelTag',
		type : 'view',
		param : {
			applyId : $("#applyId").val(),
			"purchDept" : '1',
			"isDel" : '0'
		},
		colModel : [{
			display : '��������',
			name : 'productName',
			tclass : 'readOnlyTxtItem'
		}, {
			display : '���',
			name : 'pattem',
			tclass : 'readOnlyTxtItem'
		}, {
			display : '��λ',
			name : 'unitName',
			tclass : 'readOnlyTxtItem'
		}, {
			display : '��������',
			name : 'purchAmount',
			tclass : 'txtshort'
		}, {
			display : '�´���������',
			name : 'issuedAmount',
			tclass : 'txtshort'
		}, {
			display : 'ϣ����������',
			name : 'dateHope',
			type : 'date'
		}, {
			display : '��ע',
			name : 'remark',
			tclass : 'txt'
		}, {
			display : '�ɹ�����',
			name : 'purchDept',
			tclass : 'txt',
			process : function($input, rowData) {
				if (rowData.purchDept == '0') {
					return '������';
				} else if (rowData.purchDept == '1') {
					return '������';
				}
			}
		}]
	})

	// ���ݲɹ��������ж��Ƿ���ʾ���ֵ��ֶ�
//	 alert($("#purchaseType").text());
	if ($("#purchaseType").text() != "�ƻ��� ") {
		$("#hiddenA").hide();
		// $("#hiddenB").hide();
	}

	// ���ݲɹ�����Ϊ���з��ࡱʱ����ʾ�����ֶΣ��ɹ����ࡢ�ش�ר�����ơ�ļ���ʽ���Ŀ�������з���Ŀ��
	// alert($("#purchCategory").text());
	if ($("#purchCategory").text() == "�з���") {
		$("#hiddenC").hide();
	} else {
		$("#hiddenD").hide();
		$("#hiddenE").hide();
	}

});