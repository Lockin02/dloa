$(function(){
	$("select.myUnExecute").bind("change",function(){
		var selvalue = $(this).val();
		var hidevalue = $(this).next().val();
		var hidevale2 = $(this).next().next().val();
		switch(selvalue){
			//�鿴
			case "viewpurchase": location='index1.php?model=purchase_contract_purchasecontract&action=init&perm=view&id='+hidevalue+'&contNumber='+hidevale2;break;
			//�༭
			case "editpurchase": location='index1.php?model=purchase_contract_purchasecontract&action=init&id='+hidevalue;break;
			//����
			case "begin": location='index1.php?model=purchase_contract_purchasecontract&action=beginPurchaseContract&id='+hidevalue;break;
			//ɾ��
			case "delete": location='index1.php?model=purchase_contract_purchasecontract&action=deletes&id='+hidevalue+'&contNumber='+hidevale2;break;
			case "":break;
			default : break;
		}
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