$(function(){
	$("select.myUnExecute").bind("change",function(){
		var selvalue = $(this).val();
		var hidevalue = $(this).next().val();
		var hidevale2 = $(this).next().next().val();
		var taskId = $(this).next().next().next().val();
		var spId = $(this).next().next().next().next().val();
		var skeyStr="&skey="+$("#checkkey"+hidevalue).val();
		switch(selvalue){
			//查看
			case "viewpurchase": showOpenWin('?model=purchase_contract_purchasecontract&action=toReadTab&id='+hidevalue+skeyStr);break;
			//编辑
			case "editpurchase": parent.location='index1.php?model=purchase_contract_purchasecontract&action=init&id='+hidevalue+skeyStr;break;
			//导出采购订单
			case "export": location='index1.php?model=purchase_contract_purchasecontract&action=exportPurchaseOrder&id='+hidevalue+skeyStr;break;
			//附件上传;
			case "uploadFile" : showThickboxWin('?model=purchase_contract_purchasecontract&action=toUploadFile&id='+hidevalue+ "&formType=blue&placeValuesBefore&TB_iframe=true&modal=false&height=300&width=800");break;
			//提交审批
			case "approval": if(confirm('确定要提交审批吗？'))parent.location='index1.php?model=purchase_contract_purchasecontract&action=approvalOrder&id='+hidevalue;break;
//			case "approval": if(confirm('确定要提交审批吗？'))parent.location='controller/purchase/contract/ewf_index.php?actTo=ewfSelect&billId='+hidevalue+'&examCode=oa_purch_apply_basic&formName=采购合同审批';break;
			//删除
			case "delete": if(confirm('确定要删除吗？')) location='index1.php?model=purchase_contract_purchasecontract&action=deletes&id='+hidevalue+'&contNumber='+hidevale2+skeyStr+'&formType=blue&placeValuesBefore&TB_iframe=true&modal=false&height=200&width=400';break;
			case "":break;
			default : break;
		}
		$(this).val("");
	});
	/**
	 * 初始化时时表格隐藏
	 */
	$.each($("table[id^='table']"),function(){
		$(this).hide();
	});

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