$(function() {
	$("#suppName").yxcombogrid_supplier({
				hiddenId : 'suppId',
				height:300,
				gridOptions : {
					showcheckbox : false,
					event : {
					'row_dblclick' : function(e, row, data) {
							//alert(data.Prov);
						},
						'row_click' : function() {
							// alert(123)
						},
						'row_rclick' : function() {
							// alert(222)
						}
					}
				}
			});


	$("#productName").yxcombogrid_product({
    	hiddenId : 'productId',
		nameCol : 'productName',
		height:300,
		gridOptions : {
			showcheckbox : false,
			event : {
			'row_dblclick' : function(e, row, data) {
					//alert(data.Prov);
				},
				'row_click' : function() {
					// alert(123)
				},
				'row_rclick' : function() {
					// alert(222)
				}
			}
		}
    });
	$("#applyDeptName").yxselect_dept({
				hiddenId : 'applyDeptId'
	});
});