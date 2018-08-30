$(function() {
	// π©”¶…Ã
	$("#customerName").yxcombogrid_customer({
		hiddenId : 'customerId',
		isShowButton : false,
		height : 500,
		gridOptions : {
			showcheckbox : false
		}
	});

	$("#thisYear").val($("#year").val());
	$("#thisMonth").val($("#month").val());
});