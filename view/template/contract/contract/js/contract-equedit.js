
// ����ȷ���޸�
$(function() {
			equConfig.type = "edit";
			var rowNum = $('#rowNum').val();
			for (var i = 1; i <= rowNum; i++) {
				getGoodsProducts(i);
			}

			var newEquRowNum = rowNum*1+1;
			getNoGoodsProducts(newEquRowNum);
		});
// ֱ���ύ����
function toEditAudit() {
	document.getElementById('form1').action = "index1.php?model=contract_contract_equ&action=equEdit&act=audit";
}
