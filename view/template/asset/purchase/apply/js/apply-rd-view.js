$(document).ready(function() {

	$("#RDProductTable").yxeditgrid({
		objName : 'apply[applyItem]',
		url : '?model=asset_purchase_apply_applyItem&action=listJson',
		delTagName : 'isDelTag',
		type : 'view',
		param : {
			applyId : $("#id").val()
		},
		event : {
			reloadData : function(event, g) {
				var rowCount = g.getCurRowNum();
				for (var rowNum = 0; rowNum < rowCount; rowNum++) {
					if ($("#RDProductTable_cmp_equUseYear" + rowNum).val() == "0") {
						$("#RDProductTable_cmp_equUseYear" + rowNum)
								.val("һ������");
					} else {
						$("#RDProductTable_cmp_equUseYear" + rowNum)
								.val("һ������");
					}
					if ($("#RDProductTable_cmp_planPrice" + rowNum).val() == "0") {
						$("#RDProductTable_cmp_planPrice" + rowNum)
								.val("500Ԫ����");
					} else {
						$("#RDProductTable_cmp_planPrice" + rowNum)
								.val("500Ԫ����");
					}
				}
			}
		},
		colModel : [{
					// display : '�豸����',
					// name : 'productCode'
					// }, {
					display : '�豸����',
					name : 'productName'
				}, {
					display : '����ͺ�',
					name : 'pattem'

				}, {
					display : '��λ',
					name : 'unitName',
					tclass : 'txtshort'
				}, {
					display : '����',
					name : 'applyAmount',
					tclass : 'txtshort'
				}, {
					display : 'ϣ����������',
					name : 'dateHope',
					type : 'date',
					tclass : 'txtshort'
				}, {
					display : '��Ӧ��',
					name : 'supplierName'

				}, {
					display : '�豸ʹ������',
					name : 'equUseYear',
					tclass : 'txtshort',
					process : function(v, row) {
						if (v == "0") {
							return "һ������";
						} else {
							return "һ������";
						}
					}
				}, {
					display : 'Ԥ�ƹ��뵥��',
					name : 'planPrice',
					type : 'select',
					tclass : 'txtmiddle',
					process : function(v, row) {
						if (v == "0") {
							return "500Ԫ����";
						} else {
							return "500Ԫ����";
						}
					}

				}, {
					display : '��ע',
					name : 'remark',
					tclass : 'txt'
				}]
	})
	// �����Ƿ������з�ר���豸����ʾ�����ֶΣ��з�ר����Ŀ���ơ��з�ר���ţ�
	if ($("#isrd").text() == "1") {
		$("#hiddenA").hide();
	} else {
		$("#hiddenA").show();
	}

	// �ж��Ƿ���ʾ�رհ�ť
	// alert($("#showBtn").val());
	if ($("#showBtn").val() == 1) {
		$("#btn").hide();
		$("#hiddenF").hide();
	}
});