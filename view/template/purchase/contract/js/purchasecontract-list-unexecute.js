$(function(){
	$("select.myUnExecute").bind("change",function(){
		var selvalue = $(this).val();
		var hidevalue = $(this).next().val();
		var hidevale2 = $(this).next().next().val();
		switch(selvalue){
			//查看
			case "viewpurchase": location='index1.php?model=purchase_contract_purchasecontract&action=init&perm=view&id='+hidevalue+'&contNumber='+hidevale2;break;
			//编辑
			case "editpurchase": location='index1.php?model=purchase_contract_purchasecontract&action=init&id='+hidevalue;break;
			//启动
			case "begin": location='index1.php?model=purchase_contract_purchasecontract&action=beginPurchaseContract&id='+hidevalue;break;
			//删除
			case "delete": location='index1.php?model=purchase_contract_purchasecontract&action=deletes&id='+hidevalue+'&contNumber='+hidevale2;break;
			case "":break;
			default : break;
		}
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