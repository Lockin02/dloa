$(function() {
	/**
	 * 选择卡片后自动带出规格，原值等信息
	 */
	var selectAssetFn = function(g, rowNum, rowData) {
		var $assetId = g.getCmpByRowAndCol(rowNum, 'assetId');
		$assetId.val(rowData.id);
		// //这里只带出了规格，其他属性开发员按照此模式完成
		// //卡片rowData.origina里的原值，(rowNum, 'original')为从表的原值.
		var $origina = g.getCmpByRowAndCol(rowNum, 'origina');
		$origina.val(rowData.origina);

		var $originav = $("#" + $origina.attr('id') + "_v");
		$originav.val(moneyFormat2(rowData.origina));

		var $salvage = g.getCmpByRowAndCol(rowNum, 'salvage');
		$salvage.val(rowData.salvage);

		var $salvagev = $("#" + $salvage.attr('id') + "_v");
		$salvagev.val(moneyFormat2(rowData.salvage));

		var $buyDate = g.getCmpByRowAndCol(rowNum, 'buyDate');
		$buyDate.val(rowData.buyDate);

		var $alreadyDay = g.getCmpByRowAndCol(rowNum, 'alreadyDay');
		$alreadyDay.val(rowData.alreadyDay);

		var $depreciation = g.getCmpByRowAndCol(rowNum, 'depreciation');
		$depreciation.val(rowData.depreciation);

		var $depreciationv = $("#" + $depreciation.attr('id') + "_v");
		$depreciationv.val(moneyFormat2(rowData.depreciation));

		var $spec = g.getCmpByRowAndCol(rowNum, 'spec');
		$spec.val(rowData.spec);

		var $orgId = g.getCmpByRowAndCol(rowNum, 'orgId');
		$orgId.val(rowData.orgId);

		var $useOrgId = g.getCmpByRowAndCol(rowNum, 'useOrgId');
		$useOrgId.val(rowData.useOrgId);

		var $orgName = g.getCmpByRowAndCol(rowNum, 'orgName');
		$orgName.val(rowData.orgName);

		var $useOrgName = g.getCmpByRowAndCol(rowNum, 'useOrgName');
		$useOrgName.val(rowData.useOrgName);

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
		objName : 'lose[item]',
		url : '?model=asset_daily_loseitem&action=listJson',
				event : {
			removeRow : function(t, rowNum, rowData) {
				countAmount();
			}
		},
		param : {
			loseId : $("#loseId").val(),
			assetId : $("#assetId").val()
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
			},
			// blur 失焦触发计算金额和数量的方法
			event : {
				blur : function() {
					countAmount();
				}
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
			},
			// blur 失焦触发计算金额和数量的方法
			event : {
				blur : function() {
					countAmount();
				}
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
					display : '所属部门Id',
					name : 'orgId',
					tclass : 'txt',
					type : 'hidden'
				}, {
					display : '所属部门',// orgName
					name : 'orgName',
					tclass : 'txtmiddle',
					readonly : true
				}, {
					display : '使用部门Id',
					name : 'useOrgId',
					tclass : 'txtshort',
					type : 'hidden'
				}, {
					display : '使用部门',// useOrgName
					name : 'useOrgName',
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
				}, {
					display : '购进原值',
					name : 'origina',
					tclass : 'txtmiddle',
					readonly : true,
					type : 'money'
				}, {
					display : '已经使用期间数',
					name : 'alreadyDay',
					tclass : 'txtshort',
					readonly : true
				}, {
					display : '累计折旧金额',
					name : 'depreciation',
					tclass : 'txtmiddle',
					readonly : true,
					type : 'money'
				}, {
					display : '残值',
					name : 'salvage',
					tclass : 'txtmiddle',
					readonly : true,
					type : 'money'
				}, {
					display : '备注',
					name : 'remark',
					tclass : 'txt'
				}, {
					display : '是否报废',
					name : 'isScrap',
					tclass : 'txt',
					value : '0',
					type : 'hidden'
				}]

	});
	// 选择人员组件
	$("#applicat").yxselect_user({
		hiddenId : 'applicatId',
		isGetDept : [true, "deptId", "deptName"]
	});

	/**
	 * 验证信息
	 */
	validate({
		"billNo" : {
			required : true

		},
		"applicat" : {
			required : true
		},
		"loseDate" : {
			custom : ['date']
		},
		"loseAmount" : {
			custom : ['money']
		},
//		"realAmount" : {
//			custom : ['money']
//		},
		"loseNum" : {
			custom : ['onlyNumber']
		}
	});

});

// 根据从表的残值动态计算应付总金额
function countAmount() {
	// 获取当前的行数即卡片的资产数
	var curRowNum = $("#purchaseProductTable").yxeditgrid("getCurShowRowNum")
	$("#loseNum").val(curRowNum);

	var rowAmountVa = 0;
	var cmps = $("#purchaseProductTable").yxeditgrid("getCmpByCol", "salvage");
	cmps.each(function() {
		rowAmountVa = accAdd(rowAmountVa, $(this).val(), 2);
	});
	$("#loseAmount").val(rowAmountVa);
	$("#loseAmount_v").val(moneyFormat2(rowAmountVa));
	return true;
}
/*
 * 审核确认
 */
function confirmAudit() {
	if (confirm("你确定要提交审核吗?")) {
		$("#form1").attr("action",
				"?model=asset_daily_lose&action=edit&actType=audit");
		$("#form1").submit();

	} else {
		return false;
	}
}
