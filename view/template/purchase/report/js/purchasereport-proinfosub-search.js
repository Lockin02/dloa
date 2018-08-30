//人员渲染
$(document).ready(function(){
//	$("#suppName").yxcombogrid_supplier({
//		hiddenId : 'suppId',
//		isShowButton : false,
//		isFocusoutCheck : false,
//		height : 200,
//		width : 600,
//		gridOptions : {
//			showcheckbox : false
//		}
//	});

	$("#productNumb").yxcombogrid_product({
		hiddenId : 'productId',
		isShowButton : false,
		height : 200,
		width : 600,
		isShowButton : false,
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

//	var suppId=$("#suppId").val();
//	var suppName=$("#suppName").val();

	var productId=$("#productId").val();
	var productNumb=$("#productNumb").val();
	var productName=$("#productName").val();

	this.opener.location = "?model=purchase_report_purchasereport&action=toProInfoSub"
		+"&beginMonth="+beginMonth
		+"&beginYear="+beginYear
		+"&endMonth="+endMonth
		+"&endYear="+endYear

		+"&suppId="
		+"&suppName="
//		+"&suppId="+suppId
//		+"&suppName="+suppName

		+"&productId="+productId
		+"&productNumb="+productNumb
		+"&productName="+productName
	this.close();
}

//清空
function toClear(){
	$(".toClear").val("");
}