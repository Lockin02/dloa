$(function() {
	$("#purchaseProductTable").yxeditgrid({
		objName : 'apply[applyItem]',
		url : '?model=asset_purchase_apply_applyItem&action=listJson',
		param : {
			applyId : $("#applyId").val(),
			"isDel" : '0'
		},
		isAdd : false,
		event : {
			removeRow : function(t, rowNum, rowData) {
				check_all();
			}
		},
		colModel : [{
			display : 'id',
			name : 'id',
			type : 'hidden'
		}, {
			display : '��������',
			name : 'inputProductName',
			tclass : 'txtshort'
		}, {
			display : '���',
			name : 'pattem',
			tclass : 'txtshort'
		}, {
			display : '��λ',
			name : 'unitName',
			tclass : 'txtshort'
		}, {
			display : '��Ӧ��',
			name : 'supplierName',
			tclass : 'txtmiddle',
			validation : {
				required : true
			}
		}, {
			display : '��������',
			name : 'applyAmount',
			tclass : 'txtshort',
			readonly : true
		}, {
			display : '�ɹ�����',
			name : 'purchAmount',
			tclass : 'txtshort',
			process:function($input,row){
				if(row.purchAmount==""){
					$input.val(row.applyAmount);
				}
			},
			event : {
				blur : function(e) {
					var rownum = $(this).data('rowNum');// �ڼ���
					var colnum = $(this).data('colNum');// �ڼ���
					var grid = $(this).data('grid');// ������
					var price = grid.getCmpByRowAndCol(rownum, 'price').val();
					var purchAmount = $(this).val();
					var applyAmount = grid.getCmpByRowAndCol(rownum,
							'applyAmount').val();
					applyAmount = parseFloat(applyAmount);
					purchAmount = parseFloat(purchAmount);
					if (purchAmount > applyAmount) {
						alert("�ɹ��������ܳ�������������");
						$(this).val(applyAmount);
					}
					var $moneyAll = grid.getCmpByRowAndCol(rownum, 'moneyAll');
					var purchAmount = $(this).val();
//					$moneyAll.val(price * purchAmount);
					$("#"+$moneyAll.attr('id').replace('_v',"")).val(accMul(price,purchAmount));
//					$moneyAll.val(accMul(price,purchAmount));
//					var $moneyAllv = $("#"+$moneyAll.attr('id')+'_v');
					$moneyAll.val(moneyFormat2(accMul(price,purchAmount)));
					check_all();
				}
			},
			validation : {
				custom : ['onlyNumber']
			}
		}, {
			display : '����',
			name : 'price',
			tclass : 'txtshort',
			type : 'money',
			event : {
				blur : function(e) {
					var rownum = $(this).data('rowNum');// �ڼ���
					var colnum = $(this).data('colNum');// �ڼ���
					var grid = $(this).data('grid');// ������
					var purchAmount = grid.getCmpByRowAndCol(rownum,
							'purchAmount').val();
					var price = $(this).val();
					var $moneyAll = grid.getCmpByRowAndCol(rownum, 'moneyAll');
//					$moneyAll.val(accMul(price,purchAmount));
					$("#"+$moneyAll.attr('id').replace('_v',"")).val(accMul(price,purchAmount));
					$moneyAll.val(moneyFormat2(accMul(price,purchAmount)));
					check_all();
				}
			},
			validation : {
				required : true
			}
		}, {
			display : '���',
			name : 'moneyAll',
			tclass : 'readOnlyTxtItem',
			type : 'money'
		}, {
			display : 'ϣ����������',
			name : 'dateHope',
			type : 'date',
			tclass : 'txtshort'
		}, {
			display : '��ע',
			name : 'remark',
			tclass : 'txt'
		}, {
			display : 'ɾ��',
			name : 'isDel',
			type : 'hidden'
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

	/**
	 * ��֤��Ϣ
	 */
//	validate({
//		"estimatPrice" : {
//			required : true
//		},
//		"moneyAll" : {
//			required : true
//		}
//	});
});

// ���ݴӱ�Ľ�̬�����ܽ��
function check_all() {
	var rowAmountVa = 0;
	var cmps = $("#purchaseProductTable").yxeditgrid("getCmpByCol", "moneyAll");
	cmps.each(function() {
//		rowAmountVa = rowAmountVa+parseFloat($(this).val());
		rowAmountVa = accAdd(rowAmountVa, $(this).val(),2);
	});
	$("#moneyAll").val(rowAmountVa);
	$("#moneyAll_v").val(moneyFormat2(rowAmountVa));
	return false;
}
