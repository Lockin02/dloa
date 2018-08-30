//≤È—Ø
function search(){
	var html = getDisplayViewerHtml2("100%", "92%",
		"view/template/stock/report/stockreport-productstockchange.grf",
		"view/template/stock/report/stockreport-productstockchange.php"
		+ "?thisYear1=" + $("#thisYear1").val()
		+ "&thisMonth1=" + $("#thisMonth1").val()
		+ "&thisYear2=" + $("#thisYear2").val()
		+ "&thisMonth2=" + $("#thisMonth2").val()
		+ "&productNo=" + $("#productNo").val()
		,
		true,
		"<param name='OnContentCellDblClick' value='OnContentCellDblClick'>");
	$("#reportDiv").empty().html(html);
}