$(function() {
	$("#receiveTable").yxeditgrid({
		objName : 'receive[receiveItem]',
//		delTagName : 'isDelTag',
		url : '?model=asset_purchase_receive_receiveItem&action=listJson',
		param : {
			receiveId : $("#receiveId").val()
		},
		event : {
			removeRow : function(t, rowNum, rowData) {
				check_all();
			}
		},
		isAdd : false,
		colModel : [{
			display : 'id',
			name : 'id',
			type : 'hidden'
		}, {
			display : '�ʲ�����',
			name : 'assetName',
			tclass : 'readOnlyTxtItem'
		}, {
			display : '���',
			name : 'spec',
			tclass : 'readOnlyTxtItem'
		}, {
			display : '����',
			name : 'checkAmount',
			tclass : 'readOnlyTxtItem'
		}, {
			display : '����',
			name : 'price',
			tclass : 'txtshort',
			type : 'money',
			event : {
				blur : function() {
					var rownum = $(this).data('rowNum');// �ڼ���
					var grid = $(this).data('grid');// ������
					var checkAmount = grid.getCmpByRowAndCol(rownum, 'checkAmount').val();
					var price = $(this).val();
					var $amount = grid.getCmpByRowAndCol(rownum, 'amount');
//					$amount.val(price * checkAmount);
					$amount.val(accMul(price,checkAmount));
					var $amountv = $("#"+$amount.attr('id')+'_v');
					$amountv.val(moneyFormat2(accMul(price,checkAmount)));
					check_all();
				}
			}
		}, {
			display : '���',
			name : 'amount',
			type : 'money',
			readonly : true
		}, {
			display : '����',
			name : 'deploy',
			tclass : 'txt'
		}, {
			display : '��ע',
			name : 'remark',
			tclass : 'txt'
		}]
	})
	// ѡ����Ա���
	$("#salvage").yxselect_user({
		hiddenId : 'salvageId',
		isGetDept : [true, "deptId", "deptName"]
	});

	/**
	 * ��֤��Ϣ
	 */
	validate({
		"name" : {
			required : true
		},
		"code" : {
			required : true
		},
		"salvage" : {
			required : true
		},
		"limitYears" : {
			custom : ['date']
		},
		"amount" : {
			custom : ['money']
		},
		"result" : {
			required : true
		}
	});

});

// ���ݴӱ�Ľ�̬�����ܽ��
function check_all() {
	var rowAmountVa = 0;
	var cmps = $("#receiveTable").yxeditgrid("getCmpByCol", "amount");
	cmps.each(function() {
//		rowAmountVa = accAdd(rowAmountVa, $(this).val());
//		rowAmountVa = rowAmountVa+parseFloat($(this).val());
		rowAmountVa = accAdd(rowAmountVa, $(this).val(),2);
	});
	$("#amount").val(rowAmountVa);
	$("#amount_v").val(moneyFormat2(rowAmountVa));
	return false;
}

/*
 * ���ȷ��
 */
function confirmAudit() {
	if (confirm("��ȷ��Ҫ�ύ�����?")) {
		$("#form1").attr("action",
				"?model=asset_purchase_receive_receive&action=edit&actType=audit");
		$("#form1").submit();

	} else {
		return false;
	}
}