$(function() {
	$("#projectName").yxcombogrid_rdProject({// 研发项目combogrid
		hiddenId : 'projectId',
		width : 600,
		gridOptions : {
			showcheckbox : false,
			event : {
				'row_dblclick' : function(e, row, data) {
					$("#projectCode").val(data.projectCode);
				}
			}
		}
	});

	$("#RDProductTable").yxeditgrid({
		objName : 'basic[equipment]',
		// delTagName : 'isDelTag',
		url : '?model=purchase_plan_equipment&action=pageItemJson',
		param : {
			basicId : $("#id").val()
		},
		event : {
			reloadData : function(event, g) {
				var rowCount = g.getCurRowNum();
				for (var rowNum = 0; rowNum < rowCount; rowNum++) {
					if (!$("#RDProductTable_cmp_isAsset" + rowNum)
							.attr("checked")) {
						$("#RDProductTable_cmp_productNumb" + rowNum)
								.yxcombogrid_product({
									hiddenId : 'RDProductTable_cmp_productId'
											+ rowNum,
									nameCol : 'productCode',
									width : 600,
									gridOptions : {
										event : {
											'row_dblclick' : function(e, row,
													data) {
												g.getCmpByRowAndCol(rowNum,
														'productName')
														.val(data.productName);
												g.getCmpByRowAndCol(rowNum,
														'pattem')
														.val(data.pattern);
												g.getCmpByRowAndCol(rowNum,
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
											'row_dblclick' : function(e, row,
													data) {
												g.getCmpByRowAndCol(rowNum,
														'productNumb')
														.val(data.productCode);
												g.getCmpByRowAndCol(rowNum,
														'pattem')
														.val(data.pattern);
												g.getCmpByRowAndCol(rowNum,
														'unitName')
														.val(data.unitName);
											}
										}
									}
								});
					}

				}

			}
		},
		colModel : [{
					display : 'id',
					name : 'id',
					type : 'hidden'
				}, {
					display : '是否归属固定资产',
					name : 'isAsset',
					type : 'checkbox',
					tclass : 'txtmin',
					event : {
						click : function() {
							var rowNum = $(this).data("rowNum");
							var g = $(this).data("grid");
							var rowData = $(this).data("rowData");
							$("#RDProductTable_cmp_productName" + rowNum)
									.val("");
							$("#RDProductTable_cmp_productId" + rowNum).val("");
							$("#RDProductTable_cmp_productNumb" + rowNum)
									.val("");
							if ($(this).attr("checked")) {
								$("#RDProductTable_cmp_productNumb" + rowNum)
										.yxcombogrid_product("remove");
								$("#RDProductTable_cmp_productNumb" + rowNum)
										.attr("readOnly", false);
								$("#RDProductTable_cmp_productName" + rowNum)
										.yxcombogrid_product("remove");
								$("#RDProductTable_cmp_productName" + rowNum)
										.attr("readOnly", false);
								$("#RDProductTable_cmp_pattem" + rowNum).attr(
										"readOnly", false);
								$("#RDProductTable_cmp_unitName" + rowNum)
										.attr("readOnly", false);
							} else {
								$("#RDProductTable_cmp_productNumb" + rowNum)
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
																		'productNumb')
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
					display : 'productId',
					name : 'productId',
					type : 'hidden'
				}, {
					display : '设备编码',
					name : 'productNumb',
					validation : {
						required : true
					}
				}, {
					display : '设备名称',
					name : 'productName',
					tclass : 'txt',
					validation : {
						required : true
					}
				}, {
					display : '规格型号',
					name : 'pattem'
					// validation : {
				// required : true
				// }
			}	, {
					display : '供应商',
					name : 'surpplierName',
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
					display : '数量',
					name : 'amountAll',
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
					name : 'equUseYear',
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
					name : 'planPrice',
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
					display : '备注',
					name : 'remark',
					tclass : 'txt'
				}]
	})

	$("#applicantName").yxselect_user({
				hiddenId : 'applicantId',
				isGetDept : [true, "applyDetId", "applyDetName"]
			});

	// 根据是否属于研发专项设备来显示部分字段（研发专项项目名称、研发专项编号）
	$('#isRd').change(function() {
				if ($("#isRd").val() == "1") {
					$("#hiddenA").hide();
				} else {
					$('#projectName').val("");
					$('#projectCode').val("");
					$("#hiddenA").show();
				}
			});

	function checkForm() {// 表单校验
		var itemscount = $("#RDProductTable").yxeditgrid("getCurRowNum");
		var deleteCount = 0;
		for (var i = 0; i < itemscount; i++) {
			if ($("#isDelTag" + i).val() == 1) {
				deleteCount = deleteCount + 1;
			}

		}
		if (deleteCount == itemscount) {
			alert("请新增物料信息...");
			return false;
		}
	}
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
function checkForm() {// 表单校验
	var itemscount = $("#RDProductTable").yxeditgrid("getCurRowNum");
	var deleteCount = 0;
	for (var i = 0; i < itemscount; i++) {
		if ($("#isDelTag" + i).val() == 1) {
			deleteCount = deleteCount + 1;
		}

	}
	if (deleteCount == itemscount) {
		alert("请新增物料信息...");
		return false;
	}
}
/*
 * 审核确认
 */
function confirmAudit() {
	if (confirm("你确定要提交审核吗?")) {
		$("#form1")
				.attr("action",
						"index1.php?model=purchase_external_external&action=toSubmitAuditByEdit");
		$("#form1").submit();

	} else {
		return false;
	}
}