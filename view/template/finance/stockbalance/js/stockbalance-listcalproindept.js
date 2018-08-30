function show_page(){
	window.location.reload();
}

//�첽��������
function ajaxSave(){
	var thisDate = getDataFun();
	if(thisDate != false){
		$.ajax({
			type : "POST",
			url : "?model=finance_stockbalance_stockbalance&action=productInCal",
			data : {
				"data" : thisDate
			},
			success : function(msg) {
				if (msg == 1) {
					alert('����ɹ���');
				}else{
					alert("����ʧ��! ");
				}
			}
		});
	}else{
		alert('û���޸��κ�ֵ');
		return false;
	}
}

//��������
function excelOut(){
	url = "?model=finance_stockbalance_stockbalance&action=productInCalExcelOut"
		+ '&productId=' + $("#productId").val()
		+ '&productNoBegin=' + $("#productNoBegin").val()
		+ '&productNoEnd=' + $("#productNoEnd").val()
		+ '&checkType=' + $("#checkType").val()
		+ '&isGroupByDept=' + $("#isGroupByDept").val()
						;
	window.open(url,"", "width=200,height=200,top=200,left=200");
}

//��������
function excelIn(){
	showThickboxWin("?model=finance_stockbalance_stockbalance&action=toProductInCalExcelInDept"
          + "&placeValuesBefore&TB_iframe=true&modal=false&height=400&width=600")
}


/**
 * ����������� arg3 Ϊ��ʱֱ����� ��Ϊ��ʱ����id���
 */
function FloatDivSix(arg1, arg2, arg3) {
	var value1 = $('#' + arg1).val();
	var value2 = $('#' + arg2).val();
	var t1 = 0, t2 = 0, r1, r2;
	if (value1 != "" && value2 != "") {
		var thisVal = accDiv(value1, value2, 6);
		if (arg3 == "") {
			return thisVal;
		} else {
			var newReturnMoney = moneyFormat2(thisVal,6);
			$('#' + arg3).val(thisVal);
			$('#' + arg3 + '_v').val(newReturnMoney);
		}
	} else {
		return false;
	}
}


//��ȡ�������json����
function getDataFun(){
	var tempProductId = '';   //��ʱ��Ʒid
	var tempSubPrice = 0;     //��ʱ�ܽ��
	var tempprice = 0;  //����
	var tempOldSubPrice = 0;  //ԭʼ���
	var tempPurchaserCode = '';  //����

	var j = 0;

	var dataJson = {};

	//����
	var countNum = $("#countNum").val();

	//ѭ������json����
	for(var i = 1 ; i <= countNum ; i++){
		//�޸ĺ�Ľ��
		tempSubPrice = $('#subPrice' + i).val() * 1;
		//�޸�ǰ�Ľ��
		tempOldSubPrice = $('#oldSubPrice' + i).val() * 1;
		//��������,�����json������
		if( tempSubPrice != tempOldSubPrice ){
			j ++ ;
			tempProductId = $('#productId' + i).val();
			tempprice = $('#price' + i).val();
			tempPurchaserCode = $('#purchaserCode' + i).val();

			dataJson[i] = {
				"productId" : tempProductId ,
				"subPrice" : tempSubPrice ,
				"price" : tempprice,
				"purchaserCode" : tempPurchaserCode
			};
		}
	}

	if(j != 0){
		return dataJson;
	}else{
		return false;
	}
}