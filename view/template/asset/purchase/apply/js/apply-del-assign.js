$(function() {
	$("#purchaseProductTable").yxeditgrid({
		objName : 'apply[applyItem]',
		url : '?model=asset_purchase_apply_applyItem&action=issuedListJson',
		param : {
			applyId : $("#applyId").val(),
			"purchDept" : '1',
			"isDel" : '0'
		},
		isAddAndDel : false,
//		isAdd : false,
		colModel : [{
			display : 'id',
			name : 'id',
			type : 'hidden'
		}, {
			display : '��������',
			name : 'productName',
			type : 'statictext',
			process : function(v, rowData, $tr, g, $input){
				if( v == '' ){
					return rowData.productCategoryName;
				}else{
					return v;
				}
			}
		}, {
			display : '���',
			name : 'pattem',
			type : 'statictext'
		}, {
			display : '��������',
			name : 'applyAmount',
			type : 'statictext'
		},
//			{
//			display : '��Ӧ��',
//			name : 'supplierName',
//			type : 'statictext'
//		},
			{
			display : '��λ',
			name : 'unitName',
			type : 'statictext'
		}, {
			display : '�ɹ�����',
			name : 'purchAmount',
			type : 'statictext'
		}, {
			display : '�´���������',
			name : 'issuedAmount',
			type : 'statictext'
		},
//			{
//			display : '����',
//			name : 'price',
//			type : 'statictext'
//		}, {
//			display : '���',
//			name : 'moneyAll',
//			type : 'statictext'
//		},
			{
			display : 'ϣ����������',
			name : 'dateHope',
			type : 'statictext'
		}, {
			display : '��ע',
			name : 'remark',
			type : 'statictext'
		}, {
			display : '�ɹ�����',
			name : 'purchDept',
			type : 'select',
			options : [{
				name : "������",
				value : 0
			}, {
				name : "������",
				value : 1
			}]
		}]
	})

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