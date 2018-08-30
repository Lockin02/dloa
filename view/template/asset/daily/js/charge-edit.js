function checkAsset(assetCode){
	var flag=0
	 $.ajax({
		type : 'POST',
		url : '?model=asset_assetcard_assetcard&action=checkAsset',
		data : {
			assetCode : assetCode
		},
	    async: false,
		success : function(data) {
			if(data==0){
				return flag=0;
			}else{
				return flag=1;
			}
		}
	})
	return flag
}
$(function() {
	/**
	 * 选择卡片后自动带出规格，原值等信息
	 */
	var selectAssetFn = function(g, rowNum, rowData) {
		var $assetId = g.getCmpByRowAndCol(rowNum, 'assetId');
		$assetId.val(rowData.id);
		var $buyDate = g.getCmpByRowAndCol(rowNum, 'buyDate');
		$buyDate.val(rowData.buyDate);
		var $alreadyDay = g.getCmpByRowAndCol(rowNum, 'alreadyDay');
		$alreadyDay.val(rowData.alreadyDay);
		var $spec = g.getCmpByRowAndCol(rowNum, 'spec');
		$spec.val(rowData.spec);
		var $estimateDay = g.getCmpByRowAndCol(rowNum, 'estimateDay');
		$estimateDay.val(rowData.estimateDay);
		var $residueYears = g.getCmpByRowAndCol(rowNum, 'residueYears');
		$residueYears.val(rowData.estimateDay - rowData.alreadyDay);
	}

	$("#purchaseProductTable").yxeditgrid({
		objName : 'charge[item]',
		url : '?model=asset_daily_chargeitem&action=listJson',
		param : {
			allocateID : $("#allocateID").val(),
			assetId : $("#assetId").val()
		},
		colModel : [{
			display : '关联物料Id',
			name : 'productId',
			type : 'hidden'
		}, {
			display : '对应需求物料',
			name : 'productName',
			tclass : 'readOnlyTxtNormal',
			readonly : true,
			validation : {
				required : true
			},
			process : function($input, rowData) {
				var rowNum = $input.data("rowNum");
				var g = $input.data("grid");
				$input.yxcombogrid_assetrequireitem({
					hiddenId : 'purchaseProductTable_cmp_productId' + rowNum,
					nameCol : 'productName',
					gridOptions : {
						// 过滤卡片,只显示闲置状态的卡片
						 param : {
							 'mainId' : $('#requireId').val()
						 },
						event : {
							row_dblclick : (function(rowNum) {
								return function(e, row, rowData) {
									var $productId = g.getCmpByRowAndCol(
											rowNum, 'productId');
									$productId.val(rowData.productId);
									var $productName = g.getCmpByRowAndCol(
											rowNum, 'productName');
									$productName.val(rowData.description);
								}
							})(rowNum)
						}
					}
				});
			}
		}, {
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
						// 过滤卡片,只显示闲置状态的卡片
						param : {
							'useStatusCode' : 'SYZT-XZ',
							'isDel' : '0',
							'machineCodeSearch':'0',
							'belongTo' : '0',
							'idle' : '0',
							'isScrap':'0'
						},
						event : {
							row_dblclick : (function(rowNum) {
								return function(e, row, rowData) {
									var $cmps = g.getCmpByCol('assetCode');
									var isReturn = false;
//									if(checkAsset(rowData.assetCode)!=0){
//										alert("该资产已借出，请选择其它资产.");
//										return false;
//									}
									$cmps.each(function() {
										if ($(this).val() == rowData.assetCode) {
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
						// 过滤卡片,只显示闲置状态的卡片
						param : {
							'useStatusCode' : 'SYZT-XZ',
							'machineCodeSearch':'0',
							'isDel' : '0',
							'idle' : '0',
							'belongTo' : '0',
							'isScrap':'0'
						},
						event : {
							row_dblclick : (function(rowNum) {
								return function(e, row, rowData) {
									var $cmps = g.getCmpByCol('assetCode');
									var isReturn = false;
//									if(checkAsset(rowData.assetCode)!=0){
//										alert("该资产已借出，请选择其它资产.");
//										return false;
//									}
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
		},
				// {
				// display:'sequence',
				// name : 'sequence',
				// type:'hidden'
				// },
				{
					display : '资产Id',
					name : 'assetId',
					type : 'hidden'
				}, {
					display : '规格型号',
					name : 'spec',
					tclass : 'txtshort',
					readonly : true
				}, {
					display : '购置日期',
					name : 'buyDate',
					// type : 'date',
					tclass : 'txtshort',
					readonly : true
				}, {
					display : '预计使用期间数',
					name : 'estimateDay',
					tclass : 'txtshort',
					readonly : true
				}, {
//					display : '已经使用期间数',
//					name : 'alreadyDay',
//					tclass : 'txtshort',
//					readonly : true
//				}, {
					display : '剩余使用期间数',// 等于卡片的预计使用期间数减去已使用期间数
					name : 'residueYears',
					tclass : 'txtshort',
					readonly : true
				// event : {
				// blur : function(e) {
				// //计算剩余使用年限:卡片的预计使用期间数减去已使用期间数
				// var rownum = $(this).data('rowNum');// 第几行
				// var colnum = $(this).data('colNum');// 第几列
				// var grid = $(this).data('grid');// 表格组件
				// var estimateDay =
				// grid.getCmpByRowAndCol(rownum,'estimateDay').val();
				// var alreadyDay =
				// grid.getCmpByRowAndCol(rownum,'alreadyDay').val();
				// var $residueYears = grid.getCmpByRowAndCol(rownum,
				// 'residueYears');
				// $residueYears.val(accSub(estimateDay,alreadyDay));
				// }
				// }
				}, {
					display : '备注',
					name : 'remark',
					tclass : 'txt'
				}]

	});
	// 选择人员组件
	$("#chargeMan").yxselect_user({
		hiddenId : 'chargeManId',
		isGetDept : [true, "deptId", "deptName"]
	});

	/**
	 * 验证信息
	 */
	validate({
		"billNo" : {
			required : true

		},
		"chargeMan" : {
			required : true
		},
		"chargeDate" : {
			required : true,
			custom : ['date']
		}
	});

});
/*
 * 审核确认
 */
function confirmAudit() {
	if (confirm("你确定要提交审核吗?")) {
		$("#form1").attr("action",
				"?model=asset_daily_charge&action=edit&actType=audit");
		$("#form1").submit();

	} else {
		return false;
	}
}
