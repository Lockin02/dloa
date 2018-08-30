$(function() {
			// 客户信息下拉combogrid
			$("#customerName").yxcombogrid_customer({
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
									$("#adress").val(data.Address);
									$("#prov").val(data.Prov);
								}
							}
						}
					});

					//选择组件
					$("#proposer").yxselect_user({
						hiddenId : 'proposerId',
						mode : 'single'
					});
					//选择组件
					$("#applyUserName").yxselect_user({
						hiddenId : 'applyUserCode',
						mode : 'single'
					});
					//部门组件
					$("#applyDeptName").yxselect_dept({
						hiddenId : 'applyDeptId',
						mode : 'single'
					});
		});