$(function() {
	// 客户联系人下拉combogrid
	$("#contactUserName").yxcombogrid_linkman({
		isFocusoutCheck : false,
		hiddenId : 'contactUserId',
		width : 600,
		isShowButton : false,
		gridOptions : {
			isShowButton : true,
			showcheckbox : false,
			param : {
				'customerId' : $('#customerId').val()
			},
			event : {
				'row_dblclick' : function(e, row, data) {
					$("#customerName").val(data.customerName);
					$("#customerId").val(data.customerId);
					$("#telephone").val(data.phone);
				}
			}
		}
	});
});