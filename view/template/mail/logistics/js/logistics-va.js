function checkform(){
	if($("#companyName").val() == ""){
		alert("�����빫˾����");
		return false;
	}
	return true;
}

$(function(){
	if($("#id").length > 0){
		setSelect('isDefault');
	}
});