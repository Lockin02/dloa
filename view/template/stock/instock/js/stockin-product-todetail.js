//跳转到列表页面
function toList(){
	if(checkform() == true){
		location='?model=stock_instock_stockin&action=detailList&beginYear=' + $("#beginYear").val()
			+ '&beginMonth=' + $("#beginMonth").val()
			+ '&endYear=' + $("#endYear").val()
			+ '&endMonth=' + $("#endMonth").val()
			+ '&docType=' + $("#docType").val()
			+ '&productId=' + $("#productId").val()
			+ '&docStatus=' + $("#docStatus").val()
			+ '&isRed=' + $("#isRed").val()
			;
	}
}

function checkform(){

	var beginYearVal = $("#beginYear").val() * 1; //开始年

	if(beginYearVal == ""){
		alert('请输入开始年份');
		return false;
	}
	if(isNaN(beginYearVal)){
		alert('开始年份不是数字');
		return false;
	}
	if( beginYearVal > 2100 || beginYearVal < 1980 ){
		alert('请输入年份在1980 至 2100 ');
		return false;
	}

	var beginMonthVal = $("#beginMonth").val() * 1 ;

	if( beginMonthVal == "" ){
		alert('请输入开始月份');
		return false;
	}
	if(isNaN(beginMonthVal)){
		alert('开始月份不是数字');
		return false;
	}
	if( beginMonthVal > 12 || beginMonthVal < 1 ){
		alert('请输入月份在1 至 12 ');
		return false;
	}

	var endYearVal = $("#endYear").val() * 1; //结束年

	if(endYearVal == ""){
		alert('请输入结束年份');
		return false;
	}
	if(isNaN(endYearVal)){
		alert('结束年份不是数字');
		return false;
	}
	if( endYearVal > 2100 || endYearVal < 1980 ){
		alert('请输入年份在1980 至 2100 ');
		return false;
	}
	var endMonthVal = $("#endMonth").val() * 1 ;

	if( endMonthVal == "" ){
		alert('请输入结束月份');
		return false;
	}
	if(isNaN(endMonthVal)){
		alert('结束月份不是数字');
		return false;
	}
	if( endMonthVal > 12 || endMonthVal < 1 ){
		alert('请输入月份在1 至 12 ');
		return false;
	}


	return true;
}

$(function() {
	// 供应商
	$("#productCode").yxcombogrid_product({
		hiddenId : 'productId',
		height : 400,
		width : 700,
		gridOptions : {
			showcheckbox : false
		}
	});
});