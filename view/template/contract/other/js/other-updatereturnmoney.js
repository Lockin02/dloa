$(function(){
	$("form").submit(function(){
		var payedMoney = $("#payedMoney").val();
		var returnMoney = $("#returnMoney").val();
		var invotherMoney = $("#invotherMoney").val();
		var orderMoney = $("#orderMoney").val();
		if(returnMoney*1 > payedMoney*1){
			alert('返款金额录入错误，返款金额 ' + moneyFormat2(returnMoney) + ' 大于 付款金额 ' + moneyFormat2(payedMoney));
			return false;
		}

		if(returnMoney*1 > accSub(orderMoney,invotherMoney,2)*1){
			alert('返款金额录入错误，返款金额 ' + moneyFormat2(returnMoney) + ' 大于 剩余可录发票金额 ' + moneyFormat2(payedMoney));
			return false;
		}
	});
});