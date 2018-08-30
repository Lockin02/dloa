$(function() {
	/**
	 * 选择卡片后自动带出规格，原值等信息
	 */
	var selectAssetFn = function(g, rowNum, rowData) {
		var $assetId = g.getCmpByRowAndCol(rowNum, 'assetId');
		$assetId.val(rowData.id);

		var $spec = g.getCmpByRowAndCol(rowNum, 'spec');
		$spec.val(rowData.spec);
		// 这里只带出了规格，其他属性开发员按照此模式完成
		// 卡片rowData.origina里的原值，(rowNum, 'original')为从表的原值.
		// var $original = g.getCmpByRowAndCol(rowNum, 'original');
		// $original.val(rowData.origina);
		var $englishName = g.getCmpByRowAndCol(rowNum, 'englishName');
		$englishName.val(rowData.englishName);

		var $buyDate = g.getCmpByRowAndCol(rowNum, 'buyDate');
		$buyDate.val(rowData.buyDate);

		var $alreadyDay = g.getCmpByRowAndCol(rowNum, 'alreadyDay');
		$alreadyDay.val(rowData.alreadyDay);

		var $beforeUse = g.getCmpByRowAndCol(rowNum, 'beforeUse');
		$beforeUse.val(rowData.useType);

		if(rowData.salvage){
			g.setRowColValue(rowNum,'salvage',rowData.salvage,true);
		}else{
			g.setRowColValue(rowNum,'salvage',0,true);
		}
		if(rowData.depreciation){
			g.setRowColValue(rowNum,'depreciation',rowData.depreciation,true);
		}else{
			g.setRowColValue(rowNum,'depreciation',0,true);
		}

		var $equip = g.getCmpByRowAndCol(rowNum, 'equip');
		$equip.children().unbind("click");
		$equip.unbind("click");
		$equip.click((function(assetCode) {
			return function() {
				window
						.open('?model=asset_assetcard_equip&action=toPage&assetCode='
								+ assetCode);
			}
		})(rowData.assetCode));
	}

	$("#purchaseProductTable").yxeditgrid({
		objName : 'sell[item]',
		url : '?model=asset_disposal_sellitem&action=listJson',
		event : {
			removeRow : function(t, rowNum, rowData) {
				countAmount();
			}
		},
		param : {
			sellID : $("#sellID").val(),
			assetId : $("#assetId").val()
		},
		colModel : [{
			display : 'id',
			name : 'id',
			type : 'hidden'
		}, {
			display : '卡片编号',
			name : 'assetCode',
			process : function($input, rowData) {
				var rowNum = $input.data("rowNum");
				var g = $input.data("grid");
				$input.yxcombogrid_asset({
					hiddenId : 'cmp_assetId' + rowNum,
					nameCol : 'assetCode',
					gridOptions : {
						param : {
							'isDel' : '0',
							'isSell' : '0'
						},
						event : {
							row_dblclick : (function(rowNum) {
								return function(e, row, rowData) {
									var $cmps = g.getCmpByCol('assetId');
									var isReturn = false;
									$cmps.each(function() {
										if ($(this).val() == rowData.id) {
											alert("请不要选择相同的资产.");
											isReturn = true;
										}
									});
									if (!isReturn) {
										var $assetName = g.getCmpByRowAndCol(
												rowNum, 'assetName');
										$assetName.val(rowData.assetName);
										var $assetCode = g.getCmpByRowAndCol(
												rowNum, 'assetCode');
										$assetCode.val(rowData.assetCode);
										selectAssetFn(g, rowNum, rowData);
										countAmount();
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
			process : function($input, rowData) {
				var rowNum = $input.data("rowNum");
				var g = $input.data("grid");
				$input.yxcombogrid_asset({
					hiddenId : 'cmp_assetId' + rowNum,
					gridOptions : {
						param : {
							'isDel' : '0',
							'isSell' : '0'
						},
						event : {
							row_dblclick : (function(rowNum) {
								return function(e, row, rowData) {
									var $cmps = g.getCmpByCol('assetId');
									var isReturn = false;
									$cmps.each(function() {
										if ($(this).val() == rowData.id) {
											alert("请不要选择相同的资产.");
											isReturn = true;
										}
									});
									if (!isReturn) {
										var $assetCode = g.getCmpByRowAndCol(
												rowNum, 'assetCode');
										$assetCode.val(rowData.assetCode);
										var $assetName = g.getCmpByRowAndCol(
												rowNum, 'assetName');
										$assetName.val(rowData.assetName);
										selectAssetFn(g, rowNum, rowData);
										countAmount();
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
			display : '英文名称',
			name : 'englishName',
			tclass : 'txtshort',
			readonly : true
		}, {
			display : '购置日期',
			name : 'buyDate',
			type : 'date',
			tclass : 'txtshort',
			readonly : true
		}, {
			display : '规格型号',
			name : 'spec',
			tclass : 'txtshort',
			readonly : true
		}, {
			display : '附属设备',
			name : 'equip',
			type : 'statictext',
			process : function(e, data) {
				if (data) {
					var $href = $("<a>详细</a>");
					$href.attr("href", "#");
					$href.click(function() {
						window
								.open('?model=asset_assetcard_equip&action=toPage&assetCode='
										+ data.assetCode);
					})
					return $href;
				} else {
					return '<a href="#" >详细</a>';
				}
			}

		},
				// {
				// display:'耐用年限',
				// name : 'estimateDay',
				// tclass : 'txtshort'
				// },
				{
					display : '已经使用期间数',// 已使用期间数alreadyDay
					name : 'alreadyDay',
					tclass : 'txtshort',
					readonly : true
				}, {
					display : '售出部门',// 不带
					name : 'deptName',
					tclass : 'txtshort'
				}, {
					display : '售出前用途',// 用途useType
					name : 'beforeUse',
					tclass : 'txtshort',
					readonly : true
				}, {
					display : '已折旧金额',// 累计折旧depreciation
					name : 'depreciation',
					tclass : 'txtshort',
					type : 'money',
					readonly : true
				}, {
					display : '残余价值',// 预计净残值salvage
					name : 'salvage',
					tclass : 'txtshort',
					type : 'money',
					readonly : true
				}
				// , {
				// display:'月折旧额',//不带，计算出来的。
				// name : 'monthDepr',
				// tclass : 'txtshort'
				// }
				, {
					display : '备注',// 不带，可改
					name : 'remark',
					tclass : 'txt'
				}]

	});
	// 选择人员组件
	$("#seller").yxselect_user({
		hiddenId : 'sellerId',
		isGetDept : [true, "deptId", "deptName"]
	});

	/**
	 * 验证信息
	 */

	validate({
		"billNo" : {
			required : true
		},
		"seller" : {
			required : true
		},
		"sellNum" : {
			required : true,
			custom : ['onlyNumber']
		},
		"sellAmount" : {
			required : true,
			custom : ['money']
		},
		"donationDate" : {
			required : true
		}
	});

});
// 根据从表的残值动态计算应付总金额
function countAmount() {
	// 获取当前的行数即卡片的资产数
	var curRowNum = $("#purchaseProductTable").yxeditgrid("getCurShowRowNum")
	$("#sellNum").val(curRowNum);
	var rowAmountVa = 0;
	var cmps = $("#purchaseProductTable").yxeditgrid("getCmpByCol", "salvage");
	cmps.each(function() {
		rowAmountVa = accAdd(rowAmountVa, $(this).val(), 2);
	});
	$("#sellAmount").val(rowAmountVa);
	$("#sellAmount_v").val(moneyFormat2(rowAmountVa));
	return true;
}
/*
 * 审核确认
 */
function confirmAudit() {
	if (confirm("你确定要提交审核吗?")) {
		$("#form1").attr("action",
				"?model=asset_disposal_sell&action=edit&actType=audit");
		$("#form1").submit();

	} else {
		return false;
	}
}