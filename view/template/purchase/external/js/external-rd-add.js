$(function() {
			$("#projectName").yxcombogrid_rdProject({// �з���Ŀcombogrid
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
				// url : '?model=asset_purchase_apply_applyItem',
				isAddOneRow : true,
				colModel : [{
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
							$("#RDProductTable_cmp_productName" + rowNum).val("");
							$("#RDProductTable_cmp_productId" + rowNum).val("");
							$("#RDProductTable_cmp_productNumb" + rowNum).val("");
							
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
								$("#RDProductTable_cmp_pattem" + rowNum).attr(
										"readOnly", true);
								$("#RDProductTable_cmp_unitName" + rowNum)
										.attr("readOnly", true);
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
					display : '�豸id',
					name : 'productId',
					type : 'hidden'
				}, {
					display : '�豸����',
					name : 'productNumb'
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
					}, {
					display : '�豸����',
					name : 'productName',
					tclass : 'txt',
					validation : {
						required : true
					}
				}, {
					display : '����ͺ�',
					name : 'pattem'
//					validation : {
//						required : true
//					}

				}, {
					display : '��λ',
					name : 'unitName',
					tclass : 'txtshort',
					validation : {
						required : true
					}
				}, {
					display : '��Ӧ��',
					name : 'surpplierName',
					validation : {
						required : true
					}
				}, {
					display : '����',
					name : 'amountAll',
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
					name : 'equUseYear',
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
					name : 'planPrice',
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
			$('#isRd').change(function() {
						if ($("#isRd").val() == "1") {
							$("#hiddenA").hide();
						} else {
							$('#projectName').val("");
							$('#projectCode').val("");
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
						"phone" : {
							required : false,
							custom : ['onlyNumber']
						}
					});

		});

function checkForm() {// ����У��
	var itemscount = $("#RDProductTable").yxeditgrid("getCurRowNum");
	var deleteCount = 0;
	for (var i = 0; i < itemscount; i++) {
		if ($("#isDelTag" + i).val() == 1) {
			deleteCount = deleteCount + 1;
		}

	}
	if (deleteCount == itemscount) {
		alert("������������Ϣ...");
		return false;
	}
	
}
/*
 * ���ȷ��
 */
function confirmAudit() {
	if (confirm("��ȷ��Ҫ�ύ�����?")) {
		$("#form1")
				.attr("action",
						"index1.php?model=purchase_external_external&action=toSubmitAudit");
		$("#form1").submit();

	} else {
		return false;
	}
}