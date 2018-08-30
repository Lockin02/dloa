$(function(){
	$("p select.myPrincipal").bind("change",function(){
		var selvalue = $(this).val();
		var hidevalue = $(this).next().val();
		var hidevale2 = $(this).next().next().val();
		switch(selvalue){
			case "info": showOpenWin('index1.php?model=contract_sales_sales&action=infoTab&id='+hidevalue+'&contNumber='+hidevale2);break;
			case "respon": showThickboxWin('index1.php?model=contract_sales_sales&action=changeprincipal&id='+hidevalue+'&placeValuesBefore&TB_iframe=true&modal=false&height=400&width=800');break;
			case "executor": showThickboxWin('index1.php?model=contract_sales_sales&action=changeExecute&id='+hidevalue+'&placeValuesBefore&TB_iframe=true&modal=false&height=400&width=800');break;
			case "newpurchaseplan": parent.location='index1.php?model=purchase_external_sale&action=toAddPlan&contId='+hidevalue+'&contNumber='+hidevale2;break;
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