$(document)
		.ready(
				function() {

					/*
					 * validate({ "orderNum" : { required : true, custom :
					 * 'onlyNumber' } });
					 */$("#itemTable")
							.yxeditgrid(
									{
										objName : 'equipment[items]',
										url : '?model=stock_extra_equipmentpro&action=pageItemJson',
										param : {
											mainId : $("#id").val()
										},
										colModel : [
												{
													name : 'id',
													display : 'id',
													type : 'hidden'
												},
												{
													name : 'productId',
													display : '����id',
													sortable : true,
													type : 'hidden'
												},
												{
													name : 'productCode',
													display : '���ϱ��',
													sortable : true,
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
													display : '��������',
													sortable : true,
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

																				}
																			})
																					(rowNum)
																		}
																	}
																});
													}
												}, {
													name : 'pattern',
													display : '����ͺ�',
													sortable : true,
													tclass : 'readOnlyTxtItem',
													readonly : true
												}, {
													name : 'unitName',
													display : '��λ',
													sortable : true,
													tclass : 'readOnlyTxtItem',
													readonly : true
												} ]
									})
				})