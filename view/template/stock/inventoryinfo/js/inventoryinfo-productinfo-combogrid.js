$(function() {
			$("#productCode").yxcombogrid_product({// 绑定物料编号
				hiddenId : 'productId',
				nameCol : 'productCode',
				gridOptions : {
					showcheckbox : false,
					event : {
						'row_dblclick' : function(e, row, data) {
							$("#productId").trigger('blur');
							if ($("#stockId").val() == "") {
								alert("请先选择仓库...")
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
			$("#productName").yxcombogrid_product({// 绑定物料名称
				hiddenId : 'productId',
				nameCol : 'productName',
				gridOptions : {
					showcheckbox : false,
					event : {
						'row_dblclick' : function(e, row, data) {
							$("#productId").trigger('blur');
							if ($("#stockId").val() == "") {
								alert("请先选择仓库...")
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