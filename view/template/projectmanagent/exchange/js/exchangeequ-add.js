
// 物料确认 新增
$(function() {
	equConfig.type = "add";
	var rowNum = $('#rowNum').val();
	for (var i = 1; i <= rowNum; i++) {
		getGoodsProducts(i,
				'?model=common_contract_allsource&action=getProductEqu&sourceType=chance');
	}

	// 新增物料
	var newEquRowNum = rowNum * 1 + 1;
	getNoGoodsProducts(newEquRowNum);
});

// 直接提交审批
function toAddAudit() {
	document.getElementById('form1').action = "index1.php?model=projectmanagent_exchange_exchangeequ&action=equAdd&act=audit";
}