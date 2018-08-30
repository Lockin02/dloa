$(function() {
$("#purOrderCode").yxcombogrid_purchcontract({
		hiddenId : 'purOrderId',
		gridOptions : {
			param : {
				'state' : '7'
			},
			showcheckbox : false,
			event : {
				'row_dblclick' : function(e, row, data) {
						$("#purchaserName").val(data.sendName);
						$("#purchaserCode").val(data.sendUserId);
				}
			}
		}
	});
})