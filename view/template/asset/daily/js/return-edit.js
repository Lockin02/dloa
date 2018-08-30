$(function() {
	var hasSource = false;// 是否有源单标识
	/**
	 * 选择卡片后自动带出规格，原值等信息
	 */
	var selectAssetFn = function(g, rowNum, rowData) {
		var $assetId = g.getCmpByRowAndCol(rowNum, 'assetId');
		$assetId.val(rowData.id);
		// //这里只带出了规格，其他属性开发员按照此模式完成
		// //卡片rowData.origina里的原值，(rowNum, 'original')为从表的原值.
		var $buyDate = g.getCmpByRowAndCol(rowNum, 'buyDate');
		$buyDate.val(rowData.buyDate);
		var $alreadyDay = g.getCmpByRowAndCol(rowNum, 'alreadyDay');
		$alreadyDay.val(rowData.alreadyDay);
		var $estimateDay = g.getCmpByRowAndCol(rowNum, 'estimateDay');
		$estimateDay.val(rowData.estimateDay);
		var $spec = g.getCmpByRowAndCol(rowNum, 'spec');
		$spec.val(rowData.spec);
		var $residueYears = g.getCmpByRowAndCol(rowNum, 'residueYears');
		$residueYears.val(rowData.estimateDay - rowData.alreadyDay);
	}
	$("#purchaseProductTable").yxeditgrid({
		objName : 'return[item]',
		url : '?model=asset_daily_returnitem&action=listJson',
		param : {
			allocateID : $("#allocateID").val(),
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
			readonly : true,
			process : function($input, rowData) {
				if (!hasSource) {
					var rowNum = $input.data("rowNum");
					var g = $input.data("grid");
					$input.yxcombogrid_asset({
						hiddenId : 'purchaseProductTable_cmp_assetId' + rowNum,
						nameCol : 'assetCode',
						gridOptions : {
							param : {
								'useStatusCode' : 'SYZT-SYZ',
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
											var $assetName = g
													.getCmpByRowAndCol(rowNum,
															'assetName');
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
			}
		}, {
			display : '资产名称',
			name : 'assetName',
			validation : {
				required : true
			},
			readonly : true,
			process : function($input, rowData) {
				if (!hasSource) {
					var rowNum = $input.data("rowNum");
					var g = $input.data("grid");
					$input.yxcombogrid_asset({
						hiddenId : 'purchaseProductTable_cmp_assetId' + rowNum,
						gridOptions : {
							param : {
								'useStatusCode' : 'SYZT-SYZ',
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
											var $assetCode = g
													.getCmpByRowAndCol(rowNum,
															'assetCode');
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
					display : '已经使用期间数',
					name : 'alreadyDay',
					tclass : 'txtshort',
					readonly : true
				}, {
					display : '剩余使用期间数',
					name : 'residueYears',
					tclass : 'txtshort',
					readonly : true
				}, {
					display : '备注',
					name : 'remark',
					tclass : 'txt'
				}]

	});

	// 归还类型，下拉选择不同的归还类型出现对应的类型编号
	// $(function() {
	// if ($("#returnType").val() == "0") {
	// $('.nullType').hide();
	// }
	// })
	// 选择人员组件
	$("#returnMan").yxselect_user({
		hiddenId : 'returnManId',
		isGetDept : [true, "deptId", "deptName"]
	});

	$(function() {
		$("#purchaseProductTable").yxeditgrid("removeAll");
		if ($("#returnTypeVal").val() == "other") {
			hasSource = false;
			$("#borrowNo").yxcombogrid_charge("remove");
			$("#borrowNo").yxcombogrid_borrow("remove");
			$('.nullType').hide();
			$("#purchaseProductTable").yxeditgrid("addRow", 1, {});
			$("#purchaseProductTable").yxeditgrid("showAddBn");
		}
		if ($("#returnTypeVal").val() == "oa_asset_borrow") {
			hasSource = true;
			borrowFun();
			$('#borrowType').show();
			$('#chargeType').hide();
			$('#relNo').show();
			$("#purchaseProductTable").yxeditgrid("hideAddBn");
		}
		if ($("#returnTypeVal").val() == "oa_asset_charge") {
			hasSource = true;
			chargeFun();
			$('#chargeType').show();
			$('#borrowType').hide();
			$('#relNo').show();
			$("#purchaseProductTable").yxeditgrid("hideAddBn");
		}
	});

	$('#returnType').change(function() {
		$("#purchaseProductTable").yxeditgrid("removeAll");

		if ($("#returnType").val() == "other") {
			clearFun();
			hasSource = false;
			$("#borrowNo").yxcombogrid_charge("remove");
			$("#borrowNo").yxcombogrid_borrow("remove");
			$('.nullType').hide();
			$("#purchaseProductTable").yxeditgrid("addRow", 1, {});
			$("#purchaseProductTable").yxeditgrid("showAddBn");
		}
		if ($("#returnType").val() == "oa_asset_borrow") {
			clearFun();
			$('.nullType').hide();
			hasSource = true;
			borrowFun();
			$('#borrowType').show();
			$('#chargeType').hide();
			$('#relNo').show();
			$("#purchaseProductTable").yxeditgrid("hideAddBn");
		}
		if ($("#returnType").val() == "oa_asset_charge") {
			clearFun();
			$('.nullType').hide();
			hasSource = true;
			chargeFun();
			$('#chargeType').show();
			$('#borrowType').hide();
			$('#relNo').show();
			$("#purchaseProductTable").yxeditgrid("hideAddBn");
		}
	});

	/**
	 * 验证信息
	 */
	validate({
		"billNo" : {
			required : true
		}
	});

	function clearFun() {
		$('#borrowId').val("");
		$('#borrowNo').val("");
	}
	// 选择借用单id带出采购借用明细信息
	function borrowFun() {
		$("#borrowNo").yxcombogrid_charge("remove")
		$("#borrowNo").yxcombogrid_borrow({
			hiddenId : 'borrowId',
			gridOptions : {
				param : {
					'unDocStatus' : 'YGH',
					'ExaStatus':'完成'
				},
				// 默认搜索字段名
				sortname : "id",
				// 默认搜索顺序 降序DESC 升序ASC
				sortorder : "DESC",
				showcheckbox : false,
				event : {
					row_dblclick : function(t, row, rowData) {
						$("#purchaseProductTable").yxeditgrid("removeAll");// 先移除所有行
						$.ajax({
							type : "POST",
							async : false,
							url : "?model=asset_daily_borrowitem&action=listpageJson&borrowId="
									+ rowData.id,
							dataType : 'json',
							success : function(result) {
								for (var i = 0; i < result.length; i++) {
									var rowData = {
										assetCode : result[i].assetCode,
										assetName : result[i].assetName,
										spec : result[i].spec,
										buyDate : result[i].buyDate,
										estimateDay : result[i].estimateDay,
										alreadyDay : result[i].alreadyDay,
										residueYears : result[i].estimateDay
												- result[i].alreadyDay
									};
									$("#purchaseProductTable").yxeditgrid(
											"addRow", i, rowData);
								}
							}
						})

					}
				}
			}
		});
	}
	// 选择领用单id带出采购领用明细信息
	function chargeFun() {
		$('#borrowNo').yxcombogrid_borrow("remove");
		$("#borrowNo").yxcombogrid_charge({
			hiddenId : 'borrowId',
			gridOptions : {
				// 默认搜索字段名
				sortname : "id",
				// 默认搜索顺序 降序DESC 升序ASC
				sortorder : "DESC",
				//过滤领用,只显示已签收的领用单
				param : {
					'unDocStatus' : 'YGH',
					'ExaStatus':'完成',
					'isSign':'已签收'
				},
				showcheckbox : false,
				event : {
					row_dblclick : function(t, row, rowData) {
						$("#purchaseProductTable").yxeditgrid("removeAll");// 先移除所有行
						$.ajax({
							type : "POST",
							async : false,
							url : "?model=asset_daily_chargeitem&action=listpageJson&allocateID="
									+ rowData.id,
							dataType : 'json',
							success : function(result) {
								for (var i = 0; i < result.length; i++) {
									var rowData = {
										assetCode : result[i].assetCode,
										assetName : result[i].assetName,
										spec : result[i].spec,
										buyDate : result[i].buyDate,
										estimateDay : result[i].estimateDay,
										alreadyDay : result[i].alreadyDay,
										residueYears : result[i].estimateDay
												- result[i].alreadyDay
									};
									$("#purchaseProductTable").yxeditgrid(
											"addRow", i, rowData);
								}
							}
						})

					}
				}
			}
		});
	}

});
/*
 * 审核确认
 */
function confirmAudit() {
	if (confirm("你确定要提交审核吗?")) {
		$("#form1").attr("action",
				"?model=asset_daily_return&action=edit&actType=audit");
		$("#form1").submit();

	} else {
		return false;
	}
}
