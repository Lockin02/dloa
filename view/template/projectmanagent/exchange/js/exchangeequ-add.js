
// ����ȷ�� ����
$(function() {
	equConfig.type = "add";
	var rowNum = $('#rowNum').val();
	for (var i = 1; i <= rowNum; i++) {
		getGoodsProducts(i,
				'?model=common_contract_allsource&action=getProductEqu&sourceType=chance');
	}

	// ��������
	var newEquRowNum = rowNum * 1 + 1;
	getNoGoodsProducts(newEquRowNum);
});

// ֱ���ύ����
function toAddAudit() {
	document.getElementById('form1').action = "index1.php?model=projectmanagent_exchange_exchangeequ&action=equAdd&act=audit";
}