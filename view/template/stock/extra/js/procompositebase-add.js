$(document)
		.ready(
				function() {
					setPeriodSeNumOption();
					/*
					 * validate({ "orderNum" : { required : true, custom :
					 * 'onlyNumber' } });
					 */
					$("#itemTable")
							.yxeditgrid(
									{
										objName : 'procompositebase[items]',
										isAddOneRow : true,
										colModel : [
												{
													name : 'goodsId',
													display : '��Ʒid',
													sortable : true,
													type : 'hidden'
												},
												{
													name : 'goodsName',
													display : '�豸����',
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
													display : '�Ƿ�ͣ��',
													// type : 'select',
													type : 'hidden',
													tclass : 'readOnlyTxtItem',
													process : function(v, row) {
														if (v == "0") {
															return "�ڲ�";
														} else {
															return "ͣ��";
														}
													}
												// options : [ {
												// name : "�ڲ�",
												// value : '0'
												// }, {
												// name : "ͣ��",
												// value : '1'
												// } ]
												// process :
												// function($input,
												// rowData) {
												// var rowNum = $input
												// .data("rowNum");
												// var g = $input
												// .data("grid");
												// $input
												// .yxcombogrid_goods({
												// hiddenId :
												// 'itemTable_cmp_goodsId'
												// + rowNum,
												// nameCol : 'goodsName',
												// width : 600,
												// gridOptions : {}
												// })
												// }
												// },
												// {
												// name : 'productId',
												// display : '����id',
												// sortable : true,
												// type : 'hidden'
												// },
												// {
												// name : 'productCode',
												// display : '���ϱ��',
												// sortable : true,
												// process : function($input,
												// rowData) {
												// var rowNum = $input
												// .data("rowNum");
												// var g = $input
												// .data("grid");
												// $input
												// .yxcombogrid_product({
												// hiddenId :
												// 'itemTable_cmp_productId'
												// + rowNum,
												// nameCol : 'productCode',
												// width : 600,
												// gridOptions : {
												// event : {
												// row_dblclick : (function(
												// rowNum) {
												// return function(
												// e,
												// row,
												// rowData) {
												// g
												// .getCmpByRowAndCol(
												// rowNum,
												// 'productName')
												// .val(
												// rowData.productName);
												// g
												// .getCmpByRowAndCol(
												// rowNum,
												// 'pattern')
												// .val(
												// rowData.pattern);
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
												//
												// }
												// })
												// (rowNum)
												// }
												// }
												// });
												// }
												// },
												// {
												// name : 'productName',
												// display : '��������',
												// sortable : true,
												// tclass : 'txt',
												// process : function($input,
												// rowData) {
												// var rowNum = $input
												// .data("rowNum");
												// var g = $input
												// .data("grid");
												// $input
												// .yxcombogrid_product({
												// hiddenId :
												// 'itemTable_cmp_productId'
												// + rowNum,
												// nameCol : 'productName',
												// width : 600,
												// gridOptions : {
												// event : {
												// row_dblclick : (function(
												// rowNum) {
												// return function(
												// e,
												// row,
												// rowData) {
												// g
												// .getCmpByRowAndCol(
												// rowNum,
												// 'productCode')
												// .val(
												// rowData.productCode);
												// g
												// .getCmpByRowAndCol(
												// rowNum,
												// 'pattern')
												// .val(
												// rowData.pattern);
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
												// })
												// (rowNum)
												// }
												// }
												// });
												// }
												// }, {
												// name : 'pattern',
												// display : '����ͺ�',
												// sortable : true,
												// tclass : 'readOnlyTxtItem',
												// readonly : true

												// }, {
												// name : 'unitName',
												// display : '��λ',
												// sortable : true,
												// tclass : 'readOnlyTxtItem',
												// readonly : true

												}, {
													name : 'forecastSaleNum',
													display : '����Ԥ������',
													sortable : true

												}, {
													name : 'exeNum',
													display : '��ִͬ��������',
													sortable : true,
													tclass : 'txtshort',
													readonly : true
												}, {
													name : 'availableNum',
													display : '���',
													sortable : true,
													tclass : 'txtshort'
												}, {
													name : 'planPurchNum',
													display : '�ƻ���������',
													sortable : true
												}, {
													name : 'purchDays',
													display : '�ɹ�ʱ�䣨�죩',
													sortable : true
												}, {
													name : 'deliverDays',
													display : '�������ڣ��죩',
													sortable : true
												}, {
													name : 'remark',
													display : '��ע',
													sortable : true
												} ]
									})
				})

/**
 * �����������������
 */
function setPeriodSeNumOption() {
	$("#periodSeNum option").remove();
	var tempStr = "";
	if ($("#periodType").val() == "0") {

		tempStr = "<option value='1'>1�·��ϰ���</option>"
				+ "<option value='2'>1�·��°���</option>"
				+ "<option value='3'>2�·��ϰ���</option>"
				+ "<option value='4'>2�·��°���</option>"
				+ "<option value='5'>3�·��ϰ���</option>"
				+ "<option value='6'>3�·��°���</option>"
				+ "<option value='7'>4�·��ϰ���</option>"
				+ "<option value='8'>4�·��°���</option>"
				+ "<option value='9'>5�·��ϰ���</option>"
				+ "<option value='10'>5�·��°���</option>"
				+ "<option value='11'>6�·��ϰ���</option>"
				+ "<option value='12'>6�·��°���</option>"
				+ "<option value='13'>7�·��ϰ���</option>"
				+ "<option value='14'>7�·��°���</option>"
				+ "<option value='15'>8�·��ϰ���</option>"
				+ "<option value='16'>8�·��°���</option>"
				+ "<option value='17'>9�·��ϰ���</option>"
				+ "<option value='18'>9�·��°���</option>"
				+ "<option value='19'>10�·��ϰ���</option>"
				+ "<option value='20'>10�·��°���</option>"
				+ "<option value='21'>11�·��ϰ���</option>"
				+ "<option value='22'>11�·��°���</option>"
				+ "<option value='23'>12�·��ϰ���</option>"
				+ "<option value='24'>12�·��°���</option>";
	} else if ($("#periodType").val() == "1") {
		tempStr = "<option value='1'>1�·�</option>"
				+ "<option value='2'>2�·�</option>"
				+ "<option value='3'>3�·�</option>"
				+ "<option value='4'>4�·�</option>"
				+ "<option value='5'>5�·�</option>"
				+ "<option value='6'>6�·�</option>"
				+ "<option value='7'>7�·�</option>"
				+ "<option value='8'>8�·�</option>"
				+ "<option value='9'>9�·�</option>"
				+ "<option value='10'>10�·�</option>"
				+ "<option value='11'>11�·�</option>"
				+ "<option value='12'>12�·�</option>";
	} else if ($("#periodType").val() == "2") {
		tempStr = "<option value='1'>��1����</option>"
				+ "<option value='2'>��2����</option>"
				+ "<option value='3'>��3����</option>"
				+ "<option value='4'>��4����</option>";
	}

	$("#periodSeNum").append(tempStr);
	// '
	// <option value="1">1</option>
	// <option value="1">1</option>
	// <option value="1">1</option>
	// <option value="1">1</option>
	// <option value="1">1</option>
	// <option value="1">1</option>
	//			
	// <option value="1">1</option>';
}

/**
 * �豸�������Ͽ���Ʒ�ֵĿ����Ϣ
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
 * ��ȡ���ۺ�ͬ�豸�������ϵ�ִ��������
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