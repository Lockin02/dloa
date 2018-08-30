$(function() {
	$("#reportType").change(function() {
		var v = $(this).val();
		var startTime = $("#startTime").val();
		var endTime = $("#endTime").val();
		document.location = "?model=projectmanagent_order_order&action=contractReport&reportType="
				+ v + "&startTime=" + startTime + "&endTime=" + endTime;
	});

	$("#reportType").val($("#reportType").attr("val"));

	$("#searchButton").click(function() {
		var reportType = $("#reportType").val();
		var startTime = $("#startTime").val();
		var endTime = $("#endTime").val();
		document.location = "?model=projectmanagent_order_order&action=contractReport&reportType="
				+ reportType
				+ "&startTime="
				+ startTime
				+ "&endTime="
				+ endTime;;
	});
});