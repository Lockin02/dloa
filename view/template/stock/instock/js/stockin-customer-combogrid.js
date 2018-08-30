$(function() {
		//客户信息下拉combogrid
			$("#clientName").yxcombogrid_customer({
						hiddenId : 'clientId',
						width : 600,
						isShowButton : false,
						gridOptions : {
							isShowButton : true,
							showcheckbox : false,
							event : {
								'row_dblclick' : function(e, row, data) {
								}
							}
						}
					});
		});