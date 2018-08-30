$(function() {
	$("#materialName").yxcombogrid_product({
				hiddenId : 'materialId',
				gridOptions : {
					showcheckbox : false,
					event : {
						'row_dblclick' : function(e, row, data) {
//							alert(data.materialId);
						},
						'row_click' : function(e,row,data) {
							$("#materialName").val(data.productName);
							$("#pattern").val(data.pattern);
							$("#materialCode").val(data.sequence);
							$("#materialId").val(data.id);
						},
						'row_rclick' : function() {
							// alert(222)
						}
					}
				}
			});

});