

/** *****************Òþ²Ø¼Æ»®******************************* */
function dis(name) {
	var temp = document.getElementById(name);
	if (temp.style.display == '')
		temp.style.display = "none";
	else if (temp.style.display == "none")
		temp.style.display = '';
}


$(function() {
//	dis('payApply');
//	dis('payed');
//	dis('invoice');
	if($('#readType').val()!=""){
		$('#closeButton').hide();
	}
	
	if ($("#hideBtn").val() == 1) {
		$("#closeButton").hide();
	}
});