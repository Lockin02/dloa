
// Œ¨–ﬁ…Í«Îœ¬¿≠combogrid
function reloadCustomerCombo() {
	$("#customerName").yxcombogrid_customer({
				hiddenId : 'customerId',
				width : 600,
				isShowButton : false,
				gridOptions : {
					isShowButton : true,
					showcheckbox : false,
					event : {
						'row_dblclick' : function(e, row, data) {
							$("#adress").val(data.Address);
						}
					}
				}
			});
}