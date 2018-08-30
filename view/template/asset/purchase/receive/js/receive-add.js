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

	$("#receiveTable").yxeditgrid({
		objName : 'receive[receiveItem]',
		isAddOneRow : true,
		isAdd : false,
		event : {
			removeRow : function(t, rowNum, rowData) {
				check_all();
			}
		},
		url : '?model=asset_purchase_receive_receiveItem&action=getApplyItemPage',
		param : {
			"applyId" : $("#applyId").val()
		},
		colModel : [{
			display : '采购申请id',
			name : 'applyId',
			type : 'hidden'
		}, {
			display : '采购申请编码',
			name : 'applyCode',
			type : 'hidden'
		}, {
			display : '采购申请明细表id',
			name : 'applyEquId',
			type : 'hidden'
		}, {
			display : '资产名称',
			name : 'assetName',
			tclass : 'readOnlyTxtItem',
			process:function($input,row){
				$input.val(row.productName);
			}
		}, {
			display : '规格',
			name : 'spec',
			tclass : 'readOnlyTxtItem',
			process:function($input,row){
				$input.val(row.pattem);
			}
		}, {
			display : '采购数量',
			name : 'purchAmount',
			type : 'hidden'
		}, {
			display : '数量',
			name : 'checkAmount',
			tclass : 'txtshort',
			process:function($input,row){
				$input.val("");
			},
			event : {
				blur : function() {
					var rownum = $(this).data('rowNum');// 第几行
					var grid = $(this).data('grid');// 表格组件
					var price = grid.getCmpByRowAndCol(rownum, 'price').val();
					var checkAmount = $(this).val();

					var $amount = grid.getCmpByRowAndCol(rownum, 'amount');
					var checkAmount = $(this).val();
					var amountId = $amount.attr('id').replace('_v','');
					var amount=accMul(price,checkAmount);
					setMoney(amountId,amount);
////					$amount.val(price * checkAmount);
//					$amount.val(accMul(price,checkAmount));
//
//					var $amountv = $("#"+$amount.attr('id')+'_v');
//					$amountv.val(moneyFormat2(accMul(price,checkAmount)));
					check_all();
				}
			},
			validation : {
				custom : ['onlyNumber']
			}
		}, {
			display : '单价',
			name : 'price',
			tclass : 'txtshort',
			type : 'money',
			event : {
				blur : function() {
					var rownum = $(this).data('rowNum');// 第几行
					var grid = $(this).data('grid');// 表格组件
					var checkAmount = grid.getCmpByRowAndCol(rownum, 'checkAmount').val();
					var price = $(this).val();
					var $amount = grid.getCmpByRowAndCol(rownum, 'amount');
					var amountId = $amount.attr('id').replace('_v','');
//					$amount.val(price * checkAmount);
					var amount=accMul(price,checkAmount);
					setMoney(amountId,amount);
//					$amount.val(amount);
//					var $amountv = $("#"+$amount.attr('id')+'_v');
//					$amountv.val(moneyFormat2(amount));
					check_all();
				}
			}
		}, {
			display : '金额',
			name : 'amount',
			tclass : 'readOnlyTxtItem',
			type : 'money',
			event : {
				blur : function() {
					var rownum = $(this).data('rowNum');// 第几行
					var grid = $(this).data('grid');// 表格组件
					var checkAmount = grid.getCmpByRowAndCol(rownum, 'checkAmount').val();
					var price = $(this).val();
					var $amount = grid.getCmpByRowAndCol(rownum, 'amount');
					var amountId = $amount.attr('id').replace('_v','');
//					$amount.val(price * checkAmount);
					var amount=accMul(price,checkAmount);
					setMoney(amountId,amount);
//					$amount.val(amount);
//					var $amountv = $("#"+$amount.attr('id')+'_v');
//					$amountv.val(moneyFormat2(amount));
					check_all();
				}
			}
//			,
//			process:function($input,row){
//				$input.val((row.purchAmount-row.checkAmount)*row.price);
//			}
		},
		{
			display : '配置',
			name : 'deploy',
			tclass : 'txt'
		},
		{
			display : '备注',
			name : 'remark',
			tclass : 'txt'
		}]
	});
	// 选择人员组件
	$("#salvage").yxselect_user({
		hiddenId : 'salvageId',
		isGetDept : [true, "deptId", "deptName"],
		event : {
			select : function(e, returnValue) {
				if (returnValue) {
					$('#company').val(returnValue.companyCode)
					$('#companyName').val(returnValue.companyName)
				}
			}
		}
	});

	// 选择采购单id带出采购申请明细信息
	$("#code").yxcombogrid_apply({
		hiddenId : 'applyId',
		gridOptions : {
			showcheckbox : false,
			event : {
				row_dblclick : function(t, row, rowData) {
					$("#receiveTable").yxeditgrid("removeAll");// 先移除所有行
					$.ajax({
						type : "POST",
						async : false,
						url : "?model=asset_purchase_apply_applyItem&action=DelListJson&applyId="
								+ rowData.id,
						dataType : 'json',
						success : function(result) {
							for (var i = 0; i < result.length; i++) {
								if( result[i].productName == '' ){
									var productName = result[i].inputProductName;
								}
								var rowData = {
									applyId : result[i].applyId,
									applyCode : result[i].applyCode,
									applyEquId : result[i].id,
									assetName : productName,
									purchAmount : result[i].purchAmount,
									spec : result[i].pattem,
									checkAmount : result[i].checkAmount,
									price : result[i].price
//									,
//									amount : result[i].moneyAll
								};
								$("#receiveTable").yxeditgrid("addRow", i,
										rowData);
							}
						}
					})

				}
			}
		}
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
			custom : ['date']
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
//		rowAmountVa = rowAmountVa+parseFloat($(this).val());
		rowAmountVa = accAdd(rowAmountVa, $(this).val(),2);
	});
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
function checkform(){
	if($("#amount").val()==""){
		alert("填写好请验收金额！");
		return false;
	}
	return true;
}