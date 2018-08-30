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
	//
	// var url = "?model=asset_daily_borrow&action=checkRepeat";
	// $("#billNo").ajaxCheck({
	// url : url,
	// alertText : "* 该编号已存在",
	// alertTextOk : "* 该编号可用"
	// });
//	itemAddFun();
	// 选择使用人组件
	$("#chargeMan").yxselect_user({
		hiddenId : 'chargeManId',
		isGetDept : [true, "deptId", "deptName"],
		mode : 'single',
		event : {
			select : function(e, returnValue) {
				if (returnValue) {
					$('#applyCompanyCode').val(returnValue.companyCode)
					$('#applyCompanyName').val(returnValue.companyName)
				}
			}
		}
	});
	//办事处
	$("#agencyName").yxcombogrid_agency({
		hiddenId : 'agencyCode',
		gridOptions : {
			showcheckbox : false,
			event : {
				'row_dblclick' : function(e, row, data) {
					var rowNum = $("#borrowTable").yxeditgrid('getCurShowRowNum');
					if( typeof(rowNum)=='number' ){
						for( var i=0;i<rowNum;i++ ){
							$('#borrowTable_cmp_productName'+i).yxcombogrid_assetrequireitem('remove');
						}
					}
					$('#agencyCode').val(data.agencyCode);
					$("#borrowTable").yxeditgrid("remove");
					itemAddFun(data.agencyCode);
				}
			}
		}
	});
	// 选择使用部门组件
	$("#deptName").yxselect_dept({
		hiddenId : 'deptId'
	});
	// 选择申请人组件
	$("#reposeMan").yxselect_user({
		hiddenId : 'reposeManId',
		isGetDept : [true, "reposeDeptId", "reposeDept"],
		mode : 'single'
	});
	// 选择申请人组件
	$("#reposeDept").yxselect_dept({
		hiddenId : 'reposeDeptId'
	});

	/**
	 * 验证信息
	 */
	validate({
		"billNo" : {
			required : true
		},

//		"borrowCustome" : {
//			required : true
//		},
		"deptId" : {
			required : true
		},
		"deptName" : {
			required : true
		},
		"chargeManId" : {
			required : true
		},
		"chargeMan" : {
			required : true
		},
		"reposeDeptId" : {
			required : true
		},
		"reposeDept" : {
			required : true
		},
		"reposeManId" : {
			required : true
		},
		"reposeMan" : {
			required : true
		},
		"borrowDate" : {
			required : true
		},
		"predictDate" : {
			required : true
		},
		"agencyName" : {
			required : true
		}
	});
	// 下拉客户组件
	$("#borrowCustome").yxcombogrid_customer({
		hiddenId : 'borrowCustomeId',
		gridOptions : {
			showcheckbox : false,
			event : {
				'row_dblclick' : function(e, row, data) {
				}
			}
		}
	});

	$("#itemTable").yxeditgrid({
		url : '?model=asset_require_requireitem&action=listByRequireJson',
		objName : 'requirement[items]',
		type:'view',
		title : '借用设备清单',
		param : {
			mainId : $("#requireId").val()
		},
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
	if (confirm("你确定要提交审核吗?")) {
		$("#form1").attr("action",
				"?model=asset_daily_borrow&action=add&actType=audit");
		$("#form1").submit();
	} else {
		return false;
	}
}

/**
 *
 * 表单验证
 */
function checkForm(obj) {
	var s = plusDateInfo('borrowDate', 'predictDate');

	if (s <= 0) {
		alert("预计归还日期不能早于借用日期！"); // 时间验证
		$(obj).val('');
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
	var borrowTable = $("#borrowTable");
	//计算选择的卡片数量
	for(var i = 0;i < borrowTable.yxeditgrid("getAllAddRowNum");i++){
		itemId = borrowTable.yxeditgrid("getCmpByRowAndCol",i,"itemId").val();
		if(!countArr[itemId]){
			countArr[itemId] = 1;
		}else{
			countArr[itemId] = ++countArr[itemId];
		}
	}
	//验证选择的卡片数量是否在允许范围内，未发货数量=数量-已发货数量
	for (var i in countArr) { 
		if(itemIdArr[i] && itemIdArr[i]<countArr[i]){
			alert("选择的卡片数量超出借用设备清单未发货数量！");
			return false;
		}
	}
	var rowNum = borrowTable.yxeditgrid('getCurShowRowNum');
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
		g.getCmpByRowAndCol(rowNum, 'residueYears').val(rowData.estimateDay - rowData.alreadyDay);
	}
	$("#borrowTable").yxeditgrid({
		objName : 'borrow[borrowitem]',
		// url:'?model=asset_daily_borrowitem',
		title : '卡片信息',
		isAddOneRow : false,
		colModel : [{
			display : '设备清单Id',
			name : 'itemId',
			type : 'hidden'
		}, {
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
					hiddenId : 'borrowTable_cmp_productId' + rowNum,
					nameCol : 'productName',
					gridOptions : {
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
			process : function($input, rowData) {
				var rowNum = $input.data("rowNum");
				var g = $input.data("grid");
				$input.yxcombogrid_asset({
					hiddenId : 'borrowTable_cmp_assetId' + rowNum,
					nameCol : 'assetCode',
					gridOptions : {
						param : {
							'useStatusCode' : 'SYZT-XZ',
							'agencyCode' : agency,
							'machineCodeSearch':'0',
							'isDel' : '0',
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
					hiddenId : 'borrowTable_cmp_assetId' + rowNum,
					gridOptions : {
						param : {
							'useStatusCode' : 'SYZT-XZ',
							'agencyCode' : agency,
							'machineCodeSearch':'0',
							'isDel' : '0',
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
			// type:'date',
			readonly : true
		}, {
			display : '规格型号',
			name : 'spec',
			tclass : 'txtshort',
			readonly : true
		}, {
			display : '预计使用期间数',
			name : 'estimateDay',
			tclass : 'txtshort',
			readonly : true
		}, {
//			display : '已经使用期间数',
//			name : 'alreadyDay',
//			tclass : 'txtshort',
//			readonly : true
//		}, {
			display : '剩余使用期间数',
			name : 'residueYears',
			tclass : 'txtshort',
			readonly : true
		}, {
			display : '备注',
			name : 'remark',
			tclass : 'txt'
		}],
		event : {
//			'clickAddRow' : function(e, rowNum, g) {
//				g.removeRow(rowNum);
//				return false;
//			},
			beforeAddRow : function(e, rowNum, rowData, g) {
				if( $('#agencyCode').val()=='' ){
					alert('请选择责任区域!');
					g.removeRow(rowNum);
				}
			}
		}
	});
	//加载选择卡片信息按钮
	$("#borrowTable").find("tr:first td").append("<input type='button' value='选择卡片信息' class='txt_btn_a' style='margin-left:10px;' onclick='selectCard();'/>");
}
//选择卡片信息
function selectCard(){
	showOpenWin("?model=asset_assetcard_assetcard"
			+ "&action=selectCard&showType=borrow&agencyCode="
			+$("#agencyCode").val(),1,500,900);
}
//设置卡片内容
function setDatas(rows){
	var objGrid = $("#borrowTable");
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
		objGrid.yxeditgrid("setRowColValue",tbRowNum,"residueYears",rows[i].estimateDay-rows[i].alreadyDay);
	}
}