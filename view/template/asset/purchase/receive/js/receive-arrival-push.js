$(function() {
//	/**
//	 * ���Ψһ����֤
//	 */
//	var url = "?model=asset_purchase_receive_receive&action=checkRepeat";
//	$("#name").ajaxCheck({
//		url : url,
//		alertText : "* �ñ���Ѵ���",
//		alertTextOk : "* �ñ�ſ���"
//	});

	$("#receiveTable")
			.yxeditgrid(
					{
						objName : 'receive[receiveItem]',
						// isAddOneRow : true,
						isAdd : false,
						url : '?model=asset_purchase_receive_receiveItem&action=getArrivalEqus',
						param : {
							"arrivalId" : $("#arrivalId").val()
						},
						event : {
							reloadData : function(e, g, data) {
								// var isCanAdd = false;
								// for ( var i = 0; i < data.length; i++) {
								// if (data[i].checkAmount > 0) {
								// isCanAdd = true;
								// }
								// }
								// if (!isCanAdd) {
								// alert("�����ϵ��������Ѿ�ȫ������.")
								// self.parent.tb_remove();
								// }
								check_all();
							},
							removeRow : function(t, rowNum, rowData) {
								check_all();
							}
						},
						colModel : [
								{
									display : '�ɹ�������ϸ��id',
									name : 'contractEquId',
									type : 'hidden'
								},
								{
									display : '����֪ͨ�嵥id',
									name : 'arrivalEquId',
									process : function($input, row) {
										// if (row.id == '') {
										$input.val(row.id);
										// } else {
										// $input.val('');
										// }
									},
									type : 'hidden'
								},
								{
									display : '�ʲ�����',
									name : 'assetName',
									tclass : 'readOnlyTxtItem',
									process : function($input, row) {
										if (row.productName == '') {
											$input.val(row.inputProductName);
										} else {
											$input.val(row.productName);
										}
									}
								},
								{
									display : '����id',
									name : 'productId',
									type : 'hidden'
								},
								{
									display : '���ϱ���',
									name : 'sequence',
									type : 'hidden'
								},
								{
									display : '��λ',
									name : 'units',
									type : 'hidden'
								},
								{
									display : '���',
									name : 'spec',
									tclass : 'readOnlyTxtItem',
									process : function($input, row) {
										$input.val(row.pattem);
									}
								},
								{
									display : '����',
									name : 'checkAmount',
									tclass : 'txtshort',
									process : function($input, row) {
										$input.val(row.arrivalNum);
									},
									event : {
										blur : function(e) {
											if(parseInt($(this).val())>parseInt(e.data.rowData['arrivalNum'])){
												alert("��������δ����������")
												$(this).val(e.data.rowData['arrivalNum'])
											}											
											var rownum = $(this).data('rowNum');// �ڼ���
											var grid = $(this).data('grid');// ������
											var price = grid.getCmpByRowAndCol(
													rownum, 'price').val();
											var checkAmount = $(this).val();

											var $amount = grid
													.getCmpByRowAndCol(rownum,
															'amount');
											var checkAmount = $(this).val();
											var amountId = $amount.attr('id')
													.replace('_v', '');
											var amount = accMul(price,
													checkAmount);
											setMoney(amountId, amount);
											check_all();

										}
									},
									validation : {
										custom : [ 'onlyNumber' ]
									}
								},
								{
									display : '����',
									name : 'price',
									tclass : 'txtshort',
									type : 'money',
									event : {
										blur : function() {
											var rownum = $(this).data('rowNum');// �ڼ���
											var grid = $(this).data('grid');// ������
											var checkAmount = grid
													.getCmpByRowAndCol(rownum,
															'checkAmount')
													.val();
											var price = $(this).val();
											var $amount = grid
													.getCmpByRowAndCol(rownum,
															'amount');
											var amountId = $amount.attr('id')
													.replace('_v', '');
											var amount = accMul(price,
													checkAmount);
											setMoney(amountId, amount);
											check_all();
										}
									}
								},
								{
									display : '���',
									name : 'amount',
									tclass : 'readOnlyTxtItem',
									type : 'money',
									process : function($input, row) {
										$input.val((row.arrivalNum)
												* row.price);
									}
								}, {
									display : '��ע',
									name : 'remark',
									tclass : 'txt'
								}, {
									display : '��ͬ����id',
									name : 'contractId',
									type : 'hidden'
								} ]
					});
	// ѡ����Ա���
	$("#salvage").yxselect_user({
		hiddenId : 'salvageId',
		isGetDept : [ true, "deptId", "deptName" ],
		event : {
			select : function(e, returnValue) {
				if (returnValue) {
					$('#company').val(returnValue.companyCode)
					$('#companyName').val(returnValue.companyName)
				}
			}
		}
	});
	//�ֹ�˾
	$("#companyName").yxcombogrid_branch({// �����ϱ��
		hiddenId : 'company',
		width:400
	});	

	/**
	 * ��֤��Ϣ
	 */
	validate({
		"code" : {
			required : true
		},
		"salvage" : {
			required : true
		},
		"limitYears" : {
			custom : [ 'date' ]
		},
		"amount" : {
			required : true
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
		// rowAmountVa = rowAmountVa+parseFloat($(this).val());
		rowAmountVa = accAdd(rowAmountVa, $(this).val(), 2);
	});
	// setMoney('amount',rowAmountVa);
	$("#amount").val(rowAmountVa);
	$("#amount_v").val(moneyFormat2(rowAmountVa));
	return false;
}

/*
 * ���ȷ��
 */
function confirmAudit() {
	if (confirm("��ȷ��Ҫ�ύ�����?")) {
		$("#form1")
				.attr("action",
						"?model=asset_purchase_receive_receive&action=add&actType=audit");
		$("#form1").submit();

	} else {
		return false;
	}
}

/**
 * ��֤���ս�����
 */
function checkform() {
	if ($("#amount").val() == "") {
		alert("��д�������ս�");
		return false;
	}
	return true;
}