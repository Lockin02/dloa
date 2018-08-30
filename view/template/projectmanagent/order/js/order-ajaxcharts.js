function updateChart() {
	var chartSWF = "FusionCharts/swf/Charts2/MSColumn2D.swf";
	var chart = new FusionCharts('FusionCharts/swf/ChartCrack.swf?chartUrl='
					+ chartSWF, "", "1150", "400", "0", "0");
	var strurl = "?model=fusionchars_fusionChars&action=fusionChars";
	// strurl = escape(strurl);
	$.ajax({
				url : strurl,
				param : {},// 参数从这里传
				success : function(data) {
					chart.setDataXML(data);
					chart.render("chart");
				}
			});
}

$(function() {
			updateChart();
			$("#searchButton").click(function() {
						var startTime = $("#startTime").val();
						var endTime = $("#endTime").val();
						updateChart();

					});
		});