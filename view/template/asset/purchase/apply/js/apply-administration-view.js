$(document).ready(function() {

	$("#purchaseProductTable").yxeditgrid({
		objName : 'apply[applyItem]',
		url : '?model=asset_purchase_apply_applyItem&action=listJson',
		delTagName : 'isDelTag',
		type : 'view',
		param : {
			applyId : $("#applyId").val(),
			"isDel" : '0'
//			,
//			"purchDept" : '0'
		},
		colModel : [{
			display : '��������',
			name : 'productName',
			process : function(v,row) {
				if( v == '' ){
					return row.inputProductName;
				}
					return v;
			},
			tclass : 'readOnlyTxtItem'
		}, {
			display : '���',
			name : 'pattem',
			tclass : 'readOnlyTxtItem'
		}, {
			display : '��������',
			name : 'applyAmount',
			tclass : 'txtshort'
		}, {
			display : '��Ӧ��',
			name : 'supplierName',
			tclass : 'txtmiddle'
		}, {
			display : '��λ',
			name : 'unitName',
			tclass : 'readOnlyTxtItem'
		}, {
			display : '�ɹ�����',
			name : 'purchAmount',
			tclass : 'txtshort'
		}, {
			display : '����',
			name : 'price',
			tclass : 'txtshort',
			// �б��ʽ��ǧ��λ
			process : function(v) {
				return moneyFormat2(v);
			}
		}, {
			display : '���',
			name : 'moneyAll',
			tclass : 'txtshort',
			// �б��ʽ��ǧ��λ
			process : function(v) {
				return moneyFormat2(v);
			}
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