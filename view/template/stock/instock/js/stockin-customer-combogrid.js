$(function() {
		//�ͻ���Ϣ����combogrid
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