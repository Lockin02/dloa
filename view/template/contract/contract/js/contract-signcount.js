$(function() {
	$.ajax({
				url : '?model=contract_contract_contract&action=countSignContract',
				success : function(data) {
					data = eval("(" + data + ")");
					var oneMonthNum = data['oneMonthNum'];
					var twoMonthNum = data['twoMonthNum'];
					var threeMonthNum = data['threeMonthNum'];
					$("#oneMonthNum").html(oneMonthNum);
					$("#twoMonthNum").html(twoMonthNum);
					$("#threeMonthNum").html(threeMonthNum);
				}
			});
	if (parent.parent.openTab) {
		var openTab = parent.parent.openTab;
	}
	$("#oneMonthNumHref").click(function() {
		openTab("index1.php?model=contract_contract_contract&signOneMonth=1",
				"ǩԼһ����");
	});
	$("#twoMonthNumHref").click(function() {
		openTab("index1.php?model=contract_contract_contract&signTwoMonth=1",
				"ǩԼ������");
	});
	$("#threeMonthNumHref").click(function() {
		openTab("index1.php?model=contract_contract_contract&signThreeMonth=1",
				"ǩԼ������");
	});
});