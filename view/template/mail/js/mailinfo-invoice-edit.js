$(function() {
	$("#customerName").yxcombogrid_customer({
		hiddenId : 'customerId',
		isShowButton : false,
		gridOptions : {
			showcheckbox : false
		}
	})

	$("#logisticsName").yxcombogrid_logistics({
		hiddenId : 'logisticsId',
		gridOptions : {
			showcheckbox : false
		}
	})
});