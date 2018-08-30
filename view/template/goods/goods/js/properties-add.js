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
	// 设置产品id
	$("#mainId").val(parent.parent.window.$("#goodsId").val());
	// 新增分类信息 选择类型
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
					display : '值内容',
					sortable : true,
					validation : {
						required : true
					}
				}, {
					name : 'isNeed',
					display : '是否必选',
					type : 'checkbox',
					tclass : 'txtmin',
					sortable : true
				}, {
					name : 'isDefault',
					display : '是否默认',
					type : 'checkbox',
					tclass : 'txtmin',
					sortable : true
				}, {
					name : 'defaultNum',
					display : '数量',
					tclass : 'txtmin',
					sortable : true
				}, {
					name : 'productId',
					display : '产品id',
					type : "hidden",
					sortable : true
				}, {
					name : 'productCode',
					display : '对应物料编号',
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
					display : '对应物料名称',
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
					display : '对应物料型号',
					sortable : true,
					tclass : 'readOnlyTxtItem'
				}, {
					name : 'proNum',
					display : '对应物料数量',
					tclass : 'txtmin',
					sortable : true
				}, {
					name : 'status',
					display : '状态',
					type : 'select',
					tclass : 'txtshort',
					options : [{
								name : "在产",
								value : 'ZC'
							}, {
								name : "停产",
								value : 'TC'
							}],
					sortable : true
				}, {
					name : 'licenseTypeName',
					display : 'license类型',
					type : 'hidden'
				}, {
					name : 'licenseTypeCode',
					display : 'license类型',
					type : 'select',
					options : [{
								value : "",
								name : "无license"
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
											.append("<option value=''>请选择</option>");
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
					display : 'license模板',
					type : 'select',
					options : []
				}, {
					name : 'rkey',
					display : '描述标识',
					type : "hidden"
				}, {
					name : 'remark',
					display : '描述',
					type : "hidden"
				}, {
					name : 'staticRemark',
					display : '具体描述按钮',
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
											'描述信息编辑',
											'height=500, width=650, top=200, left=200, toolbar=no, menubar=no, scrollbars=no, resizable=no,location=n o, status=no');
						}
					},
					html : '<input type="button"  value="编辑"  class="txt_btn_a"  />'
				}, {
					name : 'assitem',
					display : '数据项关联',
					type : "hidden"
				}, {
					name : 'assitemIdStr',
					display : '数据项Id关联',
					type : "hidden"
				}, {
					name : 'assitemTipStr',
					display : '数据项Tip关联',
					type : "hidden"
				}, {
					name : 'staticAssitem',
					display : '数据项关联',
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
											'数据项关联',
											'height=500, width=650, top=200, left=200, toolbar=no, menubar=no, scrollbars=no, resizable=no,location=n o, status=no');
						}
					},
					html : '<input type="button"  value="设置"  class="txt_btn_a"  />'

				}]
	})
})
