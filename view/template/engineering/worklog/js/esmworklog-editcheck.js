$(function() {
	if($("#workProcess").val()*1 == -1){
		$("#workProcessShow").html('--').attr('title','����־Ϊ�������ֻ������㹤������');
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

	//��ʼ��ѡ����
	initCostDetail();

	//��ʼ����Ʊ����
	billTypeArr = getBillType();

});

//��ʼ��ѡ����
function initCostDetail(){
	var costdetailIdObj = $("#costdetailId");
	if(costdetailIdObj.length == 1){

		var costdetailArr = costdetailIdObj.val().split(',');
		for(var i =0;i < costdetailArr.length ; i++ ){
			$(".tr" + costdetailArr[i]).css('color','blue').attr('title','���α���������');
		}
	}
}