$(document).ready(function() {
	//������֤���Ϊ�գ��ӱ��޷�������֤
	validate({
		"applyDate" : {
			required : true
		}
	});
	$("#itemTable").yxeditgrid({
		url : '?model=asset_require_requireoutitem&action=listByRequireJson',
		objName : 'requireout[items]',
		title : '������Ϣ',
		param : {
			mainId : $("#id").val()
		},
		isAddAndDel : false,
		colModel : [{
			display : '��Ƭ���',
			name : 'assetCode',
			readonly : true,
			tclass : 'readOnlyTxtShort',
			width : 120
		}, {
			display : '�ʲ�����',
			name : 'assetName',
			readonly : true,
			tclass : 'readOnlyTxtShort',
			width : 120
		}, {
			display : '�ʲ���ֵ',
			name : 'salvage',
			type : 'money',
			readonly : true,
			tclass : 'readOnlyTxtShort',
			width : 80
		}, {
			display : '�ʲ�id',
			name : 'assetId',
			type : 'hidden'
		}, {
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
			display : '����ID',
			name : 'productId',
			type : 'hidden'
		}, {
			display : '����ͺ�',
			name : 'spec',
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
			width : 120
		}]
	});
});
//ȷ������
function confirmSubmit() {
	if (confirm("ȷ������?")) {
		$("#form1").attr("action","?model=asset_require_requireout&action=edit&actType=confirm");
		$("#form1").submit();
	} else {
		return false;
	}
}

