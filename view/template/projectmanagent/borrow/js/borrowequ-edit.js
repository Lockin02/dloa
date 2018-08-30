
// 物料确认修改
$(function() {
	equConfig.type = "edit";
	var rowNum = $('#rowNum').val();
	for (var i = 1; i <= rowNum; i++) {
		getGoodsProducts(i);
	}
	var newEquRowNum = rowNum * 1 + 1;
	getNoGoodsProducts(newEquRowNum);
});

// 直接提交审批
function toEditAudit() {
	document.getElementById('form1').action = "index1.php?model=projectmanagent_borrow_borrowequ&action=equEdit&act=audit";
}