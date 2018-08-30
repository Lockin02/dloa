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
			//填写采购到货单
			case "purchaseform": window.open('?model=purchase_arrival_arrival&action=toAddByContract&applyId='+hidevalue+'&applyCode='+hidevale2+skeyStr+'&storageType=ARRIVALTYPE1','新增到货单','height=500, width=1000, top='+(screen.height-500)/2+', left='+(screen.width-1000)/2+', toolbar=no, menubar=no, scrollbars=no, resizable=yes,location=no, status=no');break;
			//填写付款申请单
//			case "payform": showOpenWin('?model=finance_payablesapply_payablesapply&action=toAddInPurcont&id='+hidevalue+skeyStr );break;
			case "payform":
				$.ajax({
				    type: "POST",
				    url: "?model=purchase_contract_purchasecontract&action=canPayapply",
				    data: { "id" : hidevalue},
				    async: false,
				    success: function(data){
				    	if(data == 'hasBack'){
							alert('订单存在未处理完成的退款单，不能申请付款');
							return false;
				    	}else if(data == 'Yes'){ //如果可以继续申请
							showModalWin('?model=finance_payablesapply_payablesapply&action=toAddforObjType&addType=push&isAdvPay=0&objType=YFRK-01&objId='+hidevalue+skeyStr );
						}else if( data == 'No'){ //如果不能继续申请
							alert('已达申请金额上限,不能继续申请');
							return false;
						}else if(data == -1){
							if(confirm('订单尚未到货,确定要付款吗?')){
								showModalWin('?model=finance_payablesapply_payablesapply&action=toAddforObjType&addType=push&objType=YFRK-01&isAdvPay=1&objId='+hidevalue+skeyStr );
							}
						}else{
							if(confirm('距离付款期限还有 ' + data + ' 天，确认要申请付款么？')){
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
							alert('订单存在未处理完成的付款申请，不能申请退款');
							return false;
				    	}else if(data*1 == '0'){
							alert('订单无已付款项，不能申请退款');
							return false;
				    	}else if(data*1 == -1){
							alert('订单退款申请金额已满，不能继续申请');
							return false;
				    	}else{
							showModalWin('?model=finance_payablesapply_payablesapply&action=toAddforObjType&payFor=FKLX-03&addType=push&objType=YFRK-01&isAdvPay=0&objId='+hidevalue+skeyStr );
				    	}
					}
				});
				break;
			//导出采购订单
			case "export": location='index1.php?model=purchase_contract_purchasecontract&action=exportPurchaseOrder&id='+hidevalue+skeyStr;break;
			//查看
			case "view" : showOpenWin('?model=purchase_contract_purchasecontract&action=toTabRead&id='+hidevalue+"&applyNumb="+hidevale2+skeyStr);break;
			//附件上传;
			case "uploadFile" : showThickboxWin('?model=purchase_contract_purchasecontract&action=toUploadFile&id='+hidevalue+ "&formType=blue&placeValuesBefore&TB_iframe=true&modal=false&height=300&width=800");break;
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
			//查看审批;
			case "viewapproval" : showThickboxWin('controller/common/readview.php?itemtype=oa_purch_apply_basic&pid=' + hidevalue+ "&formType=blue&placeValuesBefore&TB_iframe=true&modal=false&height=200&width=800");break;
			//录入发票
			case "entryInvoice": showOpenWin('?model=finance_invpurchase_invpurchase&action=toAddInPurCont&applyId='+hidevalue+skeyStr );break;
			//录入采购发票
			case "entryPurchInvoice": showOpenWin('?model=finance_invpurchase_invpurchase&action=toAddInPurCont&InvoiceType=assetsPurchase&applyId='+hidevalue+skeyStr );break;
			//变更
			case "change": parent.location='?model=purchase_contract_purchasecontract&action=toChange&id='+hidevalue+skeyStr;break;
			//完成
			case "finish": if(confirm('确定完成吗？')){location='index1.php?model=purchase_contract_purchasecontract&action=finishPurchaseContract&id='+hidevalue+'&contNumber='+hidevale2};break;
			//中止
			case "close":parent.location='index1.php?model=purchase_contract_purchasecontract&action=toClose&id='+hidevalue+'&hwapplyNumb='+hwapplyNumb;break;
			//关闭
			case "closeOrder":parent.location='index1.php?model=purchase_contract_purchasecontract&action=toCloseOrder&id='+hidevalue+'&hwapplyNumb='+hwapplyNumb;break;
			case "store":showOpenWin('?model=stock_instock_stockin&action=toPush&orderId='+hidevalue +'&relDocType=RCGDD&docType=RKPURCHASE'+skeyStr);break;
			//资产采购下推验收单
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
	 * 初始化时时表格隐藏
	 */
	$.each($("table[id^='table']"),function(){
		$(this).hide();
	})

	/**
	 * 绑定单体伸缩的图片
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
	 * 绑定批量伸缩的图片
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
	 * 绑定DIV
	 */
	var imgId ;
	$("div[id^='inputDiv']").bind("click",function(){
		imgId = $(this).attr("title");
		$("#changeTab" + imgId).trigger("click");
		$(this).hide();
	})
});