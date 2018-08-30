//人员渲染
$(document).ready(function(){
    $("#sendName").yxselect_user({
		hiddenId : 'sendId'
	});

	$("#suppName").yxcombogrid_supplier({
		hiddenId : 'suppId',
		isShowButton : false,
		height : 200,
		width : 600,
		gridOptions : {
			showcheckbox : false
		}
	});

	$("#productNumb").yxcombogrid_product({
		hiddenId : 'productId',
		isShowButton : false,
		height : 200,
		width : 600,
		gridOptions : {
			showcheckbox : false,
			event :{
				row_dblclick : function(e,row,data){
					$("#productName").val(data.productName);
				}
			}
		}
	});

	$("#beginYear").val($("#beginYearHide").val());
	$("#beginMonth").val($("#beginMonthHide").val()*1);
	$("#endYear").val($("#endYearHide").val());
	$("#endMonth").val($("#endMonthHide").val()*1);

});

//查询
function confirm(){
	var beginYear=$("#beginYear").val();
	var beginMonth=$("#beginMonth").val();
	var endYear=$("#endYear").val();
	var endMonth=$("#endMonth").val();

	var suppId=$("#suppId").val();
	var suppName=$("#suppName").val();

	var hwapplyNumb=$("#hwapplyNumb").val();

	var sendName=$("#sendName").val();
	var sendId=$("#sendId").val();

	var purchTypeCN=$("#purchTypeCN").val();

	var productId=$("#productId").val();
	var productNumb=$("#productNumb").val();
	var productName=$("#productName").val();

	this.opener.location = "?model=purchase_report_purchasereport&action=toPayedNotInvoice"
		+"&beginMonth="+beginMonth
		+"&beginYear="+beginYear
		+"&endMonth="+endMonth
		+"&endYear="+endYear

		+"&suppId="+suppId
		+"&suppName="+suppName

		+"&hwapplyNumb="+hwapplyNumb

		+"&sendName="+sendName
		+"&sendId="+sendId

		+"&purchTypeCN="+purchTypeCN

		+"&productId="+productId
		+"&productNumb="+productNumb
		+"&productName="+productName
	this.close();
}

//清空
function toClear(){
	$(".toClear").val("");
}