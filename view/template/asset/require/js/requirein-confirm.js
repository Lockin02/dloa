$(document).ready(function() {
	//������֤���Ϊ�գ��ӱ��޷�������֤
	validate({
		"applyDate" : {
			required : true
		}
	});
	$("#itemTable").yxeditgrid({
		url : '?model=asset_require_requireinitem&action=listByRequireJson',
		objName : 'requirein[items]',
		title : '������Ϣ',
		param : {
			mainId : $("#id").val()
		},
		isAddAndDel : false,
		isAddOneRow : true,
		colModel : [{
			display : '����id',
			name : 'id',
			type : 'hidden'
		}, {
			display : '���ϱ��',
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
			display : '��������',
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
			display : '����ID',
			name : 'productId',
			type : 'hidden'
		}, {
			display : '���Ͻ��',
			name : 'productPrice',
			type : 'money',
			width : 80
		}, {
			display : '����Ʒ��',
			name : 'brand',
			width : 100
		}, {
			display : '����ͺ�',
			name : 'spec',
			width : 100
		}, {
			display : '�豸����',
			name : 'name',
			readonly : true,
			tclass : 'readOnlyTxtShort',
			width : 120
		}, {
			display : '�豸����',
			name : 'description',
			readonly : true,
			tclass : 'readOnlyTxtShort',
			width : 120
		}, {
			display : '����',
			name : 'number',
			readonly : true,
			tclass : 'readOnlyTxtShort',
			width : 60
		}, {
			display : '��ע',
			name : 'remark',
			tclass : 'txt',
			width : 200
		}]
	})
});
//ȷ������
function confirmSubmit() {
	if (confirm("ȷ������?")) {
		$("#form1").attr("action","?model=asset_require_requirein&action=edit&actType=confirm");
		$("#form1").submit();
	} else {
		return false;
	}
}

