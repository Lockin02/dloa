$(function(){
	$("select.myUnExecute").bind("change",function(){
		var selvalue = $(this).val();
		var hidevalue = $(this).next().val();
		var hidevale2 = $(this).next().next().val();
		var taskId = $(this).next().next().next().val();
		var spId = $(this).next().next().next().next().val();
		var skeyStr="&skey="+$("#checkkey"+hidevalue).val();
		switch(selvalue){
			//查看详情
			case "viewinfo": showOpenWin('?model=purchase_contract_purchasecontract&action=toReadTab&id='+hidevalue+skeyStr);break;
			//导出采购订单
			case "export": location='index1.php?model=purchase_contract_purchasecontract&action=exportPurchaseOrder&id='+hidevalue+skeyStr;break;
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
			//查看审批
//			case "viewapproval": location='controller/common/readview.php?itemtype=oa_purch_apply_basic&pid='+hidevalue;break;
			case "viewapproval" : showThickboxWin('controller/common/readview.php?itemtype=oa_purch_apply_basic&pid=' + hidevalue + "&formType=blue&placeValuesBefore&TB_iframe=true&modal=false&height=200&width=800");break;
			//提交审批
			case "cancel":
				if(confirm('确定要撤消审批吗？')){
					$.ajax({
						type : "POST",
						url : "?model=common_workflow_workflow&action=isAuditedContract",
						data : {
							billId :hidevalue,
							examCode : 'oa_purch_apply_basic'
						},
						success : function(msg) {
							if (msg == '1') {
								alert('单据已经存在审批信息，不能撤销审批！');
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