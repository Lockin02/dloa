/**
 * 销售发票金额计算
 */
function countSales(){
	var allMoney = 0;
	var salesOne = $('#salesOne').val() * 1;
	var salesTwo = $('#salesTwo').val() * 1;
	var salesThree = $('#salesThree').val() * 1;
	var salesFour = $('#salesFour').val() * 1;
	if(salesOne != "" ){
		allMoney += salesOne;
	}
	if(salesTwo != "" ){
		allMoney += salesTwo;
	}
	if(salesThree != "" ){
		allMoney += salesThree;
	}
	if(salesFour != "" ){
		allMoney += salesFour;
	}
	$('#salesAll').val(allMoney);
	var viewMoney = moneyFormat2(allMoney);
	$('#salesAll_v').val(viewMoney);
}

/**
 * 服务发票金额计算
 */
function countService(){
	var allMoney = 0;
	var serviceOne = $('#serviceOne').val() * 1;
	var serviceTwo = $('#serviceTwo').val() * 1;
	var serviceThree = $('#serviceThree').val() * 1;
	var serviceFour = $('#serviceFour').val() * 1;
	if(serviceOne != "" ){
		allMoney += serviceOne;
	}
	if(serviceTwo != "" ){
		allMoney += serviceTwo;
	}
	if(serviceThree != "" ){
		allMoney += serviceThree;
	}
	if(serviceFour != "" ){
		allMoney += serviceFour;
	}
	$('#serviceAll').val(allMoney);
	var viewMoney = moneyFormat2(allMoney);
	$('#serviceAll_v').val(viewMoney);
}

$(function(){
	$("#year").yxcombogrid_invoiceYearPlan({
		gridOptions : {
			event : {
				'row_dblclick' : function(e, row, data) {
					$("#year").val(data.year);
					$("#salesOne").val(data.salesOne);
					$("#salesOne_v").val(moneyFormat2(data.salesOne));
					$("#salesTwo").val(data.salesTwo);
					$("#salesTwo_v").val(moneyFormat2(data.salesTwo));
					$("#salesThree").val(data.salesThree);
					$("#salesThree_v").val(moneyFormat2(data.salesThree));
					$("#salesFour").val(data.salesFour);
					$("#salesFour_v").val(moneyFormat2(data.salesFour));
					$("#salesAll").val(data.salesAll);
					$("#salesAll_v").val(moneyFormat2(data.salesAll));

					$("#serviceOne").val(data.serviceOne);
					$("#serviceOne_v").val(moneyFormat2(data.serviceOne));
					$("#serviceTwo").val(data.serviceTwo);
					$("#serviceTwo_v").val(moneyFormat2(data.serviceTwo));
					$("#serviceThree").val(data.serviceThree);
					$("#serviceThree_v").val(moneyFormat2(data.serviceThree));
					$("#serviceFour").val(data.serviceFour);
					$("#serviceFour_v").val(moneyFormat2(data.serviceFour));
					$("#serviceAll").val(data.serviceAll);
					$("#serviceAll_v").val(moneyFormat2(data.serviceAll));
				}
			}
		}
	});
});
