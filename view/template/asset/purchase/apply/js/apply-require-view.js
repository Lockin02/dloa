$(document).ready(function() {
	$("#purchaseProductTable").yxeditgrid({
		objName: 'apply[applyItem]',
		title: '������ϸ',
		url: '?model=asset_purchase_apply_applyItem&action=preListJson',
		delTagName: 'isDelTag',
		type: 'view',
		param: {
			applyId: $("#applyId").val(),
			"isDel": '0'
		},
		colModel: [{
			display: '��������',
			name: 'productName',
			tclass: 'readOnlyTxtItem',
			width: 200,
			process: function(v, row) {
				if (v == '') {
					return row.inputProductName;
				} else {
					return v
				}
			}
		}, {
			display: '���',
			name: 'pattem',
			tclass: 'readOnlyTxtItem'
		}, {
			display: '��������',
			name: 'applyAmount',
			tclass: 'txtshort'
		}, {
			display: '��Ӧ��',
			name: 'supplierName',
			tclass: 'txtmiddle'
		}, {
			display: '��λ',
			name: 'unitName',
			tclass: 'readOnlyTxtItem'
		}, {
			display: '�ɹ�����',
			name: 'purchAmount',
			tclass: 'txtshort'
		}, {
			display: '����',
			name: 'price',
			tclass: 'txtshort',
			// type : 'money'
			// �б��ʽ��ǧ��λ
			process: function(v) {
				return moneyFormat2(v);
			}
		}, {
			display: '���',
			name: 'moneyAll',
			tclass: 'txtshort',
			// �б��ʽ��ǧ��λ
			process: function(v) {
				return moneyFormat2(v);
			}
		}, {
			display: 'ϣ����������',
			name: 'dateHope',
			type: 'date'
		}, {
			display: '��ע',
			name: 'remark',
			tclass: 'txt'
		}]
	});

	// ���ݲɹ��������ж��Ƿ���ʾ���ֵ��ֶ�
	// alert($("#purchaseType").text());
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

// �鿴���������
function viewFrom(relDocId, relDocCode) {
	if (relDocId * 1 == relDocId) {
		window.open("?model=asset_require_requirement&action=toViewTab&requireId="
			+ relDocId
			+ "&requireCode="
			+ relDocCode
		);
	} else {
		window.open("?model=common_otherdatas&action=toSignInAwsMenu&reUrl="
			+ "%26cmd%3dcom.actionsoft.apps.asset_GetApplyTask%26id%3d"
			+ relDocId
		);
	}
}