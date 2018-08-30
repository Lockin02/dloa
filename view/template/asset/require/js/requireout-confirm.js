$(document).ready(function() {
	//主表验证如果为空，从表无法进行验证
	validate({
		"applyDate" : {
			required : true
		}
	});
	$("#itemTable").yxeditgrid({
		url : '?model=asset_require_requireoutitem&action=listByRequireJson',
		objName : 'requireout[items]',
		title : '物料信息',
		param : {
			mainId : $("#id").val()
		},
		isAddAndDel : false,
		colModel : [{
			display : '卡片编号',
			name : 'assetCode',
			readonly : true,
			tclass : 'readOnlyTxtShort',
			width : 120
		}, {
			display : '资产名称',
			name : 'assetName',
			readonly : true,
			tclass : 'readOnlyTxtShort',
			width : 120
		}, {
			display : '资产残值',
			name : 'salvage',
			type : 'money',
			readonly : true,
			tclass : 'readOnlyTxtShort',
			width : 80
		}, {
			display : '资产id',
			name : 'assetId',
			type : 'hidden'
		}, {
			display : '物料id',
			name : 'id',
			type : 'hidden'
		}, {
			display : '物料编号',
			name : 'productCode',
			tclass : 'txtmiddle',
			validation : {
				required : true
			},
			process : function($input, rowData) {
				var rowNum = $input.data("rowNum");
				var g = $input.data("grid");
				$input.yxcombogrid_product({
					isFocusoutCheck : false,
					closeAndStockCheck : true,
					hiddenId : 'itemTable_cmp_productId'
							+ rowNum,
					height : 250,
					event : {
						'clear' : function() {
							g.setRowColValue(rowNum,'productId','');
							g.setRowColValue(rowNum,'productName','');
							g.setRowColValue(rowNum,'spec','');
						}
					},
					gridOptions : {
						showcheckbox : false,
						isTitle : true,
						event : {
							"row_dblclick" : (function(rowNum, rowData) {
								return function(e, row, productData) {
									g.getCmpByRowAndCol(rowNum, 'productName').val(productData.productName);
									g.getCmpByRowAndCol(rowNum, 'spec').val(productData.pattern);
								}
							})(rowNum, rowData)
						}
					}
				});
			},
			width : 120
		}, {
			display : '物料名称',
			name : 'productName',
			tclass : 'txt',
			validation : {
				required : true
			},
			process : function($input, rowData) {
				var rowNum = $input.data("rowNum");
				var g = $input.data("grid");
				$input.yxcombogrid_product({
					isFocusoutCheck : false,
					closeAndStockCheck : true,
					hiddenId : 'itemTable_cmp_productId'
							+ rowNum,
					nameCol : 'productName',
					height : 250,
					event : {
						'clear' : function() {
							g.setRowColValue(rowNum,'productId','');
							g.setRowColValue(rowNum,'productCode','');
							g.setRowColValue(rowNum,'spec','');
						}
					},
					gridOptions : {
						showcheckbox : false,
						isTitle : true,
						event : {
							"row_dblclick" : (function(rowNum, rowData) {
								return function(e, row, productData) {
									g.getCmpByRowAndCol(rowNum, 'productCode').val(productData.productCode);
									g.getCmpByRowAndCol(rowNum, 'spec').val(productData.pattern);
								}								
							})(rowNum, rowData)
						}
					}
				});
			},
			width : 120
		}, {
			display : '物料ID',
			name : 'productId',
			type : 'hidden'
		}, {
			display : '规格型号',
			name : 'spec',
			width : 120
		}, {
			display : '数量',
			name : 'number',
			readonly : true,
			tclass : 'readOnlyTxtShort',
			width : 60
		}, {
			display : '备注',
			name : 'remark',
			tclass : 'txt',
			width : 120
		}]
	});
});
//确认物料
function confirmSubmit() {
	if (confirm("确认物料?")) {
		$("#form1").attr("action","?model=asset_require_requireout&action=edit&actType=confirm");
		$("#form1").submit();
	} else {
		return false;
	}
}

