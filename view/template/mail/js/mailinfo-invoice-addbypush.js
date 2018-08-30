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

	if($("#TO_ID").length!=0){
		$("#TO_NAME").yxselect_user({
			mode : 'check',
			hiddenId : 'TO_ID'
		});
	}

	$("#mailMan").yxselect_user({
		hiddenId : 'mailManId'
	});

	$("#salesman").yxselect_user({
		mode : 'check',
		hiddenId : 'salesmanId'
	});
});