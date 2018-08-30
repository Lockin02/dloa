$(function() {
	//邮件渲染
	$("#TO_NAME").yxselect_user({
		hiddenId : 'TO_ID',
		mode : 'check'
	});
	/**
	 * 选择卡片后自动带出规格，原值等信息
	 */
	var selectAssetFn = function(g, rowNum, rowData) {
		var $spec = g.getCmpByRowAndCol(rowNum, 'spec');
		$spec.val(rowData.spec);
		// 这里只带出了规格，其他属性开发员按照此模式完成
		// 卡片rowData.origina里的原值，(rowNum, 'origina')为从表的原值.
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

		var $depreciation = g.getCmpByRowAndCol(rowNum, 'depreciation');
		$depreciation.val(rowData.depreciation);

		var $depreciationv = $("#" + $depreciation.attr('id') + "_v");
		$depreciationv.val(moneyFormat2(rowData.depreciation));

	}

	$("#purchaseProductTable").yxeditgrid({
		objName : 'scrap[item]',
		url : '?model=asset_assetcard_assetcard&action=listJson',
		isAddAndDel : false,
		param : {
			'id' : $('#assetId').val(),
			'useStatusCode' : 'SYZT-XZ',
			'isDel' : '0',
			'isScrap':'0'

		},
		event : {
			reloadData : function(data) {
				countAmount();
			}
		},
		colModel : [{
			display : '卡片编号',
			name : 'assetCode',
			tclass : 'readOnlyTxtNormal',
			readonly : true
//			,process : function($input, rowData) {
//				var rowNum = $input.data("rowNum");
//				var g = $input.data("grid");
//				$input.yxcombogrid_asset({
//					hiddenId : 'purchaseProductTable_cmp_assetId' + rowNum,
//					nameCol : 'assetCode',
//					gridOptions : {
//						param : {
//							'useStatusCode' : 'SYZT-XZ',
//							'isDel' : '0',
//							'isScrap':'0'
//						},
//						event : {
//							row_dblclick : (function(rowNum) {
//								return function(e, row, rowData) {
//									var $cmps = g.getCmpByCol('assetId');
//									var isReturn = false;
//									$cmps.each(function() {
//										if ($(this).val() == rowData.id) {
//											alert("请不要选择相同的资产.");
//											isReturn = true;
//										}
//									});
//									if (!isReturn) {
//										var $assetName = g.getCmpByRowAndCol(
//												rowNum, 'assetName');
//										$assetName.val(rowData.assetName);
//										selectAssetFn(g, rowNum, rowData);
//									} else {
//										return false;
//									}
//									var $salvage = g.getCmpByRowAndCol(
//											rowNum, 'salvage');
//									$salvage.focus(function(){
//										countAmount();
//									});
//									$salvage.focus()
//								}
//							})(rowNum)
//						}
//					}
//				});
//			},
//			// blur 失焦触发计算金额和数量的方法
//			event : {
//				blur : function() {
//					countAmount();
//				}
//			}
		}, {
			display : '资产名称',
			name : 'assetName',
			tclass : 'readOnlyTxtNormal',
			readonly : true
//			,process : function($input, rowData) {
//				var rowNum = $input.data("rowNum");
//				var g = $input.data("grid");
//				$input.yxcombogrid_asset({
//					hiddenId : 'purchaseProductTable_cmp_assetId' + rowNum,
//					gridOptions : {
//						param : {
//							'useStatusCode' : 'SYZT-XZ',
//							'isDel' : '0',
//							'isScrap':'0'
//						},
//						searchId : '',// 按此id值进行搜索过滤
//						event : {
//							row_dblclick : (function(rowNum) {
//								return function(e, row, rowData) {
//									var $cmps = g.getCmpByCol('assetId');
//									var isReturn = false;
//									$cmps.each(function() {
//										if ($(this).val() == rowData.id) {
//											alert("请不要选择相同的资产.");
//											isReturn = true;
//										}
//									});
//									if (!isReturn) {
//										var $assetCode = g.getCmpByRowAndCol(
//												rowNum, 'assetCode');
//										$assetCode.val(rowData.assetCode);
//										selectAssetFn(g, rowNum, rowData);
//									} else {
//										return false;
//									}
//									$salvage = g.getCmpByRowAndCol(
//												rowNum, 'salvage');
//									$salvage.focus(function(){
//										countAmount();
//									});
//									$salvage.focus();
//								}
//							})(rowNum)
//						}
//					}
//				});
//			},
//			// blur 失焦触发计算金额和数量的方法
//			event : {
//				blur : function() {
//					countAmount();
//				}
//			}
		}, {
			display : '资产Id',
			name : 'assetId',
			process : function($input,row){
				var assetId = row.id;
				$input.val(assetId);
			},
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
			display : '资产原值',
			name : 'origina',
			tclass : 'txtshort',
			type : 'money'
		}, {
			display : '残值',
			name : 'salvage',
			tclass : 'txtshort',
			readonly : true,
			type : 'money'
		}, {
			display : '净值',
			name : 'netValue',
			tclass : 'txtshort',
			readonly : true,
			type : 'money'
		}, {
			display : '已提折旧',
			name : 'depreciation',
			tclass : 'txtshort',
			readonly : true,
			type : 'money'
//		}, {
//			display : '出售状态',
//			name : 'sellStatus',
//			value : '未出售',
//			readonly : true,
//			type : 'hidden'
		}, {
			display : '配置',
			name : 'deploy',
			tclass : 'readOnlyTxtNormal',
			readonly : true
		}, {
			display : '备注',
			name : 'remark',
			tclass : 'txt'
		}]

	});
	// 选择人员组件
	$("#proposer").yxselect_user({
		hiddenId : 'proposerId',
		isGetDept : [true, "deptId", "deptName"],
		event : {
			select : function(e, returnValue) {
				if (returnValue) {
					$('#applyCompanyCode').val(returnValue.companyCode)
					$('#applyCompanyName').val(returnValue.companyName)
				}
			}
		}
	});
	// 选择人员组件
	$("#payer").yxselect_user({
		hiddenId : 'payerId',
		event : {
			select : function(e, returnValue) {
				if (returnValue) {
					$('#TO_NAME').val($('#payer').val());
					$('#TO_ID').val($('#payerId').val());
				}
			}
		}
	});
	/**
	 * 验证信息
	 */
	validate({
		"billNo" : {
			required : true
		},
		"proposer" : {
			required : true
		},
		"scrapNum" : {
			required : true,
			custom : ['onlyNumber']
		},
		"amount" : {
			required : true,
			custom : ['money']
		},
		"reason" : {
			required : true
		},
		"scrapDeal" : {
			required : true
		},
//		"hasAccount" : {
//			required : true
//		},
		"payer" : {
			required : true
		},
		"amount_v" : {
			required : true,
			custom : ['money']
		}
	});

});

// 根据从表的残值动态计算应付总金额
function countAmount() {
	// 获取当前的行数即卡片的资产数
	var curRowNum = $("#purchaseProductTable").yxeditgrid("getCurShowRowNum");
	//报废总数
	$("#scrapNum").val(curRowNum);
	var rowsalvageVa = 0;
	var rownetValueVa = 0;
	var salvages = $("#purchaseProductTable").yxeditgrid("getCmpByCol", "salvage");
	salvages.each(function() {
		//$(this).val()获取不到值
		rowsalvageVa = accAdd(rowsalvageVa, $("#"+$(this).attr('id')+"_v").val(), 2);
	});
	var netValues = $("#purchaseProductTable").yxeditgrid("getCmpByCol", "netValue");
	netValues.each(function() {
		//$(this).val()获取不到值
		rownetValueVa = accAdd(rownetValueVa, $("#"+$(this).attr('id')+"_v").val(), 2);
	});
	//总残值
	$("#salvage").val(rowsalvageVa);
	$("#salvage_v").val(moneyFormat2(rowsalvageVa));
	//总净值
	$("#netValue").val(rownetValueVa);
	$("#netValue_v").val(moneyFormat2(rownetValueVa));
	return true;
}
/*
 * 提交财务部确认
 */
function confirmAudit() {
	if (confirm("确定要提交财务部确认吗?")) {
		checkCardStatus();//检查卡片状态
		$("#form1").attr("action",
				"?model=asset_disposal_scrap&action=add&actType=finance");
		$("#form1").submit();

	} else {
		return false;
	}
}
//检查提交财务确认的卡片中是否存在非闲置状态,存在不允许提交
function checkCardStatus(){
	var assetIds = $("#purchaseProductTable").yxeditgrid("getCmpByCol", "assetId");
	var assetIdArr = [];
	assetIds.each(function() {
		assetIdArr.push($(this).val());
	});
	var responseText = $.ajax({
		type : 'POST',
		url : '?model=asset_assetcard_assetcard&action=checkCardStatus',
		data : {
			'assetIdArr' : assetIdArr
		},
		async : false
	}).responseText;
	var data = eval("(" + responseText + ")");
	if(data.length != 0){
		alert("卡片编号为【"+data+"】的卡片已做过报废处理，请勿重新提交！");
		exit();
	}
}