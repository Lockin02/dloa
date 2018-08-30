//跳转到列表页面
function toList(){
	if(checkform() == true){
		location='?model=stock_instock_stockin&action=detailList&beginYear=' + $("#beginYear").val()
			+ '&beginMonth=' + $("#beginMonth").val()
			+ '&endYear=' + $("#endYear").val()
			+ '&endMonth=' + $("#endMonth").val()
			+ '&docType=' + $("#docType").val()
			+ '&supplierId=' + $("#supplierId").val()
			+ '&productId=' + $("#productId").val()
			+ '&catchStatus=' + $("#catchStatus").val()
			+ '&isRed=' + $("#isRed").val()
			+ '&ifshow=' + $("input:radio[name='ifshow']:checked").val()
			+ '&docCode=' + $("#docCode").val()
			+ '&inStockId=' + $("#inStockId").val()
			+ '&pattern=' + $("#pattern").val()
			+ '&actNum=' + $("#actNum").val()
			+ '&price=' + $("#price").val()
			+ '&subPrice=' + $("#subPrice").val()
			+ '&createId=' + $("#createId").val()
			+ '&purchaserCode=' + $("#purchaserCode").val()
			+ '&auditerCode=' + $("#auditerCode").val()
			+ '&auditDate=' + $("#auditDate").val()
			;
	}
}

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

	if(beginMonthVal != "" ){
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

	if( endMonthVal != "" ){
		if(isNaN(endMonthVal)){
			alert('结束月份不是数字');
			return false;
		}
		if( endMonthVal > 12 || endMonthVal < 1 ){
			alert('请输入月份在1 至 12 ');
			return false;
		}
	}

	if($("#supplierName").val() != ""){
		if($("#supplierId").val() == ""){
			alert("请通过下拉表格对供应商进行选择");
			return false;
		}
	}

	if($("#objCode").val() != ""){
		if($("#objId").val() == ""){
			alert("请通过下拉表格对合同进行选择");
			return false;
		}
	}

	return true;
}

$(function() {
	// 供应商
	$("#supplierName").yxcombogrid_supplier({
		hiddenId : 'supplierId',
		height : 400,
		width : 600,
		isShowButton : false,
		gridOptions : {
			showcheckbox : false
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
	// 制单
    $("#createName").yxselect_user({
        hiddenId: 'createId'
    });
	// 采购员
    $("#purchaserName").yxselect_user({
        hiddenId: 'purchaserCode'
    });
	// 审核人
    $("#auditerName").yxselect_user({
        hiddenId: 'auditerCode'
    });
});