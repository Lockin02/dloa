$(function() {
			// 客户
			$("#customerName").yxcombogrid_customer({
				hiddenId : 'customerId',
				height : 400,
				isShowButton : false,
				gridOptions : {
					showcheckbox : false,
					event : {
						'row_dblclick' : function(e, row, data) {
							var getGrid = function() {
								return $("#objCode")
										.yxcombogrid_order("getGrid");
							}
							var getGridOptions = function() {
								return $("#objCode")
										.yxcombogrid_order("getGridOptions");
							}
							if (getGrid().reload) {
								getGridOptions().param = {
									"customerId" : data.id,
									"ExaStatus" : "完成"
								};
								getGrid().reload();
							} else {
								getGridOptions().param = {
									"customerId" : data.id,
									"ExaStatus" : "完成"
								}
							}
						}
					}
				}
			});

			// 合同编号
			$("#objCode").yxcombogrid_allorder({
						hiddenId : 'objId',
						nameCol : 'orderCode',
						searchName : 'orderCodeOrTempSearch',
						height : 400,
						width : 700,
						gridOptions : {
							param : {
								"ExaStatus" : "完成"
							},
							showcheckbox : false,
							event : {
								'row_dblclick' : function(e, row, data) {
									if (data.orderCode == "") {
										$("#objCode").val(data.orderTempCode);
									}
									// if(data.orderCode == ""){
									// $("#objCode").yxcombogrid_order('setText',data.orderTempCode);
									// }
								}
							}
						}
			});
			
			// 制定物料
			$("#productCode").yxcombogrid_product({
				hiddenId : 'productId',
				height : 400,
				width : 700,
				gridOptions : {
					showcheckbox : false
				}
			});
			
			// 领料部门
	        $("#deptName").yxselect_dept({
	            hiddenId: 'deptCode'
	        });
		});

function checkform() {

	var beginYearVal = $("#beginYear").val() * 1; // 开始年

	if (beginYearVal != "") {
		if (isNaN(beginYearVal)) {
			alert('开始年份不是数字');
			return false;
		}
		if (beginYearVal > 2100 || beginYearVal < 1980) {
			alert('请输入年份在1980 至 2100 ');
			return false;
		}
	}

	var beginMonthVal = $("#beginMonth").val() * 1; // 开始月

	if (beginMonthVal != "") {
		if (isNaN(beginMonthVal)) {
			alert('开始月份不是数字');
			return false;
		}
		if (beginMonthVal > 12 || beginMonthVal < 1) {
			alert('请输入月份在1 至 12 ');
			return false;
		}
	}

	var endYearVal = $("#endYear").val() * 1; // 结束年

	if (endYearVal != "") {
		if (isNaN(endYearVal)) {
			alert('结束年份不是数字');
			return false;
		}
		if (endYearVal > 2100 || endYearVal < 1980) {
			alert('请输入年份在1980 至 2100 ');
			return false;
		}
	}

	var endMonthVal = $("#endMonth").val() * 1; // 结束月

	if (endMonthVal != "") {
		if (isNaN(endMonthVal)) {
			alert('结束月份不是数字');
			return false;
		}
		if (endMonthVal > 12 || endMonthVal < 1) {
			alert('请输入月份在1 至 12 ');
			return false;
		}
	}

	if ($("#customerName").val() != "") {
		if ($("#customerId").val() == "") {
			alert("请通过下拉表格对客户进行选择");
			return false;
		}
	}

	if ($("#productCode").val() != "") {
		if ($("#productId").val() == "") {
			alert("请通过下拉表格对物料进行选择");
			return false;
		}
	}
	return true;
}