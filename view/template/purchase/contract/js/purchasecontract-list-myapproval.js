$(function(){
	$("select.myUnExecute").bind("change",function(){
		var selvalue = $(this).val();
		var hidevalue = $(this).next().val();
		var hidevale2 = $(this).next().next().val();
		var taskId = $(this).next().next().next().val();
		var spId = $(this).next().next().next().next().val();
		var skeyStr="&skey="+$("#checkkey"+hidevalue).val();
		switch(selvalue){
			//�鿴����
			case "viewinfo": showOpenWin('?model=purchase_contract_purchasecontract&action=toReadTab&id='+hidevalue+skeyStr);break;
			//�����ɹ�����
			case "export": location='index1.php?model=purchase_contract_purchasecontract&action=exportPurchaseOrder&id='+hidevalue+skeyStr;break;
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
			//�鿴����
//			case "viewapproval": location='controller/common/readview.php?itemtype=oa_purch_apply_basic&pid='+hidevalue;break;
			case "viewapproval" : showThickboxWin('controller/common/readview.php?itemtype=oa_purch_apply_basic&pid=' + hidevalue + "&formType=blue&placeValuesBefore&TB_iframe=true&modal=false&height=200&width=800");break;
			//�ύ����
			case "cancel":
				if(confirm('ȷ��Ҫ����������')){
					$.ajax({
						type : "POST",
						url : "?model=common_workflow_workflow&action=isAuditedContract",
						data : {
							billId :hidevalue,
							examCode : 'oa_purch_apply_basic'
						},
						success : function(msg) {
							if (msg == '1') {
								alert('�����Ѿ�����������Ϣ�����ܳ���������');
								return false;
							} else {
								location='index1.php?model=purchase_contract_purchasecontract&action=cancelApproval&id='+hidevalue;
							}
						}
					});
				}
				break;
			case "":break;
			default : break;
		}
		$(this).val("");
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