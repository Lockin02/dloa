$(function (){

	//物料
	$("#equInfo").yxeditgrid({
		objName : 'contract[equ]',
		url : '?model=contract_contract_equ&action=listJson',
		param : {
			'contractId' : $("#contractId").val(),
			'isTemp' : '0',
			'isDel' : '0'
		},
		tableClass : 'form_in_table',
//		isAddOneRow:false,
//		isAdd : false,
		colModel : [{
			display : 'id',
			name : 'id',
			type : 'hidden'
		},{
			display : '物料编号',
			name : 'productCode',
			tclass : 'readOnlyTxtNormal',
			readonly : true,
			process : function($input, rowData) {
				var rowNum = $input.data("rowNum");
				var g = $input.data("grid");
				var isEqu = g.getCmpByRowAndCol(rowNum, 'productCode').val();
				if(isEqu == ''){
				   $input.yxcombogrid_product({
					hiddenId : 'itemTable_cmp_productId' + rowNum,
					nameCol : 'productCode',
//					closeCheck : true,// 关闭状态,不可选择
					closeAndStockCheck : true,
					width : 600,
					gridOptions : {
						event : {
							row_dblclick : (function(rowNum) {
								return function(e, row, rowData) {
									   g.getCmpByRowAndCol(rowNum, 'productId').val(rowData.id);
									   g.getCmpByRowAndCol(rowNum, 'productName').val(rowData.productName);
									   g.getCmpByRowAndCol(rowNum, 'productModel').val(rowData.pattern);
									   g.getCmpByRowAndCol(rowNum, 'number').val("1");
								}
							})(rowNum)
						}
					}
				});
				}
			}
		}, {
			display : '物料Id',
			name : 'productId',
			type : 'hidden'
		}, {
			display : '物料名称',
			name : 'productName',
			tclass : 'readOnlyTxtNormal',
			readonly : true,
			process : function($input, rowData) {
				var rowNum = $input.data("rowNum");
				var g = $input.data("grid");
				var isEqu = g.getCmpByRowAndCol(rowNum, 'productCode').val();
				if(isEqu == ''){
				   $input.yxcombogrid_product({
					hiddenId : 'itemTable_cmp_productId' + rowNum,
					nameCol : 'productName',
//					closeCheck : true,// 关闭状态,不可选择.
					closeAndStockCheck : true,
					width : 600,
					gridOptions : {
						event : {
							row_dblclick : (function(rowNum) {
								return function(e, row, rowData) {
									g.getCmpByRowAndCol(rowNum, 'productId').val(rowData.id);
									g.getCmpByRowAndCol(rowNum, 'productCode').val(rowData.productCode);
									g.getCmpByRowAndCol(rowNum, 'productModel').val(rowData.pattern);
                                    g.getCmpByRowAndCol(rowNum, 'number').val("1");
								}
							})(rowNum)
						}
					}
				 });
				}
			}
		}, {
			display : '型号/版本',
			name : 'productModel',
			tclass : 'readOnlyTxtNormal',
			readonly : true
		}, {
			display : '数量',
			name : 'number',
			tclass : 'txtshort',
			event : {
				blur : function() {
					countAll($(this).data("rowNum"),"equinfo");
				}
			}
		}, {
			display : '单价',
			name : 'price',
			tclass : 'txtshort',
			type : 'money',
			event : {
				blur : function() {
					countAll($(this).data("rowNum"),"equinfo");
				}
			}
		}, {
			display : '金额',
			name : 'money',
			tclass : 'txtshort',
			type : 'money'
		}]

	});
});