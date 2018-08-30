$(function() {
	/**
	 * 选择卡片后自动带出规格，原值等信息
	 */
	var selectAssetFn = function(g, rowNum, rowData) {
		g.getCmpByRowAndCol(rowNum, 'assetId').val(rowData.id);
		g.getCmpByRowAndCol(rowNum, 'spec').val(rowData.spec);
		g.getCmpByRowAndCol(rowNum, 'englishName').val(rowData.englishName);
		g.getCmpByRowAndCol(rowNum, 'buyDate').val(rowData.buyDate);
		g.getCmpByRowAndCol(rowNum, 'alreadyDay').val(rowData.alreadyDay);
		g.getCmpByRowAndCol(rowNum, 'beforeUse').val(rowData.useType);
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
		// url:'?model_asset_disposal_sellitem',
		title : '卡片信息',
		isAddOneRow : false,
		event : {
			removeRow : function(t, rowNum, rowData) {
				countAmount();
			}
		},
		colModel : [{
			display : '卡片编号',
			name : 'assetCode',
			validation : {
				required : true
			},
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
			validation : {
				required : true
			},
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
							'row_dblclick' : (function(rowNum) {
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
	//加载选择卡片信息按钮
	$("#purchaseProductTable").find("tr:first td").append("<input type='button' value='选择卡片信息' class='txt_btn_a' style='margin-left:10px;' onclick='selectCard();'/>");
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
				"?model=asset_disposal_sell&action=add&actType=audit");
		$("#form1").submit();

	} else {
		return false;
	}
}
//选择卡片信息
function selectCard(){
	showOpenWin("?model=asset_assetcard_assetcard"
			+ "&action=selectCard&showType=sell"
			,1,500,900);
}
//设置卡片内容
function setDatas(rows){
	var objGrid = $("#purchaseProductTable");
	for(var i = 0; i < rows.length ; i++){
		//判断卡片编码是否已存在
		var assetIdArr = objGrid.yxeditgrid("getCmpByCol","assetCode");
		var isExist = false;
		if(assetIdArr.length > 0){
			assetIdArr.each(function(){
				if(this.value == rows[i].assetCode){
					isExist = true;
					alert("请不要选择相同的资产" );
					return false;
				}
			});
		}
		//如果已经重复了，就不能继续选择
		if(isExist){
			return false;
		}
		//重新获取行数
		var tbRowNum = objGrid.yxeditgrid("getAllAddRowNum");
		//新增行
		objGrid.yxeditgrid("addRow",tbRowNum);
		objGrid.yxeditgrid("setRowColValue",tbRowNum,"assetId",rows[i].id);
		objGrid.yxeditgrid("setRowColValue",tbRowNum,"assetCode",rows[i].assetCode);
		objGrid.yxeditgrid("setRowColValue",tbRowNum,"assetName",rows[i].assetName);
		objGrid.yxeditgrid("setRowColValue",tbRowNum,"spec",rows[i].spec);
		objGrid.yxeditgrid("setRowColValue",tbRowNum,"englishName",rows[i].englishName);
		objGrid.yxeditgrid("setRowColValue",tbRowNum,"buyDate",rows[i].buyDate);
		objGrid.yxeditgrid("setRowColValue",tbRowNum,"alreadyDay",rows[i].alreadyDay);
		objGrid.yxeditgrid("setRowColValue",tbRowNum,"beforeUse",rows[i].useType);
		if(rows[i].salvage){
			objGrid.yxeditgrid("setRowColValue",tbRowNum,"salvage",rows[i].salvage,true);
		}else{
			objGrid.yxeditgrid("setRowColValue",tbRowNum,"salvage",0,true);	
		}
		if(rows[i].depreciation){
			objGrid.yxeditgrid("setRowColValue",tbRowNum,"depreciation",rows[i].depreciation,true);
		}	
		else{
			objGrid.yxeditgrid("setRowColValue",tbRowNum,"depreciation",0,true);
		}
		var $equip = objGrid.yxeditgrid("getCmpByRowAndCol",tbRowNum, 'equip');
		$equip.children().unbind("click");
		$equip.unbind("click");
		$equip.click((function(assetCode) {
			return function() {
				window.open('?model=asset_assetcard_equip&action=toPage&assetCode=' + assetCode);
			}
		})(rows[i].assetCode));
		countAmount();
	}
}