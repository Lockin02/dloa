$(function() {
	$("#RDProductTable").yxeditgrid({
		objName : 'apply[applyItem]',
		// url : '?model=asset_purchase_apply_applyItem',
		isAddOneRow : true,
		colModel : [{
					display : '设备id',
					name : 'productId',
					type : 'hidden'
				}, {
					display : '设备编码',
					name : 'productCode'
					// process : function($input, rowData) {
				// var rowNum = $input.data("rowNum");
				// var g = $input.data("grid");
				// $input.yxcombogrid_product({
				// hiddenId : 'RDProductTable_cmp_productId'
				// + rowNum,
				// nameCol : 'productCode',
				// width : 600,
				// gridOptions : {
				// event : {
				// row_dblclick : (function(rowNum) {
				// return function(e, row, rowData) {
				// g
				// .getCmpByRowAndCol(
				// rowNum,
				// 'productName')
				// .val(rowData.productName);
				// g
				// .getCmpByRowAndCol(
				// rowNum,
				// 'pattem')
				// .val(rowData.pattern);
				// g
				// .getCmpByRowAndCol(
				// rowNum,
				// 'unitName')
				// .val(rowData.unitName);
				// }
				// })(rowNum)
				// }
				// }
				// });
				// }
			}	, {
					display : '设备名称',
					name : 'productName',
					tclass : 'txt',
					validation : {
						required : true
					}
				}, {
					display : '规格型号',
					name : 'pattem',
					validation : {
						required : true
					}

				}, {
					display : '单位',
					name : 'unitName',
					tclass : 'txtshort',
					validation : {
						required : true
					}
				}, {
					display : '供应商',
					name : 'supplierName',
					validation : {
						required : true
					}
				}, {
					display : '数量',
					name : 'applyAmount',
					tclass : 'txtshort',
					validation : {
						custom : ['onlyNumber']
					}
				}, {
					display : '希望交货日期',
					name : 'dateHope',
					type : 'date',
					tclass : 'txtshort',
					validation : {
						custom : ['date']
					}
				}, {
					display : '设备使用年限',
					name : 'life',
					type : 'select',
					tclass : 'txtshort',
					options : [{
								name : "一年以上",
								value : 0
							}, {
								name : "一年以下",
								value : 1
							}]
				}, {
					display : '预计购入单价',
					name : 'exPrice',
					type : 'select',
					tclass : 'txtmiddle',
					options : [{
								name : "500元以上",
								value : 0
							}, {
								name : "500元以下",
								value : 1
							}]
				}, {
					display : '是否归属固定资产',
					name : 'isAsset',
					type : 'checkbox',
					value : "on",
					tclass : 'txtmin',
					event : {
						click : function() {
							var rowNum = $(this).data("rowNum");
							var g = $(this).data("grid");
							var rowData = $(this).data("rowData");

							if ($(this).attr("checked")) {
								$("#RDProductTable_cmp_productCode" + rowNum)
										.yxcombogrid_product("remove");
								$("#RDProductTable_cmp_productName" + rowNum)
										.yxcombogrid_product("remove");
							} else {
								$("#RDProductTable_cmp_productCode" + rowNum)
										.yxcombogrid_product({
											hiddenId : 'RDProductTable_cmp_productId'
													+ rowNum,
											nameCol : 'productCode',
											width : 600,
											gridOptions : {
												event : {
													'row_dblclick' : function(
															e, row, data) {
														g
																.getCmpByRowAndCol(
																		rowNum,
																		'productName')
																.val(data.productName);
														g
																.getCmpByRowAndCol(
																		rowNum,
																		'pattem')
																.val(data.pattern);
														g
																.getCmpByRowAndCol(
																		rowNum,
																		'unitName')
																.val(data.unitName);
													}
												}
											}
										});
								$("#RDProductTable_cmp_productName" + rowNum)
										.yxcombogrid_product({
											hiddenId : 'RDProductTable_cmp_productId'
													+ rowNum,
											nameCol : 'productName',
											width : 600,
											gridOptions : {
												event : {
													'row_dblclick' : function(
															e, row, data) {
														g
																.getCmpByRowAndCol(
																		rowNum,
																		'productCode')
																.val(data.productCode);
														g
																.getCmpByRowAndCol(
																		rowNum,
																		'pattem')
																.val(data.pattern);
														g
																.getCmpByRowAndCol(
																		rowNum,
																		'unitName')
																.val(data.unitName);
													}
												}
											}
										});
							}

						}
					}
				}, {
					display : '备注',
					name : 'remark',
					tclass : 'txt'
				}]
	});

	$("#applicantName").yxselect_user({
				hiddenId : 'applicantId',
				isGetDept : [true, "applyDetId", "applyDetName"]
			});

	// 根据是否属于研发专项设备来显示部分字段（研发专项项目名称、研发专项编号）

	$('#isrd').change(function() {
				if ($("#isrd").val() == "1") {
					$("#hiddenA").hide();
				} else {
					$('#rdProject').val("");
					$('#rdProjectCode').val("");
					$("#hiddenA").show();
				}
			});

	/**
	 * 验证信息
	 */
	validate({
				"userName" : {
					required : true
				},
				"applicantName" : {
					required : true
				},
				"applyTime" : {
					custom : ['date']
				},
				"userTel" : {
					required : false,
					custom : ['onlyNumber']
				}
			});

});

/*
 * 审核确认
 */
function confirmAudit() {
	if (confirm("你确定要提交审核吗?")) {
		$("#form1").attr("action",
				"?model=asset_purchase_apply_apply&action=add&actType=audit");
		$("#form1").submit();

	} else {
		return false;
	}
}