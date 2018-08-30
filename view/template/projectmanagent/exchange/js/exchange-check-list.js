$(function() {
	// 需要退的物料
	$("#backequinfo").yxeditgrid({
		objName : 'exchange[backequ]',
		url:'?model=projectmanagent_exchange_exchangebackequ&action=listJson',
    	param:{
        	'exchangeId' : $("#exchangeId").val(),
			'isDel' : '0'
        },
		tableClass : 'form_in_table',
		colModel : [{
			display : '物料编号',
			name : 'productCode',
			tclass : 'txt',
			readonly : true,
			process : function($input, rowData) {
				var rowNum = $input.data("rowNum");
				var g = $input.data("grid");
				$input.yxcombogrid_contractEqu({
					nameCol: 'productCode',
					isFocusoutCheck : false,
					width : 600,
					event : {
						'clear' : (function(rowNum) {
							return function(e, row, rowData) {
									g.getCmpByRowAndCol(rowNum, 'productId').val('');
									g.getCmpByRowAndCol(rowNum, 'productCode').val('');
				                    g.getCmpByRowAndCol(rowNum, 'productName').val('');
									g.getCmpByRowAndCol(rowNum, 'productModel').val('');
				                    g.getCmpByRowAndCol(rowNum, 'number').val('');
				                    g.getCmpByRowAndCol(rowNum, 'maxNum').val('');
				                    g.getCmpByRowAndCol(rowNum, 'executedNum').val('');
				                    g.getCmpByRowAndCol(rowNum, 'contractId').val('');
				                    g.getCmpByRowAndCol(rowNum, 'contractequId').val('');
							}
						})(rowNum)
					},
					gridOptions : {
						param : {
								'contractId' : $("#contractId").val(),
								'isDel' : '0',
								'maxNum' : 0
						},
						event : {
							row_dblclick : (function(rowNum) {
								return function(e, row, rowData) {
									var $cmps = g.getCmpByCol('contractequId');
									var isReturn = false;
									$cmps.each(function() {
										if ($(this).val() == rowData.id) {
											alert("请不要选择相同的合同物料");
											isReturn = true;
										}
									});
									if (!isReturn) {
										var maxNum = rowData.actNum;
										g.getCmpByRowAndCol(rowNum, 'productId').val(rowData.productId);
										g.getCmpByRowAndCol(rowNum, 'productName').val(rowData.productName);
										g.getCmpByRowAndCol(rowNum, 'productModel').val(rowData.productModel);
	                                    g.getCmpByRowAndCol(rowNum, 'number').val(maxNum);
	                                    g.getCmpByRowAndCol(rowNum, 'maxNum').val(maxNum);
	                                    g.getCmpByRowAndCol(rowNum, 'executedNum').val("0");
	                                    g.getCmpByRowAndCol(rowNum, 'contractId').val(rowData.contractId);
	                                    g.getCmpByRowAndCol(rowNum, 'contractequId').val(rowData.id);
									} else {
										return false;
									}
								}
							})(rowNum)

						}
					}
				});
			}
		}, {
			display : '物料Id',
			name : 'productId',
			type : 'hidden'
		}, {
			display : '合同id',
			name : 'contractId',
			type : 'hidden'
		}, {
			display : '从表id',
			name : 'contractequId',
			type : 'hidden'
		}, {
			display : '物料名称',
			name : 'productName',
			tclass : 'txt',
			readonly : true,
			process : function($input, rowData) {
				var rowNum = $input.data("rowNum");
				var g = $input.data("grid");
				$input.yxcombogrid_contractEqu({
					nameCol: 'productName',
					isFocusoutCheck : false,
					width : 600,
					event : {
						'clear' : (function(rowNum) {
							return function(e, row, rowData) {
									g.getCmpByRowAndCol(rowNum, 'productId').val('');
									g.getCmpByRowAndCol(rowNum, 'productCode').val('');
				                    g.getCmpByRowAndCol(rowNum, 'productName').val('');
									g.getCmpByRowAndCol(rowNum, 'productModel').val('');
				                    g.getCmpByRowAndCol(rowNum, 'number').val('');
				                    g.getCmpByRowAndCol(rowNum, 'maxNum').val('');
				                    g.getCmpByRowAndCol(rowNum, 'executedNum').val('');
				                    g.getCmpByRowAndCol(rowNum, 'contractId').val('');
				                    g.getCmpByRowAndCol(rowNum, 'contractequId').val('');
							}
						})(rowNum)
					},
					gridOptions : {
						param : {
							'contractId' : $("#contractId").val(),
							'isDel' : '0',
							'maxNum' : 0
						},
						event : {
							row_dblclick : (function(rowNum) {
								return function(e, row, rowData) {
									var $cmps = g.getCmpByCol('contractequId');
									var isReturn = false;
									$cmps.each(function() {
										if ($(this).val() == rowData.id) {
											alert("请不要选择相同的合同物料");
											isReturn = true;
										}
									});
									if (!isReturn) {
										var maxNum = rowData.actNum;
										g.getCmpByRowAndCol(rowNum, 'productId').val(rowData.productId);
										g.getCmpByRowAndCol(rowNum, 'productCode').val(rowData.productCode);
										g.getCmpByRowAndCol(rowNum, 'productModel').val(rowData.productModel);
	                                    g.getCmpByRowAndCol(rowNum, 'number').val(maxNum);
	                                    g.getCmpByRowAndCol(rowNum, 'maxNum').val(maxNum);
	                                    g.getCmpByRowAndCol(rowNum, 'executedNum').val("0");
	                                    g.getCmpByRowAndCol(rowNum, 'contractId').val(rowData.contractId);
	                                    g.getCmpByRowAndCol(rowNum, 'contractequId').val(rowData.id);
									} else {
										return false;
									}
								}
							})(rowNum)
						}
					}
				});
			}
		}, {
			display : '型号/版本',
			name : 'productModel',
			tclass : 'readOnlyTxtNormal',
			readonly : true,
			width : 400
		}, {
			display : '最大可执行数量',
			name : 'maxNum',
			type : 'hidden'
		}, {
			display : '退货数量',
			name : 'number',
			tclass : 'txtshort',
			width : 100,
			event : {
				blur : function(e, rowNum, g) {
					var rowNum = $(this).data("rowNum");
					thisNumber = parseInt($("#backequinfo_cmp_number" + rowNum).val());
					maxNum = parseInt($("#backequinfo_cmp_maxNum" + rowNum).val());
					if(thisNumber <= 0 || thisNumber > maxNum){
                       alert("数量不得大于"+maxNum+",或小于等于0 ");
                       var g = $(this).data("grid");
                       g.setRowColValue(rowNum, "number",maxNum, true);
					}
				}
			}
		}, {
			display : '执行数量',
			name : 'executedNum',
			tclass : 'readOnlyTxtNormal',
			readonly : true,
			width : 100
		}]
	});
	//换货物料
	$("#equinfo").yxeditgrid({
		objName : 'exchange[equ]',
		url:'?model=projectmanagent_exchange_exchangeequ&action=listJson',
    	param:{
        	'exchangeId' : $("#exchangeId").val(),
			'isDel' : '0'
        },
		tableClass : 'form_in_table',
		colModel : [{
			display : '物料编号',
			name : 'productCode',
			tclass : 'txt',
			readonly : true,
			process : function($input, rowData) {
				var rowNum = $input.data("rowNum");
				var g = $input.data("grid");
				$input.yxcombogrid_product({
					nameCol: 'productCode',
					isFocusoutCheck : false,
					width : 600,
					event : {
						'clear' : (function(rowNum) {
							return function(e, row, rowData) {
									g.getCmpByRowAndCol(rowNum, 'productId').val('');
									g.getCmpByRowAndCol(rowNum, 'productCode').val('');
				                    g.getCmpByRowAndCol(rowNum, 'productName').val('');
									g.getCmpByRowAndCol(rowNum, 'productModel').val('');
				                    g.getCmpByRowAndCol(rowNum, 'number').val('');
				                    g.getCmpByRowAndCol(rowNum, 'executedNum').val('');
							}
						})(rowNum)
					},
					gridOptions : {
						event : {
							row_dblclick : (function(rowNum) {
								return function(e, row, rowData) {
									g.getCmpByRowAndCol(rowNum, 'productId').val(rowData.id);
									g.getCmpByRowAndCol(rowNum, 'productName').val(rowData.productName);
									g.getCmpByRowAndCol(rowNum, 'productModel').val(rowData.pattern);
                                    g.getCmpByRowAndCol(rowNum, 'number').val("1");
                                    g.getCmpByRowAndCol(rowNum, 'executedNum').val("0");
								}
							})(rowNum)
						}
					}
				});
			}
		}, {
			display : '物料Id',
			name : 'productId',
			type : 'hidden'
		}, {
			display : '物料名称',
			name : 'productName',
			tclass : 'txt',
			readonly : true,
			process : function($input, rowData) {
				var rowNum = $input.data("rowNum");
				var g = $input.data("grid");
				$input.yxcombogrid_product({
					nameCol: 'productName',
					isFocusoutCheck : false,
					width : 600,
					event : {
						'clear' : (function(rowNum) {
							return function(e, row, rowData) {
									g.getCmpByRowAndCol(rowNum, 'productId').val('');
									g.getCmpByRowAndCol(rowNum, 'productCode').val('');
				                    g.getCmpByRowAndCol(rowNum, 'productName').val('');
									g.getCmpByRowAndCol(rowNum, 'productModel').val('');
				                    g.getCmpByRowAndCol(rowNum, 'number').val('');
				                    g.getCmpByRowAndCol(rowNum, 'executedNum').val('');
							}
						})(rowNum)
					},
					gridOptions : {
						event : {
							row_dblclick : (function(rowNum) {
								return function(e, row, rowData) {
									g.getCmpByRowAndCol(rowNum, 'productId').val(rowData.id);
									g.getCmpByRowAndCol(rowNum, 'productCode').val(rowData.productCode);
									g.getCmpByRowAndCol(rowNum, 'productModel').val(rowData.pattern);
                                    g.getCmpByRowAndCol(rowNum, 'number').val("1");
                                    g.getCmpByRowAndCol(rowNum, 'executedNum').val("0");
								}
							})(rowNum)
						}
					}
				});
			}
		}, {
			display : '型号/版本',
			name : 'productModel',
			tclass : 'readOnlyTxtNormal',
			readonly : true,
			width : 400
		}, {
			display : '换货数量',
			name : 'number',
			tclass : 'txtshort',
			width : 100
		}, {
			display : '执行数量',
			name : 'executedNum',
			tclass : 'readOnlyTxtNormal',
			readonly : true,
			width : 100
		}]
	});
});
