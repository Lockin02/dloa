function toSupport(){
	var beginYearVal = $("#beginYear").val();
	var endYearVal = $("#endYear").val();
	var beginMonthVal = $("#beginMonth").val();
	var endMonthVal = $("#endMonth").val();
	var customerId = $("#customerId").val();
	var customerName = $("#customerName").val();
	var objId = $("#objId").val();
	var objCode = $("#objCode").val();


	if(beginYearVal == ""){
		alert("开始年份不能为空");
		return false;
	}

	if(isNaN(beginYearVal)){
		alert("请输入正确的开始年份");
		return false;
	}


	if(endYearVal == ""){
		alert("结束年份不能为空");
		return false;
	}

	if(isNaN(endYearVal)){
		alert("请输入正确的结束年份");
		return false;
	}

	if(beginYearVal > endYearVal){
		alert("开始年份需要小于或等于结束年份");
		return false;
	}

	if(beginYearVal == endYearVal){
		if( beginMonthVal > endMonthVal ){
			alert("开始月份需要小于或等于结束月份");
			return false;
		}
	}
	var objType = $("#objType").val();
	var url = '?model=finance_invoice_invoice&action=invoiceInfoList&objType=' + objType + "&beginYear=" + beginYearVal
			+ "&endYear=" + endYearVal
			+ "&beginMonth=" + beginMonthVal
			+ "&endMonth=" + endMonthVal
			+ "&customerId=" + customerId
			+ "&customerName=" + customerName
			+ "&objId=" + objId
			+ "&objCode=" + objCode
			;
	location = url;
}

$(function(){
	$("#beginMonth").val($("#month").val());
	$("#endMonth").val($("#month").val());


	//客户
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

	//合同编号
	$("#objCode").yxcombogrid_order({
		hiddenId : 'objId',
		nameCol : 'orderCode',
		searchName : 'orderCodeOrTempSearch',
		height : 400,
		width : 700,
		gridOptions : {
			param : {"ExaStatus" : "完成"},
			showcheckbox : false,
			event : {
				'row_dblclick' : function(e, row, data) {
					if(data.orderCode == ""){
						$("#objCode").yxcombogrid_order('setText',data.orderTempCode);
					}
				}
			}
		}
	});
});