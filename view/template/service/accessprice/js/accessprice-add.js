$(function() {

			/**
			 * 验证信息
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
			 * 渲染物料清单物料信息combogrid
			 */
			$("#productCode").yxcombogrid_product({// 绑定物料编号
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
			$("#productName").yxcombogrid_product({// 绑定物料名称
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
 * 表单验证
 */
function checkForm() {
	var lp = $("#lowPrice").val();
	var hp = $("#highPrice").val();
	if (parseInt(lp) > parseInt(hp)) {
		alert("最低价不可以高于最高价！"); // 价格验证
		return false;
	}

	var s = plusDateInfo('strartDate', 'endDate');

	if (s < 0) {
		alert("开始时间不能后过结束时间！"); // 时间验证
		return false;
	}

}