$(function() {
	if (parent.parent.openTab) {
		var openTab = parent.parent.openTab;
	}
	$("#NotRunNumHref").click(function() {
		openTab(
				"index1.php?model=contract_contract_contract&action=shipments&DeliveryStatus='WFH'",
				"发货需求");
	});
	$("#RunningNumHref").click(function() {
		openTab(
				"index1.php?model=contract_contract_contract&action=shipments&DeliveryStatus='BFFH'",
				"发货需求");
	});
	$("#NotRunOutNumHref").click(function() {
		openTab("index1.php?model=stock_outplan_outplan&docType='WFH'", "发货计划");
	});
	$("#runningOutNumHref").click(function() {
		openTab("index1.php?model=stock_outplan_outplan&docType='BFFH'", "发货计划");
	});
});