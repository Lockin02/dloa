$(function() {
	if($("#workProcess").val()*1 == -1){
		$("#workProcessShow").html('--');
	}

	//渲染费用信息
	var invbodyObj = $("#invbody");
	if(invbodyObj.length > 0){
		//费用类型
		if(invbodyObj.html() != ""){
			showAndHide('feeImg','feeTbl');
		}else{
			invbodyObj.html("<tr><td colspan='8'>---- 没有费用信息 ----</td></tr>");
		}
	}

	//设置title
	initAmountTitle($("#feeRegular").val(),$("#feeSubsidy").val());
});