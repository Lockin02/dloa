// ����ȷ�� ����
$(function() {
	equConfig.type = "add";
	var rowNum = $('#rowNum').val();
	for (var i = 1; i <= rowNum; i++) {
		getGoodsProducts(i, '?model=common_contract_allsource&action=getProductEqu&sourceType=contract');
	}


	var newEquRowNum = rowNum*1+1;
	getNoGoodsProducts(newEquRowNum);
});

// ֱ���ύ����
function toAddAudit() {
	document.getElementById('form1').action = "index1.php?model=contract_contract_equ&action=equAdd&act=audit";
}