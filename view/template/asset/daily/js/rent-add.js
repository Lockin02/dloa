$(function() {
	// /**
	// * 编号唯一性验证
	// */
	//
	// var url = "?model=asset_daily_rent&action=checkRepeat";
	// $("#billNo").ajaxCheck({
	// url : url,
	// alertText : "* 该编号已存在",
	// alertTextOk : "* 该编号可用"
	// });

	/**
	 * 选择卡片后自动带出规格，原值等信息
	 */
	var selectAssetFn = function(g, rowNum, rowData) {
		var $spec = g.getCmpByRowAndCol(rowNum, 'spec');
		$spec.val(rowData.spec);

		var $buyDate = g.getCmpByRowAndCol(rowNum, 'buyDate');
		$buyDate.val(rowData.buyDate);

		var $unit = g.getCmpByRowAndCol(rowNum, 'unit');
		$unit.val(rowData.unit);

		var $origina = g.getCmpByRowAndCol(rowNum, 'origina');
		$origina.val(rowData.origina);

	}
	$("#rentTable").yxeditgrid({
		objName : 'rent[rentitem]',
		// url:'?model=asset_daily_borrowitem',
		colModel : [{
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
					hiddenId : 'rentTable_cmp_assetId' + rowNum,
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
					hiddenId : 'rentTable_cmp_assetId' + rowNum,
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
			display : '购入日期',
			name : 'buyDate',
			type : 'date',
			readonly : true
		}, {
			display : '规格型号',
			name : 'spec',
			tclass : 'txtshort',
			readonly : true
		}, {
			display : '单位',
			name : 'unit',
			tclass : 'txtshort',
			readonly : true
		}, {
			display : '原值',
			name : 'origina',
			tclass : 'txtshort',
			readonly : true
		}, {
			display : '出租价格',
			name : 'rentValue',
			type : "money",
			tclass : 'txtshort'
		}, {
			display : '备注',
			name : 'remark',
			tclass : 'txt'
		}],
		data : [{},

		]
	});
	// 选择人员部门组件
	$("#applicat").yxselect_user({
		hiddenId : 'applicatId',
		isGetDept : [true, "deptId", "deptName"],
		mode : 'single'
	});

	/**
	 * 验证信息
	 */
	validate({
		"billNo" : {
			required : true
		},
		"deptName" : {
			required : true
		},
		"lesseeName" : {
			required : true
		},
		"lessee" : {
			required : true
		},
		"contractNo" : {
			required : true
		},
		"rentNum" : {
			required : true,
			custom : ['onlyNumber']
		},
		"rentAmount" : {
			required : true,
			custom : ['onlyNumber']
		},
		"reason" : {
			required : true
		},
		"applicat" : {
			required : true
		},
		"applicatDate" : {
			required : true
		},
		"beginDate" : {
			required : true
		},
		"endDate" : {
			required : true

		}
	});
	// 下拉客户组件
	$("#lesseeName").yxcombogrid_customer({
		hiddenId : 'lesseeid',
		gridOptions : {
			showcheckbox : false,
			event : {
				'row_dblclick' : function(e, row, data) {
				}
			}
		}
	});

});
