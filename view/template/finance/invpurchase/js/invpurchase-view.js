$(function(){
	countAll();
});

//����鿴�⹺��ⵥ
function toLoca(){
	var sourcetType = $("#sourceType").val();
	if(sourcetType == 'CGFPYD-02'){
		sourcetType = 'RKPURCHASE';
	}
	url = '?model=stock_instock_stockin&action=viewByDocCode'
			+ '&docType=' + sourcetType
			+ '&docCode=' + $("#menuNo").val()
	;
	showModalWin(url,1);
}


//��ȡѡ�񵥾�ȥ��
function toSource(objId,objType){
	switch(objType){
		case 'CGFPYD-01': toPur(objId);break;
		case 'CGFPYD-02': toStockIn(objId);break;
		default : alert('δ���õĶ�������');
	}
}

//�����⹺��ⵥ
function toStockIn(objId){
    var skey = "";
    $.ajax({
	    type: "POST",
	    url: "?model=stock_instock_stockin&action=md5RowAjax",
	    data: {"id" : objId},
	    async: false,
	    success: function(data){
	   	   skey = data;
		}
	});
	var url = "index1.php?model=stock_instock_stockin&action=toView&id="+objId+"&docType=RKPURCHASE&skey=" + skey;
	showModalWin(url,1);
}

//����ɹ�����
function toPur(objId){
	var skey = "";
    $.ajax({
	    type: "POST",
	    url: "?model=purchase_contract_purchasecontract&action=md5RowAjax",
	    data: {"id" : objId},
	    async: false,
	    success: function(data){
	   	   skey = data;
		}
	});
	var url = '?model=purchase_contract_purchasecontract&action=toTabRead'
			+ '&id=' + objId
			+ '&skey=' + skey
	;
	showModalWin(url,1);
}




function countAll(){
	//�����嵥��¼��
	var $invnumber = $('#invnumber').val();
	//��ǰ���ܽ��
	var  thisAmount = 0;
	//��ǰ��˰��
	var thisAssessment = 0;
	//��ǰ�н��(����˰)
	var thisAllCount = 0;

	//�����ܽ��(����˰)
	var allAmount = 0;
	//����˰�ܽ��
	var allCountAll = 0;
	//������˰��
	var allAssessment = 0;
	//����������
	var allNumber = 0;

	for(var i = 1;i <= $invnumber;i++){
		//�жϽ���Ƿ����
		thisAmount = $('#amount' + i).val() * 1;

		thisNumber = $("#number"+i).val()*1;

		thisAssessment = $("#assessment"+i).val()*1;

		thisAllCount = $("#allCount"+i).val()*1;

		allAssessment = accAdd(allAssessment,thisAssessment,4);

		allCountAll = accAdd(allCountAll,thisAllCount,4);

		allAmount = accAdd(allAmount,thisAmount,4);

		allNumber = accAdd(allNumber,thisNumber);
	}

	//���ݲ���˰�ܽ��
	$('#amountAll').html(moneyFormat2(allAmount));

	//���ݺ�˰�ܽ��
	$('#allCountAll').html(moneyFormat2(allCountAll));

	//����˰��
	$('#assessmentAll').html(moneyFormat2(allAssessment));

	//����
	$('#numberAll').html(moneyFormat2(allNumber,0));

	//
	if(allCountAll < 0){

		view_formAssessment = $('#view_formAssessment').html();
		if(view_formAssessment*1 != 0){
			view_formAssessment = '-' + view_formAssessment;
			$('#view_formAssessment').html(view_formAssessment);
		}


		view_amount = "-" + $('#view_amount').html();
		$('#view_amount').html(view_amount);

		view_formCount = "-" +  $('#view_formCount').html();
		$('#view_formCount').html(view_formCount);
	}
}