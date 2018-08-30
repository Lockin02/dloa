$(function(){

	$("#salesman").yxselect_user({
		hiddenId : 'salesmanId'
	});

	$("#exaMan").yxselect_user({
		hiddenId : 'exaManId'
	});

	$("#supplierName").yxcombogrid_supplier({
		hiddenId : 'supplierId',
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
	var supplierId=$("#supplierId").val();
	var objNo=$("#objNo").val();
	var salesmanId=$("#salesmanId").val();
	var exaManId=$("#exaManId").val();
	var status = $("#status").val();
	var formType=$("#formType").val();
	var invType = $("#invType").val();
	var ExaStatus=$("#ExaStatus").val();
	var productNo=$("#productNo").val();
	this.opener.location = "?model=finance_invpurchase_invpurchase&action=viewlist"
						+ "&formDateBegin="+formDateBegin
						+ "&formDateEnd="+formDateEnd
						+ "&supplierId="+supplierId
						+ "&objNo="+objNo
						+ "&salesmanId="+salesmanId
						+ "&exaManId="+exaManId
						+ "&status=" + status
						+ "&formType="+formType
						+ "&invType="+invType
						+ "&ExaStatus=" + ExaStatus
						+ "&productNo=" + productNo;
	this.close();
}

function refresh(){

	window.opener.show_page();
	self.close();

}