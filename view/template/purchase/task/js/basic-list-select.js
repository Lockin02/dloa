$(function(){
	$("select.myExecuteTask").bind("change",function(){
		var selvalue = $(this).val();
		var hidevalue = $(this).next().val();
		var hidevale2 = $(this).next().next().val();
		var checkValue="&skey="+$("#check"+hidevalue).val();
		switch(selvalue){
			//查看
			case "view": parent.location='index1.php?model=purchase_task_basic&action=read&id='+hidevalue+'&contNumber='+hidevale2+checkValue;break;
			//导出采购任务
			case "export": location='index1.php?model=purchase_task_basic&action=exportTask&id='+hidevalue;break;
			//完成
			case "finish":
				if(confirm('确定完成吗？')){location='index1.php?model=purchase_task_basic&action=end&id='+hidevalue};
				break;
			//关闭
			case "close":
				 $.ajax({//判断是否已进行考核
				    type: "POST",
				    url: "?model=purchase_task_basic&action=isSubClose",
				    data: { id:hidevalue
				    	},
				    async: false,
				    success: function(msg){
				   	   if(msg==1){
				   	   	parent.location='index1.php?model=purchase_task_basic&action=toClose&id='+hidevalue;
				   	   }else{
				   	   		alert("该采购任务已提交关闭申请");
				   	   }
					}
				});
				break;

			//任务反馈
			case "feedback": parent.location='index1.php?model=purchase_task_basic&action=toFeedBack&id='+hidevalue+checkValue;break;
			case "":break;
			default : break;
		}
		$(this).val("");
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