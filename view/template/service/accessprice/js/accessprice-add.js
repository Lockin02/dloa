$(function() {

			/**
			 * ��֤��Ϣ
			 */
			validate({
						"productCode" : {
							required : true

						},
						"productName" : {
							required : true
						}
					});

			/**
			 * ��Ⱦ�����嵥������Ϣcombogrid
			 */
			$("#productCode").yxcombogrid_product({// �����ϱ��
				hiddenId : 'productId',
				nameCol : 'productCode',
				gridOptions : {
					showcheckbox : false,
					event : {
						'row_dblclick' : function(e, row, data) {
							$("#productId").trigger('blur');
							$("#productName").val(data.productName);
							$("#warranty").val(data.warranty);
							$("#proType").val(data.proType);
							$("#proTypeId").val(data.proTypeId);
							$("#pattern").val(data.pattern);
							$("#unitName").val(data.unitName);
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
							$("#productId").trigger('blur');
							$("#productCode").val(data.productCode);
							$("#warranty").val(data.warranty);
							$("#proType").val(data.proType);
							$("#proTypeId").val(data.proTypeId);
							$("#pattern").val(data.pattern);
							$("#unitName").val(data.unitName);
						}
					}
				}
			});
		});

/**
 * 
 * ����֤
 */
function checkForm() {
	var lp = $("#lowPrice").val();
	var hp = $("#highPrice").val();
	if (parseInt(lp) > parseInt(hp)) {
		alert("��ͼ۲����Ը�����߼ۣ�"); // �۸���֤
		return false;
	}

	var s = plusDateInfo('strartDate', 'endDate');

	if (s < 0) {
		alert("��ʼʱ�䲻�ܺ������ʱ�䣡"); // ʱ����֤
		return false;
	}

}