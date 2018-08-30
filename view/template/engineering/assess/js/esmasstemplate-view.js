$(document).ready(function() {
	var indexNamesArr = $("#indexNames").val().split(',');
	var needIndexNames = $("#needIndexNames").val();
	var needIndexNamesArr = needIndexNames.split(',');
	var rtVal = "";
	//
	for(var i = 0; i < indexNamesArr.length ;i++){
		if(jQuery.inArray(indexNamesArr[i], needIndexNamesArr) != -1){
			if(i == 0){
				rtVal = "<span class='blue' title='必选指标'>" + indexNamesArr[i] + "</span>";
			}else{
				rtVal += ",<span class='blue' title='必选指标'>" + indexNamesArr[i] + "</span>";
			}
		}else{
			if(i == 0){
				rtVal = indexNamesArr[i];
			}else{
				rtVal += "," + indexNamesArr[i];
			}
		}
	}
	$("#showIndex").html(rtVal);
});