$(function(){
	$("select.close").bind("change",function(){
		var selvalue = $(this).val();
		var hidevalue = $(this).next().val();
		var hidevale2 = $(this).next().next().val();
		var taskId = $(this).next().next().next().val();
		var spId = $(this).next().next().next().next().val();
		var skeyStr="&skey="+$("#checkkey"+hidevalue).val();
		switch(selvalue){
			//�鿴����
			case "view": showOpenWin('?model=purchase_contract_purchasecontract&action=toTabRead&id='+hidevalue+skeyStr);break;
			//�����ɹ�����
			case "export": location='index1.php?model=purchase_contract_purchasecontract&action=exportPurchaseOrder&id='+hidevalue+skeyStr;break;
			//�������;
			case "seal" :
				 $.ajax({//�ж��Ƿ��ѽ��п���
				    type: "POST",
				    url: "?model=purchase_contract_purchasecontract&action=isApplySeal",
				    data: { id:hidevalue
				    	},
				    async: false,
				    success: function(msg){
				   	   if(msg==1){
				   	         showThickboxWin('?model=purchase_contract_purchasecontract&action=toApplySeal&id='+hidevalue+ "&formType=blue&placeValuesBefore&TB_iframe=true&modal=false&height=300&width=800");
				   	   }else if(msg==2){
					   	   	if(confirm('�òɹ������Ѹ���,�Ƿ��������')){
								showThickboxWin('?model=purchase_contract_purchasecontract&action=toApplySeal&id='+hidevalue+ "&formType=blue&placeValuesBefore&TB_iframe=true&modal=false&height=300&width=800");
					   	   	}
				   	   }else {
					   	   alert("�òɹ������ĸ���������δ��ɸ���");
				   	   }
					}
				});
				break;
			//�����ϴ�;
			case "uploadFile" : showThickboxWin('?model=purchase_contract_purchasecontract&action=toUploadFile&id='+hidevalue+ "&formType=blue&placeValuesBefore&TB_iframe=true&modal=false&height=300&width=800");break;
			default : break;
		}
		$(this).val("");
	})
});