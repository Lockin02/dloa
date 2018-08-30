$(function() {
//	/**
//	 * 编号唯一性验证
//	 */
//	var url = "?model=asset_purchase_receive_receive&action=checkRepeat";
//	$("#name").ajaxCheck({
//		url : url,
//		alertText : "* 该编号已存在",
//		alertTextOk : "* 该编号可用"
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
								// alert("该收料单的物料已经全部验收.")
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
									display : '采购申请明细表id',
									name : 'contractEquId',
									type : 'hidden'
								},
								{
									display : '收料通知清单id',
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
									display : '资产名称',
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
									display : '物料id',
									name : 'productId',
									type : 'hidden'
								},
								{
									display : '物料编码',
									name : 'sequence',
									type : 'hidden'
								},
								{
									display : '单位',
									name : 'units',
									type : 'hidden'
								},
								{
									display : '规格',
									name : 'spec',
									tclass : 'readOnlyTxtItem',
									process : function($input, row) {
										$input.val(row.pattem);
									}
								},
								{
									display : '数量',
									name : 'checkAmount',
									tclass : 'txtshort',
									process : function($input, row) {
										$input.val(row.arrivalNum);
									},
									event : {
										blur : function(e) {
											if(parseInt($(this).val())>parseInt(e.data.rowData['arrivalNum'])){
												alert("数量大于未验收数量！")
												$(this).val(e.data.rowData['arrivalNum'])
											}											
											var rownum = $(this).data('rowNum');// 第几行
											var grid = $(this).data('grid');// 表格组件
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
									display : '单价',
									name : 'price',
									tclass : 'txtshort',
									type : 'money',
									event : {
										blur : function() {
											var rownum = $(this).data('rowNum');// 第几行
											var grid = $(this).data('grid');// 表格组件
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
									display : '金额',
									name : 'amount',
									tclass : 'readOnlyTxtItem',
									type : 'money',
									process : function($input, row) {
										$input.val((row.arrivalNum)
												* row.price);
									}
								}, {
									display : '备注',
									name : 'remark',
									tclass : 'txt'
								}, {
									display : '合同物料id',
									name : 'contractId',
									type : 'hidden'
								} ]
					});
	// 选择人员组件
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
	//分公司
	$("#companyName").yxcombogrid_branch({// 绑定物料编号
		hiddenId : 'company',
		width:400
	});	

	/**
	 * 验证信息
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

// 根据从表的金额动态计算总金额
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
 * 审核确认
 */
function confirmAudit() {
	if (confirm("你确定要提交审核吗?")) {
		$("#form1")
				.attr("action",
						"?model=asset_purchase_receive_receive&action=add&actType=audit");
		$("#form1").submit();

	} else {
		return false;
	}
}

/**
 * 验证验收金额必填
 */
function checkform() {
	if ($("#amount").val() == "") {
		alert("填写好请验收金额！");
		return false;
	}
	return true;
}