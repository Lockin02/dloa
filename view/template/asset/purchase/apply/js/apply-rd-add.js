$(function() {
	$("#RDProductTable").yxeditgrid({
		objName : 'apply[applyItem]',
		// url : '?model=asset_purchase_apply_applyItem',
		isAddOneRow : true,
		colModel : [{
					display : '�豸id',
					name : 'productId',
					type : 'hidden'
				}, {
					display : '�豸����',
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
					display : '�豸����',
					name : 'productName',
					tclass : 'txt',
					validation : {
						required : true
					}
				}, {
					display : '����ͺ�',
					name : 'pattem',
					validation : {
						required : true
					}

				}, {
					display : '��λ',
					name : 'unitName',
					tclass : 'txtshort',
					validation : {
						required : true
					}
				}, {
					display : '��Ӧ��',
					name : 'supplierName',
					validation : {
						required : true
					}
				}, {
					display : '����',
					name : 'applyAmount',
					tclass : 'txtshort',
					validation : {
						custom : ['onlyNumber']
					}
				}, {
					display : 'ϣ����������',
					name : 'dateHope',
					type : 'date',
					tclass : 'txtshort',
					validation : {
						custom : ['date']
					}
				}, {
					display : '�豸ʹ������',
					name : 'life',
					type : 'select',
					tclass : 'txtshort',
					options : [{
								name : "һ������",
								value : 0
							}, {
								name : "һ������",
								value : 1
							}]
				}, {
					display : 'Ԥ�ƹ��뵥��',
					name : 'exPrice',
					type : 'select',
					tclass : 'txtmiddle',
					options : [{
								name : "500Ԫ����",
								value : 0
							}, {
								name : "500Ԫ����",
								value : 1
							}]
				}, {
					display : '�Ƿ�����̶��ʲ�',
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
					display : '��ע',
					name : 'remark',
					tclass : 'txt'
				}]
	});

	$("#applicantName").yxselect_user({
				hiddenId : 'applicantId',
				isGetDept : [true, "applyDetId", "applyDetName"]
			});

	// �����Ƿ������з�ר���豸����ʾ�����ֶΣ��з�ר����Ŀ���ơ��з�ר���ţ�

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
	 * ��֤��Ϣ
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
 * ���ȷ��
 */
function confirmAudit() {
	if (confirm("��ȷ��Ҫ�ύ�����?")) {
		$("#form1").attr("action",
				"?model=asset_purchase_apply_apply&action=add&actType=audit");
		$("#form1").submit();

	} else {
		return false;
	}
}