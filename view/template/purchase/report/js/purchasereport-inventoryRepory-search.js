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
			showcheckbox : false,
			event :{
				row_dblclick : function(e,row,data){
					$("#suppCode").val(data.busiCode);
				}
			}
		}
	});

	$("#productName").yxcombogrid_product({
		hiddenId : 'productId',
		nameCol : 'productName',
		isShowButton : false,
		height : 200,
		width : 600,
		gridOptions : {
			showcheckbox : false,
			event :{
				row_dblclick : function(e,row,data){
					$("#productNumb").val(data.productCode);
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
	var suppCode=$("#suppCode").val();

	var productId=$("#productId").val();
	var productNumb=$("#productNumb").val();
	var productName=$("#productName").val();

	this.opener.location = "?model=purchase_report_purchasereport&action=toInventoryReport"
		+"&beginMonth="+beginMonth
		+"&beginYear="+beginYear
		+"&endMonth="+endMonth
		+"&endYear="+endYear

		+"&suppId="+suppId
		+"&suppName="+suppName
		+"&suppCode="+suppCode

		+"&productId="+productId
		+"&productNumb="+productNumb
		+"&productName="+productName
	this.close();
}

//清空
function toClear(){
	$(".toClear").val("");
}