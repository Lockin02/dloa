
// 物料确认变更
$(function() {
	equConfig.type = "change";
	var rowNum = $('#rowNum').val();
	for (var i = 1; i <= rowNum; i++) {
		getGoodsProducts(i);
	}
	var newEquRowNum = rowNum*1+1;
	getNoGoodsProducts(newEquRowNum);	
	
	// 是否显示确认变更按钮,目前由罗权洲发起的物料变更操作,可以选择确认变更,销售确认后无须提交审批
	if($("#dealStatus").val() != '1' && $("#dealStatus").val() != '3'){
		$(":submit").eq(1).css("display","none");
	}
});
//
function cc(){
     validate({
		"changeReason" : {
			required : true
		}

	});
}


// 直接提交审批
function toAddAudit() {
//	cc();
	/**
	 * 判断是否来自合同变更,如果是合同变更的,fromSales为1,如果是在变更页面提交审批的fromSales为0 2017-01-05
	 * fromSales为1: 代表需要所有产线确认成本
	 * fromSales为0: 代表只需销售产线概算需要确认,服务的直接用原来的
	 **/
	var fromSales = '&fromSales=0';
	if($("#dealStatus").val() != '1' && $("#dealStatus").val() != '3'){
		fromSales = '&fromSales=1';
	}
	document.getElementById('form1').action = "index1.php?model=contract_contract_equ&action=equChange&act=audit"+fromSales;
}

//提交销售确认,无须审批 --2015.10.27罗权洲提出需求，点击确认变更按钮，流程为：物料变更--销售确认--变更完成
function toNoAudit() {
//	cc();
	document.getElementById('form1').action = "index1.php?model=contract_contract_equ&action=equChange&act=noaudit";
}