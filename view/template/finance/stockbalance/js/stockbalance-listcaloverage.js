//�첽��������
function ajaxSave(){
	var thisDate = getDataFun();
	if(thisDate != false){
		$.ajax({
			type : "POST",
			url : "?model=finance_stockbalance_stockbalance&action=overageCal",
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


//��ȡ�������json����
function getDataFun(){
	var tempProductId = '';   //��ʱ��Ʒid
	var tempSubPrice = 0;     //��ʱ�ܽ��
	var tempprice = 0;  //����
	var tempOldSubPrice = 0;  //ԭʼ���

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
			dataJson[i] = {
				"productId" : tempProductId ,
				"subPrice" : tempSubPrice ,
				"price" : tempprice
			};
		}
	}

	if(j != 0){
		return dataJson;
	}else{
		return false;
	}
}