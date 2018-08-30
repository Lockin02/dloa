$(function() {
	$("#RDProductTable").yxeditgrid({
		objName : 'apply[applyItem]',
		// delTagName : 'isDelTag',
		url : '?model=asset_purchase_apply_applyItem&action=listJson',
		param : {
			applyId : $("#applyId").val()
		},
		colModel : [{
			display : 'id',
			name : 'id',
			type : 'hidden'
		}, {
			display : '�豸����',
			name : 'productCode',
			validation : {
				required : true
			}
		}, {
			display : '�豸����',
			name : 'productName',
			validation : {
				required : true
			}
		}, {
			display : '����ͺ�',
			name : 'pattem',
			validation : {
				required : true
			}
		}, {
			display : '��Ӧ��',
			name : 'supplierName',
			validation : {
				required : true
			}
		}, {
			display : '��λ',
			name : 'unitName',
			tclass : 'txtshort',
			validation : {
				required : true
			}
		}, {
			display : '����',
			name : 'applyAmount',
			tclass : 'txtshort',
			validation : {
				custom : ['onlyNumber']
			}
		}, {
			display : 'ϣ����������',
			name : 'dateHope',
			type : 'date',
			tclass : 'txtshort',
			validation : {
				custom : ['date']
			}
		}, {
			display : '�豸ʹ������',
			name : 'life',
			type : 'select',
			tclass : 'txtshort',
			options : [{
				name : "һ������",
				value : 0
			}, {
				name : "һ������",
				value : 1
			}]
		}, {
			display : 'Ԥ�ƹ��뵥��',
			name : 'exPrice',
			type : 'select',
			tclass : 'txtmiddle',
			options : [{
				name : "500Ԫ����",
				value : 0
			}, {
				name : "500Ԫ����",
				value : 1
			}]
		}, {
			display : '�Ƿ�����̶��ʲ�',
			name : 'isAsset',
			type : 'checkbox',
			tclass : 'txtmin'
		}, {
			display : '��ע',
			name : 'remark',
			tclass : 'txt'
		}]
	})

	$("#applicantName").yxselect_user({
		hiddenId : 'applicantId',
		isGetDept : [true, "applyDetId", "applyDetName"]
	});

	// �����Ƿ������з�ר���豸����ʾ�����ֶΣ��з�ר����Ŀ���ơ��з�ר���ţ�

	$('#isrd').change(function() {
		if ($("#isrd").val() == "1") {
			$("#hiddenA").hide();
		} else {
			$('#rdProject').val("");
			$('#rdProjectCode').val("");
			$("#hiddenA").show();
		}
	});

	/**
	 * ��֤��Ϣ
	 */
	validate({
		"userName" : {
			required : true
		},
		"applicantName" : {
			required : true
		},
		"applyTime" : {
			custom : ['date']
		},
		"userTel" : {
			required : false,
			custom : ['onlyNumber']
		}
	});

	});




/*
 * ���ȷ��
 */
function confirmAudit() {
	if (confirm("��ȷ��Ҫ�ύ�����?")) {
		$("#form1").attr("action",
				"?model=asset_purchase_apply_apply&action=add&actType=audit");
		$("#form1").submit();

	} else {
		return false;
	}
}