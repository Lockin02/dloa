$(function() {
			$("#productCode").yxcombogrid_product({// �����ϱ��
				hiddenId : 'productId',
				nameCol : 'productCode',
				gridOptions : {
					showcheckbox : false,
					event : {
						'row_dblclick' : function(e, row, data) {
							$("#productId").trigger('blur');
							if ($("#stockId").val() == "") {
								alert("����ѡ��ֿ�...")
								$("#productId").val("");
								$("#productCode").val("");
								return false;
							} else {
								$("#productName").val(data.productName);
								$("#proType").val(data.proType);
								$("#proTypeId").val(data.proTypeId);
								$("#pattern").val(data.pattern);
								$("#unitName").val(data.unitName);
								$("#aidUnit").val(data.aidUnit);
								$("#converRate").val(data.converRate);
							}
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
							if ($("#stockId").val() == "") {
								alert("����ѡ��ֿ�...")
								$("#productId").val("");
								$("#productCode").val("");
								return false;
							} else {
								$("#productCode").val(data.productCode);
								$("#proType").val(data.proType);
								$("#proTypeId").val(data.proTypeId);
								$("#pattern").val(data.pattern);
								$("#unitName").val(data.unitName);
								$("#aidUnit").val(data.aidUnit);
								$("#converRate").val(data.converRate);
							}
						}
					}
				}
			});
		});