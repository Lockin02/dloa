hookedArr = new Array();

$(function(){
	var ids = $("#ids").val();
	hookedArr = ids.split(",");
	for(var i = 0 ;i < hookedArr.length ; i++){
		$("#" + hookedArr[i]).attr("checked",true);
	}
})

//�첽��������
function ajaxSave(){
	var thisDate = getDataFun();
	if(thisDate != false){
		$.ajax({
			type : "POST",
			url : "?model=finance_carriedforward_carriedforward&action=carryOutStock",
			data : {
				"data" : thisDate
			},
			success : function(msg) {
				msg = strTrim(msg);
				if (msg == 0) {
					alert("����ʧ��");
				}else if(msg != 'none'){
					alert('�����ɹ�');
					hookedArr = msg.split(",");
					$("#ids").val(msg);
				}else{
					alert('�����ɹ�');
					hookedArr = [];
					$("#ids").val('');
				}
			}
		});
	}else{
		alert('û��ѡ��ֵ');
		return false;
	}
}


//��ȡ�������json����
function getDataFun(){
	//��ѡ������ַ���
	dataStr = "";

	//δѡ������ַ���
	unDataStr = "";

	//��ʱ�洢����
	var tempKey = "";

	$(":checkbox[name='outStock']").each(function(i,n){
		tempKey = $(this).val();

		if($(this).attr("checked") == true){
			if(hookedArr.indexOf(tempKey) == -1){
				if(dataStr == ""){
					dataStr =  tempKey;
				}else{
					dataStr += "," + tempKey;
				}
			}
		}else{
			if(hookedArr.indexOf(tempKey) != -1){
				if(unDataStr == ""){
					unDataStr =  tempKey;
				}else{
					unDataStr += "," + tempKey;
				}
			}
		}
	});

	if(dataStr == "" && unDataStr == ""){
		return false;
	}else{
		dataJson = {
			"dataStr" : dataStr ,
			"unDataStr" : unDataStr,
			"customerId" : $("#customerId").val(),
			"thisYear" : $("#thisYear").val(),
			"thisMonth" : $("#thisMonth").val()
		};
		return dataJson;
	}
}

//�򿪲鿴���۳���
//param1 ���ⵥid
//param2 ���ܲ���
function viewOutStock(outstockId,skeyValue){
	var url = '?model=stock_outstock_stockout&action=toView&id='
				+ outstockId
				+ "&docType=CKSALES"
				+ "&skey="
				+ skeyValue
	showOpenWin(url,1);
}

//��ӡ����
function printOutstock(outstockId,skeyValue){
	var url = '?model=stock_outstock_stockout&action=toPrintForCarry&id='
		+ outstockId
		+ "&docType=CKSALES"
		+ "&skey="
		+ skeyValue;
	showOpenWin(url,1);
}