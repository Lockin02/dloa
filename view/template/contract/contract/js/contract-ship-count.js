$(function() {
	if (parent.parent.openTab) {
		var openTab = parent.parent.openTab;
	}
	$("#NotRunNumHref").click(function() {
		openTab(
				"index1.php?model=contract_contract_contract&action=shipments&DeliveryStatus='WFH'",
				"��������");
	});
	$("#RunningNumHref").click(function() {
		openTab(
				"index1.php?model=contract_contract_contract&action=shipments&DeliveryStatus='BFFH'",
				"��������");
	});
	$("#NotRunOutNumHref").click(function() {
		openTab("index1.php?model=stock_outplan_outplan&docType='WFH'", "�����ƻ�");
	});
	$("#runningOutNumHref").click(function() {
		openTab("index1.php?model=stock_outplan_outplan&docType='BFFH'", "�����ƻ�");
	});
});