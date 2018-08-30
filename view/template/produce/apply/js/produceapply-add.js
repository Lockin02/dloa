$(document)
		.ready(
				// $("#saleUserName").yxselect_user({
				// hiddenId : 'saleUserCode'
				// });

				function() {
					$("#saleUserName").yxselect_user({
						hiddenId : 'saleUserCode'
					})
					/*
					 * validate({ "orderNum" : { required : true, custom :
					 * 'onlyNumber' } });
					 */
					$("#itemTable")
							.yxeditgrid(
									{
										objName : 'produceapply[items]',
										isAddOneRow : true,
										colModel : [
												{
													name : 'productCode',
													display : '物料编码',
													process : function($input,
															rowData) {
														var rowNum = $input
																.data("rowNum");
														var g = $input
																.data("grid");
														$input
																.yxcombogrid_product({
																	hiddenId : 'itemTable_cmp_productId'
																			+ rowNum,
																	nameCol : 'productCode',
																	width : 600,
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
																									'productName')
																							.val(
																									rowData.productName);
																					g
																							.getCmpByRowAndCol(
																									rowNum,
																									'pattern')
																							.val(
																									rowData.pattern);
																					g
																							.getCmpByRowAndCol(
																									rowNum,
																									'unitName')
																							.val(
																									rowData.unitName);
																				}
																			})
																					(rowNum)
																		}
																	}
																});
													}
												},
												{
													name : 'productName',
													display : '物料名称',
													tclass : 'txt',
													process : function($input,
															rowData) {
														var rowNum = $input
																.data("rowNum");
														var g = $input
																.data("grid");
														$input
																.yxcombogrid_product({
																	hiddenId : 'itemTable_cmp_productId'
																			+ rowNum,
																	nameCol : 'productName',
																	width : 600,
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
																									'productCode')
																							.val(
																									rowData.productCode);
																					g
																							.getCmpByRowAndCol(
																									rowNum,
																									'pattern')
																							.val(
																									rowData.pattern);
																					g
																							.getCmpByRowAndCol(
																									rowNum,
																									'unitName')
																							.val(
																									rowData.unitName);
																				}
																			})
																					(rowNum)
																		}
																	}
																});
													}
												}, {
													name : 'pattern',
													display : '规格型号',
													tclass : 'readOnlyTxtItem',
													readonly : true
												}, {
													name : 'unitName',
													display : '单位',
													tclass : 'readOnlyTxtItem',
													readonly : true
												// }, {
												// name : 'fittings',
												// display : '配置',
												// sortable : true
												}, {
													name : 'produceNum',
													display : '数量',
													tclass : 'txtmin'
												}, {
													name : 'planEndDate',
													display : '计划交货时间',
													type : 'date'
												}, {
													name : 'remark',
													display : '备注',
													tclass : 'txt'
												} ]
									})
				})