$(function(){
	$("select.myExecute").bind("change",function(){
		var selvalue = $(this).val();
		var hidevalue = $(this).next().val();
		var hidevale2 = $(this).next().next().val();
		var taskId = $(this).next().next().next().val();
		var spId = $(this).next().next().next().next().val();
		var hwapplyNumb = $(this).next().next().next().next().next().val();
		var skeyStr="&skey="+$("#checkkey"+hidevalue).val();
		switch(selvalue){
			//��д�ɹ�������
			case "purchaseform": window.open('?model=purchase_arrival_arrival&action=toAddByContract&applyId='+hidevalue+'&applyCode='+hidevale2+skeyStr+'&storageType=ARRIVALTYPE1','����������','height=500, width=1000, top='+(screen.height-500)/2+', left='+(screen.width-1000)/2+', toolbar=no, menubar=no, scrollbars=no, resizable=yes,location=no, status=no');break;
			//��д�������뵥
//			case "payform": showOpenWin('?model=finance_payablesapply_payablesapply&action=toAddInPurcont&id='+hidevalue+skeyStr );break;
			case "payform":
				$.ajax({
				    type: "POST",
				    url: "?model=purchase_contract_purchasecontract&action=canPayapply",
				    data: { "id" : hidevalue},
				    async: false,
				    success: function(data){
				    	if(data == 'hasBack'){
							alert('��������δ������ɵ��˿���������븶��');
							return false;
				    	}else if(data == 'Yes'){ //������Լ�������
							showModalWin('?model=finance_payablesapply_payablesapply&action=toAddforObjType&addType=push&isAdvPay=0&objType=YFRK-01&objId='+hidevalue+skeyStr );
						}else if( data == 'No'){ //������ܼ�������
							alert('�Ѵ�����������,���ܼ�������');
							return false;
						}else if(data == -1){
							if(confirm('������δ����,ȷ��Ҫ������?')){
								showModalWin('?model=finance_payablesapply_payablesapply&action=toAddforObjType&addType=push&objType=YFRK-01&isAdvPay=1&objId='+hidevalue+skeyStr );
							}
						}else{
							if(confirm('���븶�����޻��� ' + data + ' �죬ȷ��Ҫ���븶��ô��')){
								showModalWin('?model=finance_payablesapply_payablesapply&action=toAddforObjType&addType=push&objType=YFRK-01&isAdvPay=1&objId='+hidevalue+skeyStr );
							}
						}
					}
				});
				break;
			case "paybackform":
				$.ajax({
				    type: "POST",
				    url: "?model=purchase_contract_purchasecontract&action=canPayapplyBack",
				    data: { "id" : hidevalue},
				    async: false,
				    success: function(data){
				    	if(data == 'hasBack'){
							alert('��������δ������ɵĸ������룬���������˿�');
							return false;
				    	}else if(data*1 == '0'){
							alert('�������Ѹ�������������˿�');
							return false;
				    	}else if(data*1 == -1){
							alert('�����˿����������������ܼ�������');
							return false;
				    	}else{
							showModalWin('?model=finance_payablesapply_payablesapply&action=toAddforObjType&payFor=FKLX-03&addType=push&objType=YFRK-01&isAdvPay=0&objId='+hidevalue+skeyStr );
				    	}
					}
				});
				break;
			//�����ɹ�����
			case "export": location='index1.php?model=purchase_contract_purchasecontract&action=exportPurchaseOrder&id='+hidevalue+skeyStr;break;
			//�鿴
			case "view" : showOpenWin('?model=purchase_contract_purchasecontract&action=toTabRead&id='+hidevalue+"&applyNumb="+hidevale2+skeyStr);break;
			//�����ϴ�;
			case "uploadFile" : showThickboxWin('?model=purchase_contract_purchasecontract&action=toUploadFile&id='+hidevalue+ "&formType=blue&placeValuesBefore&TB_iframe=true&modal=false&height=300&width=800");break;
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
			//�鿴����;
			case "viewapproval" : showThickboxWin('controller/common/readview.php?itemtype=oa_purch_apply_basic&pid=' + hidevalue+ "&formType=blue&placeValuesBefore&TB_iframe=true&modal=false&height=200&width=800");break;
			//¼�뷢Ʊ
			case "entryInvoice": showOpenWin('?model=finance_invpurchase_invpurchase&action=toAddInPurCont&applyId='+hidevalue+skeyStr );break;
			//¼��ɹ���Ʊ
			case "entryPurchInvoice": showOpenWin('?model=finance_invpurchase_invpurchase&action=toAddInPurCont&InvoiceType=assetsPurchase&applyId='+hidevalue+skeyStr );break;
			//���
			case "change": parent.location='?model=purchase_contract_purchasecontract&action=toChange&id='+hidevalue+skeyStr;break;
			//���
			case "finish": if(confirm('ȷ�������')){location='index1.php?model=purchase_contract_purchasecontract&action=finishPurchaseContract&id='+hidevalue+'&contNumber='+hidevale2};break;
			//��ֹ
			case "close":parent.location='index1.php?model=purchase_contract_purchasecontract&action=toClose&id='+hidevalue+'&hwapplyNumb='+hwapplyNumb;break;
			//�ر�
			case "closeOrder":parent.location='index1.php?model=purchase_contract_purchasecontract&action=toCloseOrder&id='+hidevalue+'&hwapplyNumb='+hwapplyNumb;break;
			case "store":showOpenWin('?model=stock_instock_stockin&action=toPush&orderId='+hidevalue +'&relDocType=RCGDD&docType=RKPURCHASE'+skeyStr);break;
			//�ʲ��ɹ��������յ�
			case "toreceive":showThickboxWin("index1.php?model=asset_purchase_receive_receive&action=toPurchaseContractAdd&purchaseContractId="+hidevalue+"&code="+hwapplyNumb+"&placeValuesBefore&TB_iframe=true&modal=false&height=500&width=900");
			default : break;
		}
		$(this).val("");
	})

	$.each($("#states"),function(){

		var state=$("#state").val();
		alert(state);
	})
	/**
	 * ��ʼ��ʱʱ�������
	 */
	$.each($("table[id^='table']"),function(){
		$(this).hide();
	})

	/**
	 * �󶨵���������ͼƬ
	 */
	var thistitle;
	$("img[id^='changeTab']").bind("click",function(){
		var thistitle = $(this).attr("title");
		if($(this).attr("src") == "images/collapsed.gif"){
			$("#table" + thistitle).show();
			$("#inputDiv" + thistitle).hide();
			$(this).attr("src","images/expanded.gif");
		}else{
			$("#table" + thistitle).hide();
			$("#inputDiv" + thistitle).show();
			$(this).attr("src","images/collapsed.gif");
		}
	})

	/**
	 * ������������ͼƬ
	 */
	var thissrc ;
	$("#changeImage").bind("click",function(){
		thissrc = $(this).attr("src");
		if($(this).attr("src")=="images/collapsed.gif"){
			$(this).attr("src","images/expanded.gif");
		}else{
			$(this).attr("src","images/collapsed.gif");
		}
		$.each($("img[id^='changeTab']"),function(i,n){
			if($(this).attr("src")==thissrc)
				$(this).trigger("click");
		})
	})

	/**
	 * ��DIV
	 */
	var imgId ;
	$("div[id^='inputDiv']").bind("click",function(){
		imgId = $(this).attr("title");
		$("#changeTab" + imgId).trigger("click");
		$(this).hide();
	})
});