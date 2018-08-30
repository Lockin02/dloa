//跳转到列表页面
function toList(){
	if(checkform() == true){
		location='?model=stock_outstock_stockout&action=detailList&beginYear=' + $("#beginYear").val()
			+ '&beginMonth=' + $("#beginMonth").val()
			+ '&endYear=' + $("#endYear").val()
			+ '&endMonth=' + $("#endMonth").val()
			+ '&docType=' + $("#docType").val()
			+ '&pickCode=' + $("#pickCode").val()
			+ '&pickName=' + $("#pickName").val()
			+ '&productId=' + $("#productId").val()
			+ '&productCode=' + $("#productCode").val()
			+ '&deptCode=' + $("#deptCode").val()
			+ '&deptName=' + $("#deptName").val()
			+ '&customerId=' + $("#customerId").val()
			+ '&customerName=' + $("#customerName").val()
			+ '&isRed=' + $("#isRed").val()
			+ '&ifshow=' + $("input:radio[name='ifshow']:checked").val()
			+ '&docCode=' + $("#docCode").val()
			+ '&toUse=' + $("#toUse").val()
			+ '&pattern=' + $("#pattern").val()
			+ '&serialnoName=' + $("#serialnoName").val()
			+ '&actOutNum=' + $("#actOutNum").val()
			+ '&cost=' + $("#cost").val()
			+ '&subCost=' + $("#subCost").val()
			;
	}
}

$(function() {
	$("#pickName").yxselect_user({
		hiddenId : 'pickCode'
	});
	$("#deptName").yxselect_dept({
		hiddenId : 'deptCode'
	});


	//物料
	$("#productCode").yxcombogrid_product({
		hiddenId : 'productId',
		height : 400,
		width : 700,
		gridOptions : {
			showcheckbox : false
		}
	});

	//客户名称
	$("#customerName").yxcombogrid_customer({
		hiddenId : 'customerId',
		height : 400,
		width : 700,
		isShowButton : false,
		gridOptions : {
			showcheckbox : false
		}
	});
});

function checkform(){

	var beginYearVal = $("#beginYear").val() * 1; //开始年

	if(beginYearVal != ""){
		if(isNaN(beginYearVal)){
			alert('开始年份不是数字');
			return false;
		}
		if( beginYearVal > 2100 || beginYearVal < 1980 ){
			alert('请输入年份在1980 至 2100 ');
			return false;
		}
	}

	var beginMonthVal = $("#beginMonth").val() * 1 ; //开始月

	if( beginMonthVal != "" ){
		if(isNaN(beginMonthVal)){
			alert('开始月份不是数字');
			return false;
		}
		if( beginMonthVal > 12 || beginMonthVal < 1 ){
			alert('请输入月份在1 至 12 ');
			return false;
		}
	}

	var endYearVal = $("#endYear").val() * 1; //结束年

	if(endYearVal != ""){
		if(isNaN(endYearVal)){
			alert('结束年份不是数字');
			return false;
		}
		if( endYearVal > 2100 || endYearVal < 1980 ){
			alert('请输入年份在1980 至 2100 ');
			return false;
		}
	}

	var endMonthVal = $("#endMonth").val() * 1 ; //结束月

	if(endMonthVal != "" ){
		if(isNaN(endMonthVal)){
			alert('结束月份不是数字');
			return false;
		}
		if( endMonthVal > 12 || endMonthVal < 1 ){
			alert('请输入月份在1 至 12 ');
			return false;
		}
	}

	if($("#customerName").val() != ""){
		if($("#customerId").val() == ""){
			alert("请通过下拉表格对客户进行选择");
			return false;
		}
	}

	if($("#productCode").val() != ""){
		if($("#productId").val() == ""){
			alert("请通过下拉表格对物料进行选择");
			return false;
		}
	}
	return true;
}