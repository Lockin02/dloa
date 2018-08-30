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
	// /**
	// * 编号唯一性验证
	// */
	//
	// var url = "?model=asset_daily_charge&action=checkRepeat";
	// $("#billNo").ajaxCheck({
	// url : url,
	// alertText : "* 该表单编号已存在",
	// alertTextOk : "* 该表单编号可用"
	// });

	//办事处
	$("#agencyName").yxcombogrid_agency({
		hiddenId : 'agencyCode',
		gridOptions : {
			showcheckbox : false
			,event : {
				'row_dblclick' : function(e, row, data) {
					var rowNum = $("#purchaseProductTable").yxeditgrid('getCurShowRowNum');
					if( typeof(rowNum)=='number' ){
						for( var i=0;i<rowNum;i++ ){
							$('#purchaseProductTable_cmp_productName'+i).yxcombogrid_assetrequireitem('remove');
						}
					}
					$('#agencyCode').val(data.agencyCode);
					$("#purchaseProductTable").yxeditgrid("remove");
					itemAddFun(data.agencyCode);
				}
			}
		}
	});
	// 选择人员组件
	$("#chargeMan").yxselect_user({
		hiddenId : 'chargeManId',
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
		"agencyName" : {
			required : true
		},
		"chargeDate" : {
			required : true,
			custom : ['date']
		}
	});

	$("#itemTable").yxeditgrid({
		url : '?model=asset_require_requireitem&action=listByRequireJson',
		objName : 'requirement[items]',
		type:'view',
		param : {
			mainId : $("#requireId").val()
		},
		title : '领用设备清单',
		isAddOneRow : true,
		colModel : [{
			display : 'id',
			name : 'id',
			tclass : 'txtshort',
			type : 'hidden'
		}, {
			display : '物料id',
			name : 'productId',
			tclass : 'txtshort',
			type : 'hidden'
		}, {
			display : '设备描述',
			name : 'description',
			validation : {
				required : true
			},
			tclass : 'txt'
		}, {
			display : '数量',
			name : 'number',
			validation : {
				required : true ,
				custom : ['onlyNumber']
			},
			tclass : 'txtshort'
		}, {
			display : '已发货数量',
			name : 'executedNum',
			validation : {
				required : true ,
				custom : ['onlyNumber']
			},
			tclass : 'txtshort'
		}, {
			display : '预计金额',
			name : 'expectAmount',
			tclass : 'txtshort',
			process : function(v){
				return moneyFormat2(v);
			}
		}, {
			display : '预计交货日期',
			name : 'dateHope',
			type:'date',
			validation : {
				required : true
			},
			tclass : 'txtshort'
		}, {
			display : '备注',
			name : 'remark',
			tclass : 'txt'
		}]
	})

});

/*
 * 审核确认
 */
function confirmAudit() {
	if (confirm("你确定要提交吗?")) {
		$("#form1").attr("action",
				"?model=asset_daily_charge&action=add&actType=audit");
		$("#form1").submit();

	} else {
		return false;
	}
}

/**
 *
 * 表单验证
 */
function checkSubmit(obj) {	
	var itemTable = $("#itemTable");
	var number = 0;
	var executedNum = 0;
	var itemId = 0;
	var itemIdArr = [];
	var countArr = [];
	//计算借用设备清单未发货数量
	for(var i = 0;i < itemTable.yxeditgrid("getAllAddRowNum");i++){
		number = itemTable.yxeditgrid("getCmpByRowAndCol",i,"number").val();
		executedNum = itemTable.yxeditgrid("getCmpByRowAndCol",i,"executedNum").val();
		itemId = itemTable.yxeditgrid("getCmpByRowAndCol",i,"id").val();
		itemIdArr[itemId] = accSub(number,executedNum);
	}
	var purchaseProductTable = $("#purchaseProductTable");
	//计算选择的卡片数量
	for(var i = 0;i < purchaseProductTable.yxeditgrid("getAllAddRowNum");i++){
		itemId = purchaseProductTable.yxeditgrid("getCmpByRowAndCol",i,"itemId").val();
		if(!countArr[itemId]){
			countArr[itemId] = 1;
		}else{
			countArr[itemId] = ++countArr[itemId];
		}
	}
	//验证选择的卡片数量是否在允许范围内，未发货数量=数量-已发货数量
	for (var i in countArr) { 
		if(itemIdArr[i] && itemIdArr[i]<countArr[i]){
			alert("选择的卡片数量超出领用设备清单未发货数量！");
			return false;
		}
	}
	var rowNum = purchaseProductTable.yxeditgrid('getCurShowRowNum');
	if(rowNum == '0'){
	  alert("资产清单不能为空");
	  return false;
	}else{
		return true;
	}
}

function itemAddFun(agency){
	/**
	 * 选择卡片后自动带出规格，原值等信息
	 */
	var selectAssetFn = function(g, rowNum, rowData) {
		g.getCmpByRowAndCol(rowNum, 'assetId').val(rowData.id);
		g.getCmpByRowAndCol(rowNum, 'assetCode').val(rowData.assetCode);
		g.getCmpByRowAndCol(rowNum, 'assetName').val(rowData.assetName);
		g.getCmpByRowAndCol(rowNum, 'buyDate').val(rowData.buyDate);
		g.getCmpByRowAndCol(rowNum, 'spec').val(rowData.spec);
		g.getCmpByRowAndCol(rowNum, 'estimateDay').val(rowData.estimateDay);
		g.getCmpByRowAndCol(rowNum, 'alreadyDay').val(rowData.alreadyDay);
		g.getCmpByRowAndCol(rowNum, 'residueYears').val(rowData.estimateDay - rowData.alreadyDay);
	}

	$("#purchaseProductTable").yxeditgrid({
		objName : 'charge[item]',
		// url:'?model_asset_daily_loseitem',
		title : '卡片信息',
		isAddOneRow : false,
		colModel : [{
			display : '设备清单Id',
			name : 'itemId',
			type : 'hidden'
		},{
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
					isFocusoutCheck : false,
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
									g.getCmpByRowAndCol(rowNum, 'productId').val(rowData.productId);
									g.getCmpByRowAndCol(rowNum, 'productName').val(rowData.description);
									g.getCmpByRowAndCol(rowNum, 'itemId').val(rowData.id);
								}
							})(rowNum)
						}
					}
				});
			}
		}, {
			display : '卡片编号',
			name : 'assetCode',
			tclass : 'txt',
			validation : {
				required : true
			},
			readonly : true,
			process : function($input, rowData) {
				var rowNum = $input.data("rowNum");
				var g = $input.data("grid");
				$input.yxcombogrid_asset({
					hiddenId : 'purchaseProductTable_cmp_assetId' + rowNum,
					nameCol : 'assetCode',
					gridOptions : {
						// 过滤卡片,只显示闲置状态的卡片
						param : {
							'useStatusCode' : 'SYZT-XZ', //闲置
							'agencyCode' : agency,//办事处
							'machineCodeSearch':'0',//机器码
							'isDel' : '0',//是否删除
							'belongTo' : '0',//是否归属
							'idle' : '0',//是否空闲
							'isScrap' : '0'//是否报废
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
			readonly : true,
			process : function($input, rowData) {
				var rowNum = $input.data("rowNum");
				var g = $input.data("grid");
				$input.yxcombogrid_asset({
					hiddenId : 'purchaseProductTable_cmp_assetId' + rowNum,
					gridOptions : {
						// 过滤卡片,只显示闲置状态的卡片
						param : {
							'useStatusCode' : 'SYZT-XZ',
							'agencyCode' : agency,
							'machineCodeSearch':'0',
							'isDel' : '0',
							'idle' : '0',
							'belongTo' : '0',
							'isScrap' : '0'
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
	//加载选择卡片信息按钮
	$("#purchaseProductTable").find("tr:first td").append("<input type='button' value='选择卡片信息' class='txt_btn_a' style='margin-left:10px;' onclick='selectCard();'/>");
}
//选择卡片信息
function selectCard(){
	showOpenWin("?model=asset_assetcard_assetcard"
			+ "&action=selectCard&showType=charge&agencyCode="
			+$("#agencyCode").val(),1,500,900);
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
		objGrid.yxeditgrid("setRowColValue",tbRowNum,"productId",rows[i].productId);
		objGrid.yxeditgrid("setRowColValue",tbRowNum,"productName",rows[i].productName);
		objGrid.yxeditgrid("setRowColValue",tbRowNum,"assetId",rows[i].id);
		objGrid.yxeditgrid("setRowColValue",tbRowNum,"assetCode",rows[i].assetCode);
		objGrid.yxeditgrid("setRowColValue",tbRowNum,"assetName",rows[i].assetName);
		objGrid.yxeditgrid("setRowColValue",tbRowNum,"buyDate",rows[i].buyDate);
		objGrid.yxeditgrid("setRowColValue",tbRowNum,"spec",rows[i].spec);
		objGrid.yxeditgrid("setRowColValue",tbRowNum,"estimateDay",rows[i].estimateDay);
		objGrid.yxeditgrid("setRowColValue",tbRowNum,"alreadyDay",rows[i].alreadyDay);
		objGrid.yxeditgrid("setRowColValue",tbRowNum,"residueYears",rows[i].estimateDay-rows[i].alreadyDay);
	}
}