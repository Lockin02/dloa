$(function() {
	$.ajax({
		url : '?model=contract_contract_contract&action=countContract',
		success : function(data) {
			data = eval("(" + data + ")");
			var lastAddNum = data['lastAddNum'];
			var lastChangeNum = data['lastChangeNum'];
			$("#lastAddNum").html(lastAddNum);
			$("#lastChangeNum").html(lastChangeNum);
		}
	});
	if (parent.parent.openTab) {
		var openTab = parent.parent.openTab;
	}
	$("#lastAddNumHref").click(function() {
		openTab("index1.php?model=contract_contract_contract&lastAdd=1",
				"最近新增合同");
	});
	$("#lastChangeNumHref").click(function() {
		openTab("index1.php?model=contract_contract_contract&lastChange=1",
				"最近变更合同");
	});
});