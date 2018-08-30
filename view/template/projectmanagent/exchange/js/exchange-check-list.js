$(function() {
	// ��Ҫ�˵�����
	$("#backequinfo").yxeditgrid({
		objName : 'exchange[backequ]',
		url:'?model=projectmanagent_exchange_exchangebackequ&action=listJson',
    	param:{
        	'exchangeId' : $("#exchangeId").val(),
			'isDel' : '0'
        },
		tableClass : 'form_in_table',
		colModel : [{
			display : '���ϱ��',
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
											alert("�벻Ҫѡ����ͬ�ĺ�ͬ����");
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
			display : '����Id',
			name : 'productId',
			type : 'hidden'
		}, {
			display : '��ͬid',
			name : 'contractId',
			type : 'hidden'
		}, {
			display : '�ӱ�id',
			name : 'contractequId',
			type : 'hidden'
		}, {
			display : '��������',
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
											alert("�벻Ҫѡ����ͬ�ĺ�ͬ����");
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
			display : '�ͺ�/�汾',
			name : 'productModel',
			tclass : 'readOnlyTxtNormal',
			readonly : true,
			width : 400
		}, {
			display : '����ִ������',
			name : 'maxNum',
			type : 'hidden'
		}, {
			display : '�˻�����',
			name : 'number',
			tclass : 'txtshort',
			width : 100,
			event : {
				blur : function(e, rowNum, g) {
					var rowNum = $(this).data("rowNum");
					thisNumber = parseInt($("#backequinfo_cmp_number" + rowNum).val());
					maxNum = parseInt($("#backequinfo_cmp_maxNum" + rowNum).val());
					if(thisNumber <= 0 || thisNumber > maxNum){
                       alert("�������ô���"+maxNum+",��С�ڵ���0 ");
                       var g = $(this).data("grid");
                       g.setRowColValue(rowNum, "number",maxNum, true);
					}
				}
			}
		}, {
			display : 'ִ������',
			name : 'executedNum',
			tclass : 'readOnlyTxtNormal',
			readonly : true,
			width : 100
		}]
	});
	//��������
	$("#equinfo").yxeditgrid({
		objName : 'exchange[equ]',
		url:'?model=projectmanagent_exchange_exchangeequ&action=listJson',
    	param:{
        	'exchangeId' : $("#exchangeId").val(),
			'isDel' : '0'
        },
		tableClass : 'form_in_table',
		colModel : [{
			display : '���ϱ��',
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
			display : '����Id',
			name : 'productId',
			type : 'hidden'
		}, {
			display : '��������',
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
			display : '�ͺ�/�汾',
			name : 'productModel',
			tclass : 'readOnlyTxtNormal',
			readonly : true,
			width : 400
		}, {
			display : '��������',
			name : 'number',
			tclass : 'txtshort',
			width : 100
		}, {
			display : 'ִ������',
			name : 'executedNum',
			tclass : 'readOnlyTxtNormal',
			readonly : true,
			width : 100
		}]
	});
});
