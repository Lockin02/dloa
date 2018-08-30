$(function(){
	$("select.close").bind("change",function(){
		var selvalue = $(this).val();
		var hidevalue = $(this).next().val();
		var hidevale2 = $(this).next().next().val();
		var taskId = $(this).next().next().next().val();
		var spId = $(this).next().next().next().next().val();
		var skeyStr="&skey="+$("#checkkey"+hidevalue).val();
		switch(selvalue){
			//查看详情
			case "view": showOpenWin('?model=purchase_contract_purchasecontract&action=toTabRead&id='+hidevalue+skeyStr);break;
			//导出采购订单
			case "export": location='index1.php?model=purchase_contract_purchasecontract&action=exportPurchaseOrder&id='+hidevalue+skeyStr;break;
			//申请盖章;
			case "seal" :
				 $.ajax({//判断是否已进行考核
				    type: "POST",
				    url: "?model=purchase_contract_purchasecontract&action=isApplySeal",
				    data: { id:hidevalue
				    	},
				    async: false,
				    success: function(msg){
				   	   if(msg==1){
				   	         showThickboxWin('?model=purchase_contract_purchasecontract&action=toApplySeal&id='+hidevalue+ "&formType=blue&placeValuesBefore&TB_iframe=true&modal=false&height=300&width=800");
				   	   }else if(msg==2){
					   	   	if(confirm('该采购订单已盖章,是否继续申请')){
								showThickboxWin('?model=purchase_contract_purchasecontract&action=toApplySeal&id='+hidevalue+ "&formType=blue&placeValuesBefore&TB_iframe=true&modal=false&height=300&width=800");
					   	   	}
				   	   }else {
					   	   alert("该采购订单的盖章申请尚未完成盖章");
				   	   }
					}
				});
				break;
			//附件上传;
			case "uploadFile" : showThickboxWin('?model=purchase_contract_purchasecontract&action=toUploadFile&id='+hidevalue+ "&formType=blue&placeValuesBefore&TB_iframe=true&modal=false&height=300&width=800");break;
			default : break;
		}
		$(this).val("");
	})
});