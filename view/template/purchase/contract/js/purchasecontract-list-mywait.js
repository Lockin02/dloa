$(function(){
	$("select.myUnExecute").bind("change",function(){
		var selvalue = $(this).val();
		var hidevalue = $(this).next().val();
		var hidevale2 = $(this).next().next().val();
		var taskId = $(this).next().next().next().val();
		var spId = $(this).next().next().next().next().val();
		var skeyStr="&skey="+$("#checkkey"+hidevalue).val();
		switch(selvalue){
			//�鿴
			case "viewpurchase": showOpenWin('?model=purchase_contract_purchasecontract&action=toReadTab&id='+hidevalue+skeyStr);break;
			//�༭
			case "editpurchase": parent.location='index1.php?model=purchase_contract_purchasecontract&action=init&id='+hidevalue+skeyStr;break;
			//�����ɹ�����
			case "export": location='index1.php?model=purchase_contract_purchasecontract&action=exportPurchaseOrder&id='+hidevalue+skeyStr;break;
			//�����ϴ�;
			case "uploadFile" : showThickboxWin('?model=purchase_contract_purchasecontract&action=toUploadFile&id='+hidevalue+ "&formType=blue&placeValuesBefore&TB_iframe=true&modal=false&height=300&width=800");break;
			//�ύ����
			case "approval": if(confirm('ȷ��Ҫ�ύ������'))parent.location='index1.php?model=purchase_contract_purchasecontract&action=approvalOrder&id='+hidevalue;break;
//			case "approval": if(confirm('ȷ��Ҫ�ύ������'))parent.location='controller/purchase/contract/ewf_index.php?actTo=ewfSelect&billId='+hidevalue+'&examCode=oa_purch_apply_basic&formName=�ɹ���ͬ����';break;
			//ɾ��
			case "delete": if(confirm('ȷ��Ҫɾ����')) location='index1.php?model=purchase_contract_purchasecontract&action=deletes&id='+hidevalue+'&contNumber='+hidevale2+skeyStr+'&formType=blue&placeValuesBefore&TB_iframe=true&modal=false&height=200&width=400';break;
			case "":break;
			default : break;
		}
		$(this).val("");
	});
	/**
	 * ��ʼ��ʱʱ�������
	 */
	$.each($("table[id^='table']"),function(){
		$(this).hide();
	});

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