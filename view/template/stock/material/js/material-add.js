$(document).ready(function() {
$("#productName").yxcombogrid_product({
    	hiddenId : 'productId',
		nameCol : 'productName',
		gridOptions : {
			showcheckbox : false,
			event : {
				'row_dblclick' : function(e, row, data){
						$("#productCode").val(data.productCode);
						$("#unitName").val(data.unitName);
						$("#pattern").val(data.pattern);
			  	}
			}
		}
    });
	validate({
				"productName" : {
					required : true
				},
				"materialNum" : {
					required : true,
					custom : ['onlyNumber']
				}
 		});
  });