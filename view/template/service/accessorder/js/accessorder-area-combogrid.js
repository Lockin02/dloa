$(function() {
			// 客户区域下拉combogrid
			$("#areaName").yxcombogrid_area({
						hiddenId : 'areaId',
						width : 600,
						isShowButton : false,
						gridOptions : {
							isShowButton : true,
							showcheckbox : false,
							event : {
								'row_dblclick' : function(e, row, data) {
									$("#areaLeaderName")
											.val(data.areaPrincipal);
									$("#areaLeaderId")
											.val(data.areaPrincipalId);
								}
							}
						}
					});
		});