$(function(){
	$("#supplierName").yxcombogrid_supplier({
		hiddenId : 'supplierId',
    	isFocusoutCheck : false,
		gridOptions : {
			event : {
				'row_dblclick' : function(e, row, data) {
					$("#address").val(data.address);
				}
			}
		}
	});

	//ŒÔ¡œ±‡∫≈
	$("#productNo").yxcombogrid_product({
    	hiddenId : 'productId',
		gridOptions : {
			showcheckbox : false
		}
    });
});

function confirm(){
	var formDateBegin=$("#formDateBegin").val();
	var formDateEnd=$("#formDateEnd").val();
	var supplierName=$("#supplierName").val();
	var supplierId=$("#supplierId").val();
	var productId=$("#productId").val();
	var productNo=$("#productNo").val();
	this.opener.location = "?model=finance_adjust_adjust&action=listinfo"
						+ "&formDateBegin="+formDateBegin
						+ "&formDateEnd="+formDateEnd
						+ "&supplierName="+supplierName
						+ "&supplierId="+supplierId
						+ "&productId="+productId
						+ "&productNo=" + productNo;
	this.close();
}

function refresh(){
	$(".toClear").val('');
}