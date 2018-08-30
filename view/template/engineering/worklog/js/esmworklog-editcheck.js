$(function() {
	if($("#workProcess").val()*1 == -1){
		$("#workProcessShow").html('--').attr('title','本日志为延期填报，只参与计算工作进度');
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

	//初始化选中行
	initCostDetail();

	//初始化发票类型
	billTypeArr = getBillType();

});

//初始化选中行
function initCostDetail(){
	var costdetailIdObj = $("#costdetailId");
	if(costdetailIdObj.length == 1){

		var costdetailArr = costdetailIdObj.val().split(',');
		for(var i =0;i < costdetailArr.length ; i++ ){
			$(".tr" + costdetailArr[i]).css('color','blue').attr('title','本次报销费用项');
		}
	}
}