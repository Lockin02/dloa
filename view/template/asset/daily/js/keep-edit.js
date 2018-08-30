$(function() {
	/**
	 * 选择卡片后自动带出规格，原值等信息
	 */
	var selectAssetFn = function(g, rowNum, rowData) {
		var $assetId = g.getCmpByRowAndCol(rowNum, 'assetId');
		$assetId.val(rowData.id);
		var $userName = g.getCmpByRowAndCol(rowNum, 'userName');
		$userName.val(rowData.userName);
		var $userId = g.getCmpByRowAndCol(rowNum, 'userId');
		$userId.val(rowData.userId);
	}

	$("#purchaseProductTable").yxeditgrid({
		objName : 'keep[item]',
		url : '?model=asset_daily_keepitem&action=listJson',
		param : {
			keepId : $("#keepId").val()
		},
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
			display : '卡片编号',
			name : 'assetCode',
			tclass : 'txt',
			validation : {
				required : true
			},
			process : function($input, rowData) {
				var rowNum = $input.data("rowNum");
				var g = $input.data("grid");
				$input.yxcombogrid_asset({
					hiddenId : 'purchaseProductTable_cmp_assetId' + rowNum,
					nameCol : 'assetCode',
					gridOptions : {
						param : {
							'isDel' : '0',
							'isScrap':'0'
						},
						event : {
							row_dblclick : (function(rowNum) {
								return function(e, row, rowData) {
									var $cmps = g.getCmpByCol('assetCode');
									var isReturn = false;
									$cmps.each(function() {
										if ($(this).val() == rowData.iassetCoded) {
											alert("请不要选择相同的资产.");
											isReturn = true;
										}
									});
									if (!isReturn) {
										var $assetName = g.getCmpByRowAndCol(
												rowNum, 'assetName');
										$assetName.val(rowData.assetName);
										selectAssetFn(g, rowNum, rowData);
									} else {
										return false;
									}
								}
							})(rowNum)
						}
					}
				});
			}
		}, {
			display : '资产名称',
			name : 'assetName',
			validation : {
				required : true
			},
			process : function($input, rowData) {
				var rowNum = $input.data("rowNum");
				var g = $input.data("grid");
				$input.yxcombogrid_asset({
					hiddenId : 'purchaseProductTable_cmp_assetId' + rowNum,
					gridOptions : {
						param : {
							'isDel' : '0',
							'isScrap':'0'
						},
						event : {
							row_dblclick : (function(rowNum) {
								return function(e, row, rowData) {
									var $cmps = g.getCmpByCol('assetCode');
									var isReturn = false;
									$cmps.each(function() {
										if ($(this).val() == rowData.assetCode) {
											alert("请不要选择相同的资产.");
											isReturn = true;
										}
									});
									if (!isReturn) {
										var $assetCode = g.getCmpByRowAndCol(
												rowNum, 'assetCode');
										$assetCode.val(rowData.assetCode);
										selectAssetFn(g, rowNum, rowData);
									} else {
										return false;
									}
								}
							})(rowNum)
						}
					}
				});
			}
		}, {
			display : '资产Id',
			name : 'assetId',
			type : 'hidden'
		}, {
			display : '维修金额',
			name : 'amount',
			tclass : 'txtmiddle',
			type : 'money',
			event : {
				blur : function() {
					check_all();
				}
			}
		}, {
			display : '使用人Id',
			name : 'userId',
			type : 'hidden'
		}, {
			display : '使用人',
			name : 'userName',
			// type : 'date',
			tclass : 'txtshort',
			readonly : true

		}, {
			display : '备注',
			name : 'remark',
			tclass : 'txt'
		}],
		isAddOneRow : false

	});
	// 选择人员组件
	$("#keeper").yxselect_user({
		hiddenId : 'keeperId',
		isGetDept : [true, "deptId", "deptName"]
	});

	/**
	 * 验证信息
	 */
	validate({
		"billNo" : {
			required : true

		},
		"keeper" : {
			required : true
		},
		"keepDate" : {
			custom : ['date']
		}
	});

});
function check_all() {
	// var curRowNum =
	// $("#purchaseProductTable").yxeditgrid("getCurShowRowNum");
	// var rowAmountVa = 0;
	// for (i = 0; i < curRowNum; i++) {
	// // 判断维修金额是否为空
	// if ($("#purchaseProductTable").yxeditgrid("getCmpByRowAndCol", i,
	// "amount").val() == "") {
	// alert("维修金额为空，请填写");
	// return false;
	// }
	// rowAmountVa = accAdd(rowAmountVa, $("#purchaseProductTable")
	// .yxeditgrid("getCmpByRowAndCol", i, "amount").val(), 2);
	// }
	// $("#keepAmount").val(rowAmountVa);

	// var rowAmountVa = 0;
	// var cmps = $("#purchaseProductTable").yxeditgrid("getCmpByCol",
	// "amount");
	// cmps.each(function() {
	// rowAmountVa = accAdd(rowAmountVa, $(this).val());
	// });
	// $("#keepAmount").val(rowAmountVa);
	// return false;

	var rowAmountVa = 0;
	var cmps = $("#purchaseProductTable").yxeditgrid("getCmpByCol", "amount");
	cmps.each(function() {
		rowAmountVa = accAdd(rowAmountVa, $(this).val(), 2);
	});
	$("#keepAmount").val(rowAmountVa);
	$("#keepAmount_v").val(moneyFormat2(rowAmountVa));
	return true;
}
/*
 * 审核确认
 */
function confirmAudit() {
	if (confirm("你确定要提交审核吗?")) {
		$("#form1").attr("action",
				"?model=asset_daily_keep&action=edit&actType=audit");
		$("#form1").submit();

	} else {
		return false;
	}
}
