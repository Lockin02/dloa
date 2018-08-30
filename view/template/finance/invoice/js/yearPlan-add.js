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