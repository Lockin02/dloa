$(document)
		.ready(
				function() {

					// $("#periodSeNum").val($("#periodSeNumHidden").val());
					// alert($("#periodTypeHidden").val())
					// $("#periodType").val($("#periodTypeHidden").val());
					/*
					 * validate({ "orderNum" : { required : true, custom :
					 * 'onlyNumber' } });
					 */
					$("#itemTable")
							.yxeditgrid(
									{
										objName : 'procompositebase[items]',
										url : '?model=stock_extra_procompositebaseitem&action=pageItemJson',
										param : {
											mainId : $("#id").val()
										},
										colModel : [
												{
													name : 'id',
													display : 'id',
													sortable : true,
													type : 'hidden'
												},
												{
													name : 'goodsId',
													display : '产品id',
													sortable : true,
													type : 'hidden'
												},
												{
													name : 'goodsName',
													display : '设备名称',
													tclass : 'txt',
													process : function($input,
															rowData) {
														var rowNum = $input
																.data("rowNum");
														var g = $input
																.data("grid");
														$input
																.yxcombogrid_extraequip({
																	hiddenId : 'itemTable_cmp_goodsId'
																			+ rowNum,
																	// nameCol :
																	// 'goodsName',
																	width : 300,
																	gridOptions : {
																		event : {
																			row_dblclick : (function(
																					rowNum) {
																				return function(
																						e,
																						row,
																						rowData) {
																					g
																							.getCmpByRowAndCol(
																									rowNum,
																									'isProduce')
																							.val(
																									rowData.isProduce);
																					g
																							.getCmpByRowAndCol(
																									rowNum,
																									'exeNum')
																							.val(
																									getConEquNum(rowData.id));
																					g
																							.getCmpByRowAndCol(
																									rowNum,
																									'availableNum')
																							.val(
																									getProActNum(rowData.id));

																				}
																			})
																					(rowNum)
																		}
																	}
																})
													}
												}, {
													name : 'isProduce',
													display : '是否停产',
													type : 'hidden',
													// type : 'select',
													tclass : 'readOnlyTxtItem'
												// process : function(v, row) {
												// if (v == "0") {
												// return "在产";
												// } else {
												// return "停产";
												// }
												// }
												// }, {
												// name : 'productId',
												// display : '物料id',
												// sortable : true,
												// type : 'hidden'
												// }, {
												// name : 'productCode',
												// display : '物料编号',
												// sortable : true,
												// process : function($input,
												// rowData) {
												// var rowNum =
												// $input.data("rowNum");
												// var g = $input.data("grid");
												// $input.yxcombogrid_product({
												// hiddenId :
												// 'itemTable_cmp_productId'
												// + rowNum,
												// nameCol : 'productCode',
												// width : 600,
												// gridOptions : {
												// event : {
												// row_dblclick :
												// (function(rowNum) {
												// return function(e, row,
												// rowData) {
												// g
												// .getCmpByRowAndCol(
												// rowNum,
												// 'productName')
												// .val(rowData.productName);
												// g
												// .getCmpByRowAndCol(
												// rowNum,
												// 'pattern')
												// .val(rowData.pattern);
												// g
												// .getCmpByRowAndCol(
												// rowNum,
												// 'purchDays')
												// .val(
												// rowData.purchPeriod);
												// g
												// .getCmpByRowAndCol(
												// rowNum,
												// 'actNum')
												// .val(
												// getProActNum(rowData.id));
												// g
												// .getCmpByRowAndCol(
												// rowNum,
												// 'availableNum')
												// .val(
												// getProActNum(rowData.id));
												// }
												// })(rowNum)
												// }
												// }
												// });
												// }
												// }, {
												// name : 'productName',
												// display : '物料名称',
												// sortable : true,
												// tclass : 'txt',
												// process : function($input,
												// rowData) {
												// var rowNum =
												// $input.data("rowNum");
												// var g = $input.data("grid");
												// $input.yxcombogrid_product({
												// hiddenId :
												// 'itemTable_cmp_productId'
												// + rowNum,
												// nameCol : 'productName',
												// width : 600,
												// gridOptions : {
												// event : {
												// row_dblclick :
												// (function(rowNum) {
												// return function(e, row,
												// rowData) {
												// g
												// .getCmpByRowAndCol(
												// rowNum,
												// 'productCode')
												// .val(rowData.productCode);
												// g
												// .getCmpByRowAndCol(
												// rowNum,
												// 'pattern')
												// .val(rowData.pattern);
												// g
												// .getCmpByRowAndCol(
												// rowNum,
												// 'purchDays')
												// .val(
												// rowData.purchPeriod);
												// g
												// .getCmpByRowAndCol(
												// rowNum,
												// 'actNum')
												// .val(
												// getProActNum(rowData.id));
												// g
												// .getCmpByRowAndCol(
												// rowNum,
												// 'availableNum')
												// .val(
												// getProActNum(rowData.id));
												// }
												// })(rowNum)
												// }
												// }
												// });
												// }
												// }, {
												// name : 'pattern',
												// display : '规格型号',
												// sortable : true,
												// tclass : 'readOnlyTxtItem',
												// readonly : true
												// }, {
												// name : 'unitName',
												// display : '单位',
												// sortable : true,
												// tclass : 'readOnlyTxtItem',
												// readonly : true

												}, {
													name : 'forecastSaleNum',
													display : '销售预测数量',
													tclass : 'txtshort',
													sortable : true
												}, {
													name : 'exeNum',
													display : '合同执行中数量',
													tclass : 'txtshort'
												// ,
												// process : function(input,
												// row, $tr, g) {
												// if (row) {
												// input
												// .val(getConEquNum(row.goodsId));
												// }
												//
												// }
												}, {
													name : 'availableNum',
													display : '库存',
													tclass : 'txtshort'
												// ,
												// process : function(input,
												// row) {
												// if (row) {
												// input
												// .val(getProActNum(row.goodsId));
												// }
												//
												// }
												}, {
													name : 'planPurchNum',
													display : '计划备货数量',
													tclass : 'txtshort',
													sortable : true
												}, {
													name : 'purchDays',
													display : '采购时间（天）',
													tclass : 'txtshort',
													sortable : true
												}, {
													name : 'deliverDays',
													display : '交货周期（天）',
													sortable : true
												}, {
													name : 'remark',
													display : '备注',
													sortable : true
												} ]
									})
					// alert($("#itemTable")
					// .yxeditgrid("getRowNum"))
					// for ( var i = 0; i < 2; i++) {
					// //
					// alert(getProActNum($("#itemTable_cmp_productId"+i).val()))
					// // alert($("#itemTable_cmp_productId" + i).val())
					// $("#itemTable_cmp_availableNum" + i).val(
					// getProActNum($("#itemTable_cmp_goodsId" + i)
					// .val()));
					// $("#itemTable_cmp_actNum" + i).val(
					// getConEquNum($("#itemTable_cmp_goodsId" + i)
					// .val()));
					// }
				})

/**
 * 控制周期序次下拉框
 */
function setPeriodSeNumOption() {
	$("#periodSeNum option").remove();
	var tempStr = "";
	if ($("#periodType").val() == "0") {

		tempStr = "<option value='1'>1月份上半月</option>"
				+ "<option value='2'>1月份下半月</option>"
				+ "<option value='3'>2月份上半月</option>"
				+ "<option value='4'>2月份下半月</option>"
				+ "<option value='5'>3月份上半月</option>"
				+ "<option value='6'>3月份下半月</option>"
				+ "<option value='7'>4月份上半月</option>"
				+ "<option value='8'>4月份下半月</option>"
				+ "<option value='9'>5月份上半月</option>"
				+ "<option value='10'>5月份下半月</option>"
				+ "<option value='11'>6月份上半月</option>"
				+ "<option value='12'>6月份下半月</option>"
				+ "<option value='13'>7月份上半月</option>"
				+ "<option value='14'>7月份下半月</option>"
				+ "<option value='15'>8月份上半月</option>"
				+ "<option value='16'>8月份下半月</option>"
				+ "<option value='17'>9月份上半月</option>"
				+ "<option value='18'>9月份下半月</option>"
				+ "<option value='19'>10月份上半月</option>"
				+ "<option value='20'>10月份下半月</option>"
				+ "<option value='21'>11月份上半月</option>"
				+ "<option value='22'>11月份下半月</option>"
				+ "<option value='23'>12月份上半月</option>"
				+ "<option value='24'>12月份下半月</option>";
	} else if ($("#periodType").val() == "1") {
		tempStr = "<option value='1'>1月份</option>"
				+ "<option value='2'>2月份</option>"
				+ "<option value='3'>3月份</option>"
				+ "<option value='4'>4月份</option>"
				+ "<option value='5'>5月份</option>"
				+ "<option value='6'>6月份</option>"
				+ "<option value='7'>7月份</option>"
				+ "<option value='8'>8月份</option>"
				+ "<option value='9'>9月份</option>"
				+ "<option value='10'>10月份</option>"
				+ "<option value='11'>11月份</option>"
				+ "<option value='12'>12月份</option>";
	} else if ($("#periodType").val() == "2") {
		tempStr = "<option value='1'>第1季度</option>"
				+ "<option value='2'>第2季度</option>"
				+ "<option value='3'>第3季度</option>"
				+ "<option value='4'>第4季度</option>";
	}

	$("#periodSeNum").append(tempStr);
}
// /**
// * 获取物料即时库存
// *
// * @param productId
// */
// function getProActNum(productId) {
// var actNum = 0;
// $.ajax({
// type : "POST",
// async : false,
// url : "?model=stock_inventoryinfo_inventoryinfo&action=getProActNum",
// data : {
// productId : productId
// },
// success : function(result) {
// actNum = result;
// }
// })
// return actNum;
// }

/**
 * 设备附属物料库商品仓的库存信息
 * 
 * @param productId
 */
function getProActNum(equId) {
	var actNum = 0;
	$.ajax({
		type : "POST",
		async : false,
		url : "?model=stock_extra_procompositebase&action=getProActNum",
		data : {
			id : equId
		},
		success : function(result) {
			actNum = result;
		}
	})
	return actNum;
}

/**
 * 获取销售合同设备附属物料的执行中数量
 */
function getConEquNum(equId) {
	var actNum = 0;
	$.ajax({
		type : "POST",
		async : false,
		url : "?model=stock_extra_procompositebase&action=getConEquNum",
		data : {
			id : equId
		},
		success : function(result) {
			actNum = result;
		}
	})
	return actNum;
}