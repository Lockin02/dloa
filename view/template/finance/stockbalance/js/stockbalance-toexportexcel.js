$(function(){
	//财务期下拉列表
	var responseText = $.ajax({
		url:'?model=finance_period_period&action=getAllPeriod',
		type : "POST",
		async : false
	}).responseText;
	var periodArr = eval("(" + responseText + ")");
	var thisDate = $("#thisDateVal").val();
	for (var i = 0; i < periodArr.length; i++) {
		if(thisDate == periodArr[i].value){
			$("#thisDate").append("<option title='" + periodArr[i].text
					+ "' value='" + periodArr[i].value + "' selected>" + periodArr[i].text
					+ "</option>");
		}else{
			$("#thisDate").append("<option title='" + periodArr[i].text
					+ "' value='" + periodArr[i].value + "'>" + periodArr[i].text
					+ "</option>");
		}
	}
});