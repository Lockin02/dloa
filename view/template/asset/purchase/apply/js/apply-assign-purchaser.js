$(document).ready(function() {
		/**
	 * ��֤��Ϣ
	 */
	validate({
//		"amounts" : {
//			required : true
//		},
		"agencyName" : {
			required : true
		}
	});
		//��ѡ����
		$("#agencyName").yxcombogrid_agency({
			hiddenId : 'agencyCode',
			width:400,
			gridOptions : {
				showcheckbox : false
			}
		});

	$("#purchaseProductTable").yxeditgrid({
		objName : 'apply[applyItem]',
		url : '?model=asset_purchase_apply_applyItem&action=listJson',
		delTagName : 'isDelTag',
		type : 'view',
		param : {
			applyId : $("#applyId").val()
		},
		colModel : [{
			display : '�ɹ�����',
			name : 'purchDept'
			,process : function(v){
				if(v=='1'){
					return '������';
				}else {
					return '������';
				}
			}
		},{
			display : '��������',
			name : 'inputProductName'
		}, {
			display : '���',
			name : 'pattem'
		}, {
			display : '��������',
			name : 'applyAmount',
			tclass : 'txtshort'
		}, {
			display : '��λ',
			name : 'unitName',
			tclass : 'txtshort'
		}, {
			display : 'ϣ����������',
			name : 'dateHope',
			type : 'date'
		}, {
			display : '��ע',
			name : 'remark',
			tclass : 'txt'
		}]
	})

	// ���ݲɹ��������ж��Ƿ���ʾ���ֵ��ֶ�
//	 alert($("#purchaseType").text());
	if ($("#purchaseType").text() != "�ƻ��� ") {
		$("#hiddenA").hide();
		// $("#hiddenB").hide();
	}

	// ���ݲɹ�����Ϊ���з��ࡱʱ����ʾ�����ֶΣ��ɹ����ࡢ�ش�ר�����ơ�ļ���ʽ���Ŀ�������з���Ŀ��
//	 alert($("#purchCategory").text());
	if ($("#purchCategory").text() == "�з���") {
		$("#hiddenC").hide();
	} else {
		$("#hiddenD").hide();
		$("#hiddenE").hide();
	}
	// �ж��Ƿ���ʾ�رհ�ť
	// alert($("#showBtn").val());
	if ($("#showBtn").val() == 1) {
		$("#btn").hide();
		$("#hiddenF").hide();
	}
});