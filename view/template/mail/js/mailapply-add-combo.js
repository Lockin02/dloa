
/** ********************邮寄单位下拉表格************************ */
$(function() {
	// 单选客户
	$("#customerName").yxcombogrid_customer({
		hiddenId : 'customerId',
		gridOptions : {
			showcheckbox : false,
			event : {
				'row_dblclick' : function(e, row, data) {
//					alert(data.Prov);
				}
			}
		}
	})
});