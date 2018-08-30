//查询
function search(){
	//日期
	if($("#thisDate").val() == ""){
		alert('日期不能为空');
		return false;
	}
	var html = getDisplayViewerHtml2("100%", "92%",
		"view/template/stock/report/stockreport-productstockdetail.grf",
		"view/template/stock/report/stockreport-productstockdetail.php"
		+ "?thisDate=" + $("#thisDate").val()
		+ "&stock=" + $("#stock").val()
		+ "&productNo=" + $("#productNo").val()
        + "&moneyLimit=" + $("#moneyLimit").val()
		,
		true,
		"<param name='OnContentCellDblClick' value='OnContentCellDblClick'>");
	$("#reportDiv").empty().html(html);
}

//初始化仓库信息
$(function(){
	var responseText = $.ajax({
		url : 'index1.php?model=stock_stockinfo_stockinfo&action=listJson&dir=ASC',
		type : "GET",
		async : false
	}).responseText;

	var stockArr = eval("(" + responseText + ")");
	if(stockArr.length > 0){
		var stockStr = '';
		for (var i = 0, l = stockArr.length; i < l; i++) {
			stockStr += "<option value='" + stockArr[i].id + "'>" + stockArr[i].stockName
					+ "</option>";
		}
		$("#stock").append(stockStr);
	}
});