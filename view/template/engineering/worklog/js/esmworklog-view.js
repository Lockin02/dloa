$(function() {
	if($("#workProcess").val()*1 == -1){
		$("#workProcessShow").html('--');
	}

	//��Ⱦ������Ϣ
	var invbodyObj = $("#invbody");
	if(invbodyObj.length > 0){
		//��������
		if(invbodyObj.html() != ""){
			showAndHide('feeImg','feeTbl');
		}else{
			invbodyObj.html("<tr><td colspan='8'>---- û�з�����Ϣ ----</td></tr>");
		}
	}

	//����title
	initAmountTitle($("#feeRegular").val(),$("#feeSubsidy").val());
});