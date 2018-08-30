$(document).ready(function() {
	validate({

				"propertiesName" : {
					required : true

				},
				"orderNum" : {
					required : true,
					custom : ['onlyNumber']
				}
			});
	// ���ò�Ʒid
	$("#mainId").val(parent.parent.window.$("#goodsId").val());
	// ����������Ϣ ѡ������
	$("#parentName").yxcombotree({
		hiddenId : 'parentId',
		treeOptions : {
			event : {
				"node_click" : function(event, treeId, treeNode) {
					// $("#arrivalPeriod").val(treeNode.submitDay);
				},
				"node_change" : function(event, treeId, treeNode) {
				}
			},
			url : "?model=goods_goods_properties&action=getTreeData&goodsId="
					+ $("#mainId").val()
		}
	});
	$("#itemTable").yxeditgrid({
		objName : 'properties[items]',
		// url : '?model=asset_purchase_apply_applyItem',
		isAddOneRow : true,
		colModel : [{
					name : 'itemContent',
					tclass : 'txt',
					display : 'ֵ����',
					sortable : true,
					validation : {
						required : true
					}
				}, {
					name : 'isNeed',
					display : '�Ƿ��ѡ',
					type : 'checkbox',
					tclass : 'txtmin',
					sortable : true
				}, {
					name : 'isDefault',
					display : '�Ƿ�Ĭ��',
					type : 'checkbox',
					tclass : 'txtmin',
					sortable : true
				}, {
					name : 'defaultNum',
					display : '����',
					tclass : 'txtmin',
					sortable : true
				}, {
					name : 'productId',
					display : '��Ʒid',
					type : "hidden",
					sortable : true
				}, {
					name : 'productCode',
					display : '��Ӧ���ϱ��',
					sortable : true,
					process : function($input, rowData) {
						var rowNum = $input.data("rowNum");
						var g = $input.data("grid");
						$input.yxcombogrid_product({
									hiddenId : 'itemTable_cmp_productId'
											+ rowNum,
									nameCol : 'productCode',
									width : 600,
									gridOptions : {
										event : {
											row_dblclick : (function(rowNum) {
												return function(e, row, rowData) {
				                                    g.getCmpByRowAndCol(rowNum, 'productName').val(rowData.productName);
				                                    g.getCmpByRowAndCol(rowNum, 'pattern').val(rowData.pattern);
				                                    g.getCmpByRowAndCol(rowNum, 'itemContent').val(rowData.productName)
				                                    .attr('readonly','readonly').removeClass('txt').addClass('readOnlyTxtNormal');
												}
											})(rowNum)
										}
									},
				                    event : {
				                        'clear' : function() {
				                            g.getCmpByRowAndCol(rowNum, 'productName').val('');
				                            g.getCmpByRowAndCol(rowNum, 'pattern').val('');
				                            g.getCmpByRowAndCol(rowNum, 'itemContent').val('')
				                            .attr('readonly',false).removeClass('readOnlyTxtNormal').addClass('txt');
				                        }
				                    }
								});
					}
				}, {
					name : 'productName',
					display : '��Ӧ��������',
					tclass : 'txt',
					process : function($input, rowData) {
						var rowNum = $input.data("rowNum");
						var g = $input.data("grid");
						$input.yxcombogrid_product({
									hiddenId : 'itemTable_cmp_productId'
											+ rowNum,
									nameCol : 'productName',
									width : 600,
									gridOptions : {
										event : {
											row_dblclick : (function(rowNum) {
												return function(e, row, rowData) {
				                                    g.getCmpByRowAndCol(rowNum, 'productCode').val(rowData.productCode);
				                                    g.getCmpByRowAndCol(rowNum, 'pattern').val(rowData.pattern);
				                                    g.getCmpByRowAndCol(rowNum, 'itemContent').val(rowData.productName)
				                                    .attr('readonly','readonly').removeClass('txt').addClass('readOnlyTxtNormal');
												}
											})(rowNum)
										}
									},
				                    event : {
				                        'clear' : function() {
				                            g.getCmpByRowAndCol(rowNum, 'productCode').val('');
				                            g.getCmpByRowAndCol(rowNum, 'pattern').val('');
				                            g.getCmpByRowAndCol(rowNum, 'itemContent').val('')
				                            .attr('readonly',false).removeClass('readOnlyTxtNormal').addClass('txt');
				                        }
				                    }
								});
					},
					sortable : true
				}, {
					name : 'pattern',
					display : '��Ӧ�����ͺ�',
					sortable : true,
					tclass : 'readOnlyTxtItem'
				}, {
					name : 'proNum',
					display : '��Ӧ��������',
					tclass : 'txtmin',
					sortable : true
				}, {
					name : 'status',
					display : '״̬',
					type : 'select',
					tclass : 'txtshort',
					options : [{
								name : "�ڲ�",
								value : 'ZC'
							}, {
								name : "ͣ��",
								value : 'TC'
							}],
					sortable : true
				}, {
					name : 'licenseTypeName',
					display : 'license����',
					type : 'hidden'
				}, {
					name : 'licenseTypeCode',
					display : 'license����',
					type : 'select',
					options : [{
								value : "",
								name : "��license"
							}, {
								value : "PIO",
								name : "Pioneer"
							}, {
								value : "NAV",
								name : "Navigator"
							}, {
								value : "Pioneer-Navigator",
								name : "Pioneer-Navigator"
							}, {
								value : "WT",
								name : "Walktour"
							}, {
								value : "Walktour Pack-Ipad",
								name : "Walktour Pack-Ipad"
							}, {
								value : "FL2",
								name : "Fleet"
							}],
					event : {
						change : function(e) {
							var rowNum = e.data.rowNum;
							var g = e.data.gird;
							var $cmp = g.getCmpByRowAndCol(rowNum,
									'licenseTypeName');
							var name = $(this).find("option:selected").text();
							$cmp.val(name);
							$.ajax({
								type : "POST",
								url : "?model=yxlicense_license_template&action=getTemplateByType",
								data : {
									'licenseType' : $(this).val()
								},
								async : false,
								success : function(data) {
									var $cmp = g.getCmpByRowAndCol(rowNum,
											'licenseTemplateId');
									$cmp.children().remove();
									$cmp
											.append("<option value=''>��ѡ��</option>");
									dataRows = eval('(' + data + ')');
									for (var i = 0, l = dataRows.length; i < l; i++) {
										$cmp.append("<option  value='"
												+ dataRows[i].thisVal + "'>"
												+ dataRows[i].name
												+ "</option>");
									}
								}
							});
						}
					}
				}, {
					name : 'licenseTemplateId',
					display : 'licenseģ��',
					type : 'select',
					options : []
				}, {
					name : 'rkey',
					display : '������ʶ',
					type : "hidden"
				}, {
					name : 'remark',
					display : '����',
					type : "hidden"
				}, {
					name : 'staticRemark',
					display : '����������ť',
					type : 'statictext',
					event : {
						'click' : function(e) {
							var rowNum = $(this).data("rowNum");
							var g = $(this).data("grid");
							var rowData = $(this).data("rowData");
							window
									.open(
											"?model=goods_goods_properties&action=toEditRemark&rowNum="
													+ rowNum
													+ "&remark="
													+ $("#itemTable_cmp_remark"
															+ rowNum).val()
													+ "&rkey="
													+ $("#itemTable_cmp_rkey"
															+ rowNum).val(),
											'������Ϣ�༭',
											'height=500, width=650, top=200, left=200, toolbar=no, menubar=no, scrollbars=no, resizable=no,location=n o, status=no');
						}
					},
					html : '<input type="button"  value="�༭"  class="txt_btn_a"  />'
				}, {
					name : 'assitem',
					display : '���������',
					type : "hidden"
				}, {
					name : 'assitemIdStr',
					display : '������Id����',
					type : "hidden"
				}, {
					name : 'assitemTipStr',
					display : '������Tip����',
					type : "hidden"
				}, {
					name : 'staticAssitem',
					display : '���������',
					type : 'statictext',
					event : {
						'click' : function(e) {
							var rowNum = $(this).data("rowNum");
							var g = $(this).data("grid");
							var rowData = $(this).data("rowData");
							window
									.open(
											"?model=goods_goods_properties&action=toSetAssItem&goodsId="
													+ $("#mainId").val()
													+ "&orderNum="
													+ $("#orderNum").val()
													+ "&assitem="
													+ $("#itemTable_cmp_assitem")
															.val()
													+ "&rowNum="
													+ rowNum
													+ "&assItemIdStr="
													+ $("#itemTable_cmp_assitemIdStr"
															+ rowNum).val()
													+ "&assitemTipStr="
													+ $("#itemTable_cmp_assitemTipStr"
															+ rowNum).val(),
											'���������',
											'height=500, width=650, top=200, left=200, toolbar=no, menubar=no, scrollbars=no, resizable=no,location=n o, status=no');
						}
					},
					html : '<input type="button"  value="����"  class="txt_btn_a"  />'

				}]
	})
})
