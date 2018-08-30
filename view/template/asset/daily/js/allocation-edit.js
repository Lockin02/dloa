$(function() {
	//根据调拨类型显示/隐藏部门信息或区域信息
	switch ($('#alloType').val()) {
		case 'DTD' :
			$(".outDeptType").show();
			$(".inDeptType").show();
			$(".outAgencyType").hide();
			$(".inAgencyType").hide();
			break;
		case 'ATA' :
			$(".outAgencyType").show();
			$(".inAgencyType").show();
			$(".outDeptType").hide();
			$(".inDeptType").hide();
			break;
	}
	
	$("#allocationTable").yxeditgrid({
		objName : 'allocation[allocationitem]',
		url : '?model=asset_daily_allocationitem&action=listJson',
		param : {
			allocateID : $("#allocateID").val(),
			// sequence : $("#sequence").val(),
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
					hiddenId : 'allocationTable_cmp_assetId' + rowNum,
					nameCol : 'assetCode',
					gridOptions : {
						param : {
							'useStatusCode' : 'SYZT-XZ',
							'isDel' : '0',
							'isScrap' : '0',
							'belongTo' : '0',
							'machineCodeSearch':'0',
							'orgId' : $('#outDeptId').val(),
							'useProId' : $('#outProId').val()
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
										g.setRowColValue(rowNum,'assetName',rowData.assetName);
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
					hiddenId : 'allocationTable_cmp_assetId' + rowNum,
					gridOptions : {
						param : {
							'useStatusCode' : 'SYZT-XZ',
							'isDel' : '0',
							'isScrap' : '0',
							'belongTo' : '0',
							'machineCodeSearch':'0',
							'orgId' : $('#outDeptId').val(),
							'useProId' : $('#outProId').val()
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
										g.setRowColValue(rowNum,'assetCode',rowData.assetCode);
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
			display : '英文名称',
			name : 'englishName',
			type : 'hidden'
		}, {
			display : '购置日期',
			name : 'buyDate',
			tclass : 'readOnlyTxtNormal',
			readonly : true,
			width : 80
		}, {
			display : '原值',
			name : 'origina',
			type : 'money',
			tclass : 'readOnlyTxtNormal',
			readonly : true,
			width : 80
		}, {
			display : '规格型号',
			name : 'spec',
			tclass : 'readOnlyTxtNormal',
			readonly : true,
			width : 150
		}, {
			display : '机器码',
			name : 'sequence',
			tclass : 'readOnlyTxtNormal',
			readonly : true,
			width : 150
		}, {
			display : '配置',
			name : 'deploy',
			tclass : 'readOnlyTxtNormal',
			readonly : true,
			width : 150
		}, {
			display : '附属设备',
			name : 'equip',
			type : 'statictext',
			process : function(e, data) {
				if (data) {
					var $href = $("<a>详细</a>");
					$href.attr("href", "#");
					$href.click(function() {
						window.open('?model=asset_assetcard_equip&action=toPage&assetId='
										+ data.assetId);
					})
					return $href;
				} else {
					return '<a href="#" >详细</a>';
				}
			}
		}, {
			display : '耐用年限',
			name : 'estimateDay',
			type : 'hidden'
		}, {
			display : '已经使用期间数',
			name : 'alreadyDay',
			type : 'hidden'
		}, {
			display : '月折旧额',
			name : 'monthDepr',
			type : 'hidden'
		}, {
			display : '已折旧金额',
			name : 'depreciation',
			type : 'hidden'
		}, {
			display : '残余价值',
			name : 'salvage',
			type : 'hidden'
		}, {
			display : '调出前用途',
			name : 'beforeUse',
			type : 'hidden'

		}, {
			display : '调入后用途',
			name : 'afterUse',
			tclass : 'txtshort'

		}, {
			display : '调出前存放地点',
			name : 'beforePlace',
			type : 'hidden'

		}, {
			display : '调入后存放地点',
			name : 'afterPlace',
			tclass : 'txtshort'

		}, {
			display : '备注',
			name : 'remark',
			tclass : 'txt'
		}]
	});
	// // 选择调出部门组件
	// $("#outDeptName").yxselect_dept({
	// hiddenId : 'outDeptId',
	// mode : 'single'
	// });
	// // 选择调入部门组件
	// $("#inDeptName").yxselect_dept({
	// hiddenId : 'inDeptId',
	// mode : 'single'
	// });

	// 选择调拨申请人组件
	$("#proposer").yxselect_user({
		hiddenId : 'proposerId',
		mode : 'single'
	});
	// 选择调入确认人组件
	$("#recipient").yxselect_user({
		hiddenId : 'recipientId',
		mode : 'single'
	});
	/**
	 * 验证信息
	 */
	validate({
		"billNo" : {
			required : true
		},
		"moveDate" : {
			required : true
		},
//		"outDeptName" : {
//			required : true
//		},
//		"inDeptName" : {
//			required : true
//		},
		"proposer" : {
			required : true
		},
		"recipient" : {
			required : true
		}
	});

});

//选择卡片时带出卡片信息
function selectAssetFn (g, rowNum, rowData) {
	g.setRowColValue(rowNum,'englishName',rowData.englishName);
	g.setRowColValue(rowNum,'spec',rowData.spec);
	g.setRowColValue(rowNum,'deploy',rowData.deploy);
	g.setRowColValue(rowNum,'buyDate',rowData.buyDate);
	g.setRowColValue(rowNum,'estimateDay',rowData.estimateDay);
	g.setRowColValue(rowNum,'alreadyDay',rowData.alreadyDay);
	g.setRowColValue(rowNum,'monthDepr',rowData.monthlyDepreciation);
	g.setRowColValue(rowNum,'depreciation',rowData.depreciation);
	g.setRowColValue(rowNum,'origina',rowData.origina,true);
	g.setRowColValue(rowNum,'salvage',rowData.salvage,true);
	g.setRowColValue(rowNum,'beforeUse',rowData.useType);
	g.setRowColValue(rowNum,'beforePlace',rowData.place);

	var $equip = g.getCmpByRowAndCol(rowNum, 'equip');
	$equip.children().unbind("click");
	$equip.unbind("click");
	$equip.click((function(id) {
		return function() {
			window.open('?model=asset_assetcard_equip&action=toPage&assetId='
				+ id);
		}
	})(rowData.id));
}

//审核确认
function confirmAudit() {
	if (confirm("你确定要提交审核吗?")) {
		$("#form1").attr("action",
				"?model=asset_daily_allocation&action=edit&actType=audit");
		$("#form1").submit();

	} else {
		return false;
	}
}