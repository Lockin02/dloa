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
													display : '���ϱ���',
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
													display : '��������',
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
													display : '����ͺ�',
													tclass : 'readOnlyTxtItem',
													readonly : true
												}, {
													name : 'unitName',
													display : '��λ',
													tclass : 'readOnlyTxtItem',
													readonly : true
												// }, {
												// name : 'fittings',
												// display : '����',
												// sortable : true
												}, {
													name : 'produceNum',
													display : '����',
													tclass : 'txtmin'
												}, {
													name : 'planEndDate',
													display : '�ƻ�����ʱ��',
													type : 'date'
												}, {
													name : 'remark',
													display : '��ע',
													tclass : 'txt'
												} ]
									})
				})