$(function() {
	// 客户信息下拉combogrid
	$("#customerName").yxcombogrid_customer(
			{
				hiddenId : 'customerId',
				width : 600,
				isShowButton : false,
				gridOptions : {
					isShowButton : true,
					showcheckbox : false,
					event : {
						'row_dblclick' : function(e, row, data) {
							var getGrid = function() {
								return $("#contactUserName")
										.yxcombogrid_linkman("getGrid");
							}
							var getGridOptions = function() {
								return $("#contactUserName")
										.yxcombogrid_linkman("getGridOptions");
							}
							if (getGrid().reload) {
								getGridOptions().param = {
									customerId : data.id
								};
								getGrid().reload();
							} else {
								getGridOptions().param = {
									customerId : data.id
								}
							}
							$("#customerId").val(data.id);
							$("#contactUserName").val("");
							$("#contactUserId").val("");
							$("#address").val(data.Address);
							$("#areaLeaderName").val(data.AreaLeader);
							$("#reaLeaderCode").val(data.AreaLeaderId);
							$("#areaName").val(data.AreaName);
							$("#areaId").val(data.AreaId);
							$("#prov").val(data.Prov);
						}
					}
				}
			});

});