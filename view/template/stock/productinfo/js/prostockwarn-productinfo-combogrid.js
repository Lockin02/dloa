$(function() {
	$("#productCode").yxcombogrid_product({// �����ϱ��
		hiddenId : 'productId',
		nameCol : 'productCode',
		gridOptions : {
			showcheckbox : false,
			event : {
				'row_dblclick' : function(e, row, data) {
					$("#productName").val(data.productName);
					$("#pattern").val(data.pattern);
					$("#unitName").val(data.unitName);

					$("#productId").trigger('focus');
				}
			}
		}
	});
	$("#productName").yxcombogrid_product({// ����������
		hiddenId : 'productId',
		nameCol : 'productName',
		gridOptions : {
			showcheckbox : false,
			event : {
				'row_dblclick' : function(e, row, data) {
					$("#productCode").val(data.productCode);
					$("#pattern").val(data.pattern);
					$("#unitName").val(data.unitName);
				}
			}
		}
	});
});