$(function(){
	$("form").submit(function(){
		var payedMoney = $("#payedMoney").val();
		var returnMoney = $("#returnMoney").val();
		var invotherMoney = $("#invotherMoney").val();
		var orderMoney = $("#orderMoney").val();
		if(returnMoney*1 > payedMoney*1){
			alert('������¼����󣬷����� ' + moneyFormat2(returnMoney) + ' ���� ������ ' + moneyFormat2(payedMoney));
			return false;
		}

		if(returnMoney*1 > accSub(orderMoney,invotherMoney,2)*1){
			alert('������¼����󣬷����� ' + moneyFormat2(returnMoney) + ' ���� ʣ���¼��Ʊ��� ' + moneyFormat2(payedMoney));
			return false;
		}
	});
});