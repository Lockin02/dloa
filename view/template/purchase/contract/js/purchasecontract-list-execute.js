$(function() {
	$("select.myExecute").bind("change", function() {

		var selvalue = $(this).val();
		var hidevalue = $(this).next().val();
		var hidevale2 = $(this).next().next().val();
		var taskId = $(this).next().next().next().val();
		var spId = $(this).next().next().next().next().val();
		var skeyStr="&skey="+$("#checkkey"+hidevalue).val();
		switch(selvalue){
			//填写采购到货单
			case "purchaseform": window.open('?model=purchase_arrival_arrival&action=toAddByContract&applyId='+hidevalue+skeyStr+'&applyCode='+hidevale2+'&storageType=ARRIVALTYPE1','新增到货单','height=500, width=1000, top='+(screen.height-500)/2+', left='+(screen.width-1000)/2+', toolbar=no, menubar=no, scrollbars=no, resizable=no,location=no, status=no');break;
			//填写付款申请单
			case "payform": showModalWin('?model=finance_payablesapply_payablesapply&action=toAddInPurcont&id='+hidevalue+skeyStr ,1 );break;
			//导出采购订单
			case "export": location='index1.php?model=purchase_contract_purchasecontract&action=exportPurchaseOrder&id='+hidevalue+skeyStr;break;
			//查看
			case "view" : showOpenWin('?model=purchase_contract_purchasecontract&action=toTabRead&id='+hidevalue+"&applyNumb="+hidevale2+skeyStr);break;
			//附件上传;
			case "uploadFile" : showThickboxWin('?model=purchase_contract_purchasecontract&action=toUploadFile&id='+hidevalue+ "&formType=blue&placeValuesBefore&TB_iframe=true&modal=false&height=300&width=800");break;
			//查看审批;
			case "viewapproval" : showThickboxWin('controller/common/readview.php?itemtype=oa_purch_apply_basic&pid=' + hidevalue+ "&formType=blue&placeValuesBefore&TB_iframe=true&modal=false&height=200&width=800");break;
			//录入发票
			case "entryInvoice": showModalWin('?model=finance_invpurchase_invpurchase&action=toAddInPurCont&applyId='+hidevalue+skeyStr ,1);break;
			//变更
			case "change": location='?model=purchase_contract_purchasecontract&action=toChange&id='+hidevalue+skeyStr;break;
			//完成
			case "finish": if(confirm('确定完成吗？')){location='index1.php?model=purchase_contract_purchasecontract&action=finishPurchaseContract&id='+hidevalue+'&contNumber='+hidevale2};break;
			//关闭
			case "close": if(confirm('确定中止吗？')){location='index1.php?model=purchase_contract_purchasecontract&action=close&id='+hidevalue+'&contNumber='+hidevale2};break;
			//下推外购入库单
			case "store":showOpenWin('?model=stock_instock_stockin&action=toPush&orderId='+hidevalue +'&relDocType=RCGDD&docType=RKPURCHASE'+skeyStr);break;
			default : break;

		}
		$(this).val("");
	})
	/**
	 * 初始化时时表格隐藏
	 */
	$.each($("table[id^='table']"), function() {
				$(this).hide();
			})

	/**
	 * 绑定单体伸缩的图片
	 */
	var thistitle;
	$("img[id^='changeTab']").bind("click", function() {
				var thistitle = $(this).attr("title");
				if ($(this).attr("src") == "images/collapsed.gif") {
					$("#table" + thistitle).show();
					$("#inputDiv" + thistitle).hide();
					$(this).attr("src", "images/expanded.gif");
				} else {
					$("#table" + thistitle).hide();
					$("#inputDiv" + thistitle).show();
					$(this).attr("src", "images/collapsed.gif");
				}
			})

	/**
	 * 绑定批量伸缩的图片
	 */
	var thissrc;
	$("#changeImage").bind("click", function() {
				thissrc = $(this).attr("src");
				if ($(this).attr("src") == "images/collapsed.gif") {
					$(this).attr("src", "images/expanded.gif");
				} else {
					$(this).attr("src", "images/collapsed.gif");
				}
				$.each($("img[id^='changeTab']"), function(i, n) {
							if ($(this).attr("src") == thissrc)
								$(this).trigger("click");
						})
			})

	/**
	 * 绑定DIV
	 */
	var imgId;
	$("div[id^='inputDiv']").bind("click", function() {
				imgId = $(this).attr("title");
				$("#changeTab" + imgId).trigger("click");
				$(this).hide();
			})
});