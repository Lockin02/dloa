$(function() {
	$("select.myExecute").bind("change", function() {

		var selvalue = $(this).val();
		var hidevalue = $(this).next().val();
		var hidevale2 = $(this).next().next().val();
		var taskId = $(this).next().next().next().val();
		var spId = $(this).next().next().next().next().val();
		var skeyStr="&skey="+$("#checkkey"+hidevalue).val();
		switch(selvalue){
			//��д�ɹ�������
			case "purchaseform": window.open('?model=purchase_arrival_arrival&action=toAddByContract&applyId='+hidevalue+skeyStr+'&applyCode='+hidevale2+'&storageType=ARRIVALTYPE1','����������','height=500, width=1000, top='+(screen.height-500)/2+', left='+(screen.width-1000)/2+', toolbar=no, menubar=no, scrollbars=no, resizable=no,location=no, status=no');break;
			//��д�������뵥
			case "payform": showModalWin('?model=finance_payablesapply_payablesapply&action=toAddInPurcont&id='+hidevalue+skeyStr ,1 );break;
			//�����ɹ�����
			case "export": location='index1.php?model=purchase_contract_purchasecontract&action=exportPurchaseOrder&id='+hidevalue+skeyStr;break;
			//�鿴
			case "view" : showOpenWin('?model=purchase_contract_purchasecontract&action=toTabRead&id='+hidevalue+"&applyNumb="+hidevale2+skeyStr);break;
			//�����ϴ�;
			case "uploadFile" : showThickboxWin('?model=purchase_contract_purchasecontract&action=toUploadFile&id='+hidevalue+ "&formType=blue&placeValuesBefore&TB_iframe=true&modal=false&height=300&width=800");break;
			//�鿴����;
			case "viewapproval" : showThickboxWin('controller/common/readview.php?itemtype=oa_purch_apply_basic&pid=' + hidevalue+ "&formType=blue&placeValuesBefore&TB_iframe=true&modal=false&height=200&width=800");break;
			//¼�뷢Ʊ
			case "entryInvoice": showModalWin('?model=finance_invpurchase_invpurchase&action=toAddInPurCont&applyId='+hidevalue+skeyStr ,1);break;
			//���
			case "change": location='?model=purchase_contract_purchasecontract&action=toChange&id='+hidevalue+skeyStr;break;
			//���
			case "finish": if(confirm('ȷ�������')){location='index1.php?model=purchase_contract_purchasecontract&action=finishPurchaseContract&id='+hidevalue+'&contNumber='+hidevale2};break;
			//�ر�
			case "close": if(confirm('ȷ����ֹ��')){location='index1.php?model=purchase_contract_purchasecontract&action=close&id='+hidevalue+'&contNumber='+hidevale2};break;
			//�����⹺��ⵥ
			case "store":showOpenWin('?model=stock_instock_stockin&action=toPush&orderId='+hidevalue +'&relDocType=RCGDD&docType=RKPURCHASE'+skeyStr);break;
			default : break;

		}
		$(this).val("");
	})
	/**
	 * ��ʼ��ʱʱ�������
	 */
	$.each($("table[id^='table']"), function() {
				$(this).hide();
			})

	/**
	 * �󶨵���������ͼƬ
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
	 * ������������ͼƬ
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
	 * ��DIV
	 */
	var imgId;
	$("div[id^='inputDiv']").bind("click", function() {
				imgId = $(this).attr("title");
				$("#changeTab" + imgId).trigger("click");
				$(this).hide();
			})
});