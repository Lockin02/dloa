
/** *****************Òþ²Ø¼Æ»®******************************* */
function dis(name) {
	var temp = document.getElementById(name);
	if (temp.style.display == '')
		temp.style.display = "none";
	else if (temp.style.display == "none")
		temp.style.display = '';
}

$(function(){
	var shipType = $("#shipType").val();
	$.each($(":checkbox[@name=shipType]"),function(){
		if($(this).val() == shipType ){
			$(this).attr("checked",true);
		}
	})
});
