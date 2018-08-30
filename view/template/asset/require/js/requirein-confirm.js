$(document).ready(function() {
	//主表验证如果为空，从表无法进行验证
	validate({
		"applyDate" : {
			required : true
		}
	});
	$("#itemTable").yxeditgrid({
		url : '?model=asset_require_requireinitem&action=listByRequireJson',
		objName : 'requirein[items]',
		title : '物料信息',
		param : {
			mainId : $("#id").val()
		},
		isAddAndDel : false,
		isAddOneRow : true,
		colModel : [{
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
							g.setRowColValue(rowNum,'productName','');
							g.setRowColValue(rowNum,'productPrice','');
							g.setRowColValue(rowNum,'spec','');
							g.setRowColValue(rowNum,'brand','');
						}
					},
					gridOptions : {
						showcheckbox : false,
						isTitle : true,
						event : {
							"row_dblclick" : (function(rowNum, rowData) {
								return function(e, row, productData) {
									g.setRowColValue(rowNum, 'productName',productData.productName);
									g.setRowColValue(rowNum, 'productPrice',productData.priCost,true);
									g.setRowColValue(rowNum, 'spec',productData.pattern);
									g.setRowColValue(rowNum, 'brand',productData.brand);
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
							g.setRowColValue(rowNum, 'productCode','');
							g.setRowColValue(rowNum, 'productPrice','');
							g.setRowColValue(rowNum, 'spec','');
							g.setRowColValue(rowNum, 'brand','');
						}
					},
					gridOptions : {
						showcheckbox : false,
						isTitle : true,
						event : {
							"row_dblclick" : (function(rowNum, rowData) {
								return function(e, row, productData) {
									g.setRowColValue(rowNum, 'productCode',productData.productCode);
									g.setRowColValue(rowNum, 'productPrice',productData.priCost,true);
									g.setRowColValue(rowNum, 'spec',productData.pattern);
									g.setRowColValue(rowNum, 'brand',productData.brand);
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
			display : '物料金额',
			name : 'productPrice',
			type : 'money',
			width : 80
		}, {
			display : '物料品牌',
			name : 'brand',
			width : 100
		}, {
			display : '规格型号',
			name : 'spec',
			width : 100
		}, {
			display : '设备名称',
			name : 'name',
			readonly : true,
			tclass : 'readOnlyTxtShort',
			width : 120
		}, {
			display : '设备描述',
			name : 'description',
			readonly : true,
			tclass : 'readOnlyTxtShort',
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
			width : 200
		}]
	})
});
//确认物料
function confirmSubmit() {
	if (confirm("确认物料?")) {
		$("#form1").attr("action","?model=asset_require_requirein&action=edit&actType=confirm");
		$("#form1").submit();
	} else {
		return false;
	}
}

